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

// GET: Obtener todos los clientes (Protegido)
app.get('/clientes', verificarToken, async (req, res) => {
  try {
    const result = await pool.query('SELECT * FROM clientes ORDER BY id ASC');
    res.json(result.rows);
  } catch (err) {
    res.status(500).json({ error: 'Error al consultar clientes', detalle: err.message });
  }
});

// POST: Crear cliente (Protegido)
app.post('/clientes', verificarToken, async (req, res) => {
  const { nombre, email, telefono } = req.body;
  try {
    const result = await pool.query(
      'INSERT INTO clientes (nombre, email, telefono) VALUES ($1, $2, $3) RETURNING *',
      [nombre, email, telefono]
    );
    res.status(201).json({ mensaje: 'Cliente creado exitosamente', cliente: result.rows[0] });
  } catch (err) {
    res.status(500).json({ error: 'Error al crear cliente', detalle: err.message });
  }
});

// PUT: Actualizar cliente (Protegido)
app.put('/clientes/:id', verificarToken, async (req, res) => {
  const { id } = req.params;
  const { nombre, email, telefono } = req.body;
  try {
    const result = await pool.query(
      'UPDATE clientes SET nombre = $1, email = $2, telefono = $3 WHERE id = $4 RETURNING *',
      [nombre, email, telefono, id]
    );
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Cliente no encontrado' });
    }
    res.json({ mensaje: 'Cliente actualizado exitosamente', cliente: result.rows[0] });
  } catch (err) {
    res.status(500).json({ error: 'Error al actualizar cliente', detalle: err.message });
  }
});

// DELETE: Eliminar cliente (Protegido)
app.delete('/clientes/:id', verificarToken, async (req, res) => {
  const { id } = req.params;
  try {
    const result = await pool.query('DELETE FROM clientes WHERE id = $1 RETURNING *', [id]);
    if (result.rows.length === 0) {
      return res.status(404).json({ error: 'Cliente no encontrado' });
    }
    res.json({ mensaje: 'Cliente eliminado exitosamente' });
  } catch (err) {
    res.status(500).json({ error: 'Error al eliminar cliente', detalle: err.message });
  }
});

const PORT = process.env.PORT || 3006;
app.listen(PORT, () => {
  console.log(`MS-Clientes corriendo en el puerto ${PORT}`);
});