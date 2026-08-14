-- Ejecutar una sola vez en la base PostgreSQL de Amazon RDS.
CREATE TABLE IF NOT EXISTS usuarios (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  rol VARCHAR(20) NOT NULL DEFAULT 'operador'
);

CREATE TABLE IF NOT EXISTS productos (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  precio NUMERIC(10,2) NOT NULL,
  codigo_barras VARCHAR(50) UNIQUE NOT NULL,
  categoria_id INTEGER
);

CREATE TABLE IF NOT EXISTS inventario (
  id SERIAL PRIMARY KEY,
  producto_id INTEGER NOT NULL,
  stock_actual INTEGER NOT NULL DEFAULT 0,
  ubicacion_bodega VARCHAR(100) NOT NULL DEFAULT 'Bodega Principal'
);

CREATE TABLE IF NOT EXISTS proveedores (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  contacto VARCHAR(100)
);

CREATE TABLE IF NOT EXISTS clientes (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  email VARCHAR(100) UNIQUE,
  telefono VARCHAR(30)
);

CREATE TABLE IF NOT EXISTS ordenes_compra (
  id SERIAL PRIMARY KEY,
  proveedor_id INTEGER NOT NULL,
  producto_id INTEGER NOT NULL,
  monto_total NUMERIC(10,2) NOT NULL,
  fecha_emision DATE NOT NULL DEFAULT CURRENT_DATE
);

-- Usuario inicial: admin@stockmarket.com / 1234. Cámbialo tras entrar.
INSERT INTO usuarios (nombre, email, password, rol)
VALUES ('Administrador', 'admin@stockmarket.com', '$2b$10$HZbNq5BF7mvc0/pxLmt9qOsLV6.qv2ajEsWo51BN3NWuWJkR24Mnq', 'admin')
ON CONFLICT (email) DO NOTHING;
