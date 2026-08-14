-- Migración idempotente para catálogo, perfiles de cliente y compras con delivery.
ALTER TABLE productos ADD COLUMN IF NOT EXISTS imagen_url TEXT;
ALTER TABLE productos ADD COLUMN IF NOT EXISTS descripcion TEXT;
ALTER TABLE productos ADD COLUMN IF NOT EXISTS activo BOOLEAN NOT NULL DEFAULT TRUE;
ALTER TABLE clientes ADD COLUMN IF NOT EXISTS usuario_id INTEGER UNIQUE;
ALTER TABLE clientes ADD COLUMN IF NOT EXISTS direccion TEXT;

CREATE TABLE IF NOT EXISTS pedidos_cliente (
  id SERIAL PRIMARY KEY,
  cliente_id INTEGER NOT NULL REFERENCES clientes(id),
  direccion_entrega TEXT NOT NULL,
  telefono_entrega VARCHAR(30) NOT NULL,
  metodo_pago VARCHAR(40) NOT NULL DEFAULT 'Efectivo contra entrega',
  estado VARCHAR(30) NOT NULL DEFAULT 'Pendiente',
  subtotal NUMERIC(10,2) NOT NULL,
  costo_delivery NUMERIC(10,2) NOT NULL DEFAULT 50.00,
  total NUMERIC(10,2) NOT NULL,
  creado_en TIMESTAMPTZ NOT NULL DEFAULT NOW()
);

CREATE TABLE IF NOT EXISTS pedido_detalles (
  id SERIAL PRIMARY KEY,
  pedido_id INTEGER NOT NULL REFERENCES pedidos_cliente(id) ON DELETE CASCADE,
  producto_id INTEGER NOT NULL REFERENCES productos(id),
  cantidad INTEGER NOT NULL CHECK (cantidad > 0),
  precio_unitario NUMERIC(10,2) NOT NULL
);

-- Cuentas de demostración: administrador y cliente.
INSERT INTO usuarios (nombre, email, password, rol)
VALUES
  ('Administrador', 'admin@stockmarket.com', '$2b$10$HZbNq5BF7mvc0/pxLmt9qOsLV6.qv2ajEsWo51BN3NWuWJkR24Mnq', 'admin'),
  ('Cliente Demo', 'cliente@stockmarket.com', '$2b$10$HZbNq5BF7mvc0/pxLmt9qOsLV6.qv2ajEsWo51BN3NWuWJkR24Mnq', 'cliente')
ON CONFLICT (email) DO NOTHING;

INSERT INTO clientes (nombre, email, telefono, direccion, usuario_id)
SELECT 'Cliente Demo', 'cliente@stockmarket.com', '9999-0000', 'Colonia Palmira, Tegucigalpa', id
FROM usuarios WHERE email = 'cliente@stockmarket.com'
ON CONFLICT (email) DO UPDATE SET usuario_id = EXCLUDED.usuario_id;

INSERT INTO productos (nombre, precio, codigo_barras, categoria_id, descripcion, imagen_url)
VALUES
 ('Leche entera 1 L', 35.00, 'DEMO-001', 1, 'Leche fresca de larga duración.', 'https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=600&q=80'),
 ('Pan integral', 48.00, 'DEMO-002', 1, 'Pan suave y fresco para todos los días.', 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80'),
 ('Manzanas rojas', 72.00, 'DEMO-003', 1, 'Bolsa de manzanas seleccionadas.', 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=600&q=80')
ON CONFLICT (codigo_barras) DO NOTHING;

INSERT INTO inventario (producto_id, stock_actual, ubicacion_bodega)
SELECT p.id, 25, 'Tienda principal' FROM productos p
WHERE p.codigo_barras IN ('DEMO-001','DEMO-002','DEMO-003')
  AND NOT EXISTS (SELECT 1 FROM inventario i WHERE i.producto_id = p.id);
