const express = require('express');
const { Pool } = require('pg');
const path = require('path');
const jwt = require('jsonwebtoken'); // 👈 1. Importamos JWT

require('dotenv').config({ path: path.join(__dirname, '.env') });

const app = express();
app.use(express.json());

const pool = new Pool({
  connectionString: process.env.DATABASE_URL,
  ssl: { rejectUnauthorized: false }
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

// GET: Obtener todo el inventario (Protegido)
app.get('/inventario', verificarToken, async (req, res) => {
  try {
    const result = await pool.query('SELECT * FROM inventario ORDER BY id ASC');
    res.json(result.rows);
  } catch (err) {
    res.status(500).json({ error: 'Error al consultar inventario', detalle: err.message });
  }
});

// POST: Registrar stock de un producto (Protegido)
app.post('/inventario', verificarToken, async (req, res) => {
  const { producto_id, stock_actual, ubicacion_bodega } = req.body;
  try {
    const result = await pool.query(
      'INSERT INTO inventario (producto_id, stock_actual, ubicacion_bodega) VALUES ($1, $2, $3) RETURNING *',
      [producto_id, stock_actual || 0, ubicacion_bodega || 'Bodega Principal']
    );
    res.status(201).json({ mensaje: 'Stock registrado exitosamente', inventario: result.rows[0] });
  } catch (err) {
    res.status(500).json({ error: 'Error al registrar inventario', detalle: err.message });
  }
});

// PUT: Actualizar stock de un producto (Protegido)
app.put('/inventario/:id', verificarToken, async (req, res) => {
  const { id } = req.params;
  const { stock_actual, ubicacion_bodega } = req.body;
  try {
    const result = await pool.query(
      'UPDATE inventario SET stock_actual = $1, ubicacion_bodega = $2 WHERE id = $3 RETURNING *',
      [stock_actual, ubicacion_bodega, id]
    );
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Registro de inventario no encontrado' });
    }
    res.json({ mensaje: 'Inventario actualizado exitosamente', inventario: result.rows[0] });
  } catch (err) {
    res.status(500).json({ error: 'Error al actualizar inventario', detalle: err.message });
  }
});

// DELETE: Eliminar registro de inventario (Protegido)
app.delete('/inventario/:id', verificarToken, async (req, res) => {
  const { id } = req.params;
  try {
    const result = await pool.query('DELETE FROM inventario WHERE id = $1 RETURNING *', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Registro no encontrado' });
    }
    res.json({ mensaje: 'Registro de inventario eliminado exitosamente' });
  } catch (err) {
    res.status(500).json({ error: 'Error al eliminar inventario', detalle: err.message });
  }
});

const PORT = process.env.PORT || 3003;
app.listen(PORT, () => {
  console.log(`MS-Inventario corriendo en el puerto ${PORT}`);
});