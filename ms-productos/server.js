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

// GET: Obtener todos los productos (Protegido)
app.get('/productos', verificarToken, async (req, res) => {
  try {
    const result = await pool.query('SELECT * FROM productos ORDER BY id ASC');
    res.json(result.rows);
  } catch (err) {
    res.status(500).json({ error: 'Error al consultar productos', detalle: err.message });
  }
});

// POST: Crear un nuevo producto (Protegido)
app.post('/productos', verificarToken, async (req, res) => {
  const { nombre, precio, codigo_barras, categoria_id } = req.body;
  try {
    const result = await pool.query(
      'INSERT INTO productos (nombre, precio, codigo_barras, categoria_id) VALUES ($1, $2, $3, $4) RETURNING *',
      [nombre, precio, codigo_barras, categoria_id || null]
    );
    res.status(201).json({ mensaje: 'Producto creado exitosamente', producto: result.rows[0] });
  } catch (err) {
    res.status(500).json({ error: 'Error al crear producto', detalle: err.message });
  }
});

// PUT: Actualizar un producto (Protegido)
app.put('/productos/:id', verificarToken, async (req, res) => {
  const { id } = req.params;
  const { nombre, precio, codigo_barras, categoria_id } = req.body;
  try {
    const result = await pool.query(
      'UPDATE productos SET nombre = $1, precio = $2, codigo_barras = $3, categoria_id = $4 WHERE id = $5 RETURNING *',
      [nombre, precio, codigo_barras, categoria_id, id]
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
app.delete('/productos/:id', verificarToken, async (req, res) => {
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
