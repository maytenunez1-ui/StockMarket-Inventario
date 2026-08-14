const express = require('express');
const { Pool } = require('pg');
const path = require('path');
const jwt = require('jsonwebtoken');

require('dotenv').config({ path: path.join(__dirname, '.env') });

const app = express();
app.use(express.json());

const pool = new Pool({
  connectionString: process.env.DATABASE_URL,
  ssl: { rejectUnauthorized: false }
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

// GET: Obtener órdenes de compra (Protegido)
app.get('/ordenes', verificarToken, async (req, res) => {
  try {
    const result = await pool.query('SELECT * FROM ordenes_compra ORDER BY id ASC');
    res.json(result.rows);
  } catch (err) {
    res.status(500).json({ error: 'Error al consultar órdenes', detalle: err.message });
  }
});

// POST: Crear orden de compra (Protegido)
app.post('/ordenes', verificarToken, async (req, res) => {
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
app.delete('/ordenes/:id', verificarToken, async (req, res) => {
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