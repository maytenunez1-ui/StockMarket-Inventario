const express = require('express');
const { Pool } = require('pg');
const path = require('path');
const jwt = require('jsonwebtoken');

require('dotenv').config({ path: path.join(__dirname, '.env') });

const app = express();
app.use(express.json());

const pool = new Pool({
  connectionString: process.env.DATABASE_URL,
  ssl: process.env.DATABASE_SSL === 'true' ? { rejectUnauthorized: false } : false
});

const verificarToken = (req, res, next) => {
  const authHeader = req.headers['authorization'];
  const token = authHeader && authHeader.split(' ')[1];

  if (!token) {
    return res.status(401).json({ error: 'Acceso denegado: Token no proporcionado' });
  }

  try {
    const verificado = jwt.verify(token, process.env.JWT_SECRET || 'clave_secreta_supermercado');
    req.usuario = verificado;
    next();
  } catch (err) {
    return res.status(403).json({ error: 'Token inválido o expirado' });
  }
};
const soloAdmin = (req, res, next) => req.usuario?.rol === 'admin' ? next() : res.status(403).json({ error: 'Esta acción requiere una cuenta administradora' });

// GET: Obtener órdenes de compra (Protegido)
app.get('/ordenes', verificarToken, soloAdmin, async (req, res) => {
  try {
    const result = await pool.query('SELECT * FROM ordenes_compra ORDER BY id ASC');
    res.json(result.rows);
  } catch (err) {
    res.status(500).json({ error: 'Error al consultar órdenes', detalle: err.message });
  }
});

// POST: Crear orden de compra (Protegido)
app.post('/ordenes', verificarToken, soloAdmin, async (req, res) => {
  const { proveedor_id, producto_id, monto_total, fecha_emision } = req.body;
  try {
    const result = await pool.query(
      'INSERT INTO ordenes_compra (proveedor_id, producto_id, monto_total, fecha_emision) VALUES ($1, $2, $3, $4) RETURNING *',
      [proveedor_id, producto_id, monto_total, fecha_emision || new Date()]
    );
    res.status(201).json({ mensaje: 'Orden creada exitosamente', orden: result.rows[0] });
  } catch (err) {
    res.status(500).json({ error: 'Error al crear orden', detalle: err.message });
  }
});

// DELETE: Eliminar orden (Protegido)
app.delete('/ordenes/:id', verificarToken, soloAdmin, async (req, res) => {
  const { id } = req.params;
  try {
    const result = await pool.query('DELETE FROM ordenes_compra WHERE id = $1 RETURNING *', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Orden no encontrada' });
    }
    res.json({ mensaje: 'Orden eliminada exitosamente' });
  } catch (err) {
    res.status(500).json({ error: 'Error al eliminar orden', detalle: err.message });
  }
});

const PORT = process.env.PORT || 3007;
app.listen(PORT, () => {
  console.log(`MS-Ordenes corriendo en el puerto ${PORT}`);
});

app.get('/pedidos', verificarToken, soloAdmin, async (req, res) => {
  try {
    const result = await pool.query(`SELECT p.*, c.nombre AS cliente_nombre, c.email AS cliente_email
      FROM pedidos_cliente p JOIN clientes c ON c.id = p.cliente_id ORDER BY p.creado_en DESC`);
    res.json(result.rows);
  } catch (err) { res.status(500).json({ error: 'Error al consultar pedidos', detalle: err.message }); }
});

app.get('/pedidos/mis', verificarToken, async (req, res) => {
  try {
    const result = await pool.query(`SELECT p.* FROM pedidos_cliente p JOIN clientes c ON c.id = p.cliente_id
      WHERE c.usuario_id = $1 ORDER BY p.creado_en DESC`, [req.usuario.id]);
    res.json(result.rows);
  } catch (err) { res.status(500).json({ error: 'Error al consultar pedidos', detalle: err.message }); }
});

app.post('/pedidos', verificarToken, async (req, res) => {
  const { items, direccion_entrega, telefono_entrega, metodo_pago } = req.body;
  if (!Array.isArray(items) || !items.length || !direccion_entrega || !telefono_entrega) return res.status(400).json({ error: 'Agrega productos, dirección y teléfono de entrega' });
  const client = await pool.connect();
  try {
    await client.query('BEGIN');
    let perfil = await client.query('SELECT id FROM clientes WHERE usuario_id = $1', [req.usuario.id]);
    if (!perfil.rows.length) {
      perfil = await client.query('INSERT INTO clientes (nombre, email, telefono, direccion, usuario_id) VALUES ($1,$2,$3,$4,$5) RETURNING id', [req.usuario.nombre, req.usuario.email, telefono_entrega, direccion_entrega, req.usuario.id]);
    }
    let subtotal = 0; const detalles = [];
    for (const item of items) {
      const cantidad = Number(item.cantidad);
      if (!Number.isInteger(cantidad) || cantidad < 1) throw new Error('Cantidad inválida');
      const producto = await client.query(`SELECT p.id,p.nombre,p.precio,COALESCE(i.stock_actual,0) stock_actual,i.id inventario_id
        FROM productos p LEFT JOIN inventario i ON i.producto_id=p.id WHERE p.id=$1 AND p.activo IS DISTINCT FROM FALSE FOR UPDATE`, [item.producto_id]);
      if (!producto.rows.length) throw new Error('Producto no disponible');
      const p = producto.rows[0]; if (Number(p.stock_actual) < cantidad) throw new Error(`${p.nombre} no tiene existencias suficientes`);
      subtotal += Number(p.precio) * cantidad; detalles.push({ ...p, cantidad });
    }
    const delivery = 50; const pedido = await client.query(`INSERT INTO pedidos_cliente (cliente_id,direccion_entrega,telefono_entrega,metodo_pago,subtotal,costo_delivery,total)
      VALUES ($1,$2,$3,$4,$5,$6,$7) RETURNING *`, [perfil.rows[0].id,direccion_entrega,telefono_entrega,metodo_pago || 'Efectivo contra entrega',subtotal,delivery,subtotal + delivery]);
    for (const d of detalles) {
      await client.query('INSERT INTO pedido_detalles (pedido_id,producto_id,cantidad,precio_unitario) VALUES ($1,$2,$3,$4)', [pedido.rows[0].id,d.id,d.cantidad,d.precio]);
      await client.query('UPDATE inventario SET stock_actual=stock_actual-$1 WHERE id=$2', [d.cantidad,d.inventario_id]);
    }
    await client.query('COMMIT'); res.status(201).json({ mensaje:'Pedido recibido para delivery', pedido:pedido.rows[0] });
  } catch (err) { await client.query('ROLLBACK'); res.status(400).json({ error: err.message || 'No fue posible crear el pedido' }); }
  finally { client.release(); }
});

app.patch('/pedidos/:id/estado', verificarToken, soloAdmin, async (req, res) => {
  const permitidos = ['Pendiente','En preparación','En camino','Entregado','Cancelado'];
  if (!permitidos.includes(req.body.estado)) return res.status(400).json({ error: 'Estado no válido' });
  try { const r=await pool.query('UPDATE pedidos_cliente SET estado=$1 WHERE id=$2 RETURNING *',[req.body.estado,req.params.id]); if(!r.rows.length)return res.status(404).json({error:'Pedido no encontrado'});res.json(r.rows[0]); }
  catch(err){res.status(500).json({error:'No fue posible actualizar el pedido',detalle:err.message});}
});
