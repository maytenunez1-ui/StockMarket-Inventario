const express = require('express');
const { Pool } = require('pg');
const path = require('path');
const jwt = require('jsonwebtoken'); // 👈 1. Importamos JWT

require('dotenv').config({ path: path.join(__dirname, '.env') });

const app = express();
app.use(express.json());

const pool = new Pool({
  connectionString: process.env.DATABASE_URL,
  ssl: process.env.DATABASE_SSL === 'true' ? { rejectUnauthorized: false } : false
});

// 👈 2. Middleware para proteger las rutas con JWT
const verificarToken = (req, res, next) => {
  const authHeader = req.headers['authorization'];
  const token = authHeader && authHeader.split(' ')[1]; // Espera formato: "Bearer TOKEN"

  if (!token) {
    return res.status(401).json({ error: 'Acceso denegado: Token no proporcionado' });
  }

  try {
    const verificado = jwt.verify(token, process.env.JWT_SECRET || 'clave_secreta_supermercado');
    req.usuario = verificado;
    next(); // Permite pasar a la ruta
  } catch (err) {
    return res.status(403).json({ error: 'Token inválido o expirado' });
  }
};

const soloAdmin = (req, res, next) => {
  if (req.usuario?.rol !== 'admin') return res.status(403).json({ error: 'Esta acción requiere una cuenta administradora' });
  next();
};

// Catálogo de compra: no expone datos internos ni requiere sesión.
app.get('/productos/publicos', async (req, res) => {
  try {
    const result = await pool.query(`SELECT p.id, p.nombre, p.precio, p.codigo_barras,
      p.descripcion, p.imagen_url, COALESCE(i.stock_actual, 0) AS stock_actual
      FROM productos p LEFT JOIN inventario i ON i.producto_id = p.id
      WHERE p.activo IS DISTINCT FROM FALSE ORDER BY p.id ASC`);
    res.json(result.rows);
  } catch (err) { res.status(500).json({ error: 'Error al consultar catálogo', detalle: err.message }); }
});

// GET: Obtener todos los productos (Protegido)
app.get('/productos', verificarToken, soloAdmin, async (req, res) => {
  try {
    const result = await pool.query('SELECT * FROM productos ORDER BY id ASC');
    res.json(result.rows);
  } catch (err) {
    res.status(500).json({ error: 'Error al consultar productos', detalle: err.message });
  }
});

// POST: Crear un nuevo producto (Protegido)
app.post('/productos', verificarToken, soloAdmin, async (req, res) => {
  const { nombre, precio, codigo_barras, categoria_id, descripcion, imagen_url, stock_actual } = req.body;
  if (!nombre || !precio || !codigo_barras) return res.status(400).json({ error: 'Nombre, precio y código son obligatorios' });
  try {
    const result = await pool.query(
      'INSERT INTO productos (nombre, precio, codigo_barras, categoria_id, descripcion, imagen_url) VALUES ($1, $2, $3, $4, $5, $6) RETURNING *',
      [nombre, precio, codigo_barras, categoria_id || null, descripcion || null, imagen_url || null]
    );
    await pool.query('INSERT INTO inventario (producto_id, stock_actual, ubicacion_bodega) VALUES ($1, $2, $3)', [result.rows[0].id, Number(stock_actual) || 0, 'Tienda principal']);
    res.status(201).json({ mensaje: 'Producto creado exitosamente', producto: result.rows[0] });
  } catch (err) {
    res.status(500).json({ error: 'Error al crear producto', detalle: err.message });
  }
});

// PUT: Actualizar un producto (Protegido)
app.put('/productos/:id', verificarToken, soloAdmin, async (req, res) => {
  const { id } = req.params;
  const { nombre, precio, codigo_barras, categoria_id, descripcion, imagen_url, activo } = req.body;
  try {
    const result = await pool.query(
      'UPDATE productos SET nombre = $1, precio = $2, codigo_barras = $3, categoria_id = $4, descripcion = $5, imagen_url = $6, activo = COALESCE($7, activo) WHERE id = $8 RETURNING *',
      [nombre, precio, codigo_barras, categoria_id, descripcion || null, imagen_url || null, activo, id]
    );
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Producto no encontrado' });
    }
    res.json({ mensaje: 'Producto actualizado exitosamente', producto: result.rows[0] });
  } catch (err) {
    res.status(500).json({ error: 'Error al actualizar producto', detalle: err.message });
  }
});

// DELETE: Eliminar un producto (Protegido)
app.delete('/productos/:id', verificarToken, soloAdmin, async (req, res) => {
  const { id } = req.params;
  try {
    const result = await pool.query('DELETE FROM productos WHERE id = $1 RETURNING *', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Producto no encontrado' });
    }
    res.json({ mensaje: 'Producto eliminado exitosamente' });
  } catch (err) {
    res.status(500).json({ error: 'Error al eliminar producto', detalle: err.message });
  }
});

const PORT = process.env.PORT || 3002;
app.listen(PORT, () => {
  console.log(`MS-Productos corriendo en el puerto ${PORT}`);
});
