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

// GET: Obtener proveedores (Protegido)
app.get('/proveedores', verificarToken, async (req, res) => {
  try {
    const result = await pool.query('SELECT * FROM proveedores ORDER BY id ASC');
    res.json(result.rows);
  } catch (err) {
    res.status(500).json({ error: 'Error al consultar proveedores', detalle: err.message });
  }
});

// POST: Crear proveedor (Protegido)
app.post('/proveedores', verificarToken, async (req, res) => {
  const { nombre, contacto } = req.body;
  try {
    const result = await pool.query(
      'INSERT INTO proveedores (nombre, contacto) VALUES ($1, $2) RETURNING *',
      [nombre, contacto]
    );
    res.status(201).json({ mensaje: 'Proveedor creado exitosamente', proveedor: result.rows[0] });
  } catch (err) {
    res.status(500).json({ error: 'Error al crear proveedor', detalle: err.message });
  }
});

// PUT: Actualizar proveedor (Protegido)
app.put('/proveedores/:id', verificarToken, async (req, res) => {
  const { id } = req.params;
  const { nombre, contacto } = req.body;
  try {
    const result = await pool.query(
      'UPDATE proveedores SET nombre = $1, contacto = $2 WHERE id = $3 RETURNING *',
      [nombre, contacto, id]
    );
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Proveedor no encontrado' });
    }
    res.json({ mensaje: 'Proveedor actualizado exitosamente', proveedor: result.rows[0] });
  } catch (err) {
    res.status(500).json({ error: 'Error al actualizar proveedor', detalle: err.message });
  }
});

// DELETE: Eliminar proveedor (Protegido)
app.delete('/proveedores/:id', verificarToken, async (req, res) => {
  const { id } = req.params;
  try {
    const result = await pool.query('DELETE FROM proveedores WHERE id = $1 RETURNING *', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Proveedor no encontrado' });
    }
    res.json({ mensaje: 'Proveedor eliminado exitosamente' });
  } catch (err) {
    res.status(500).json({ error: 'Error al eliminar proveedor', detalle: err.message });
  }
});

const PORT = process.env.PORT || 3005;
app.listen(PORT, () => {
  console.log(`MS-Proveedores corriendo en el puerto ${PORT}`);
});
