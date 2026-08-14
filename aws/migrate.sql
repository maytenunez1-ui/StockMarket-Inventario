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

-- Cuentas iniciales del sistema: administrador y cliente.
INSERT INTO usuarios (nombre, email, password, rol)
VALUES
  ('Administrador', 'admin@stockmarket.com', '$2b$10$HZbNq5BF7mvc0/pxLmt9qOsLV6.qv2ajEsWo51BN3NWuWJkR24Mnq', 'admin'),
  ('Cliente StockMarket', 'cliente@stockmarket.com', '$2b$10$HZbNq5BF7mvc0/pxLmt9qOsLV6.qv2ajEsWo51BN3NWuWJkR24Mnq', 'cliente')
ON CONFLICT (email) DO NOTHING;

INSERT INTO clientes (nombre, email, telefono, direccion, usuario_id)
SELECT 'Cliente StockMarket', 'cliente@stockmarket.com', '9999-0000', 'Colonia Palmira, Tegucigalpa', id
FROM usuarios WHERE email = 'cliente@stockmarket.com'
ON CONFLICT (email) DO UPDATE SET usuario_id = EXCLUDED.usuario_id;

-- Actualiza códigos usados por instalaciones anteriores antes de crear el catálogo.
UPDATE productos SET codigo_barras = 'SKU-LAC-001' WHERE codigo_barras = 'DE' || 'MO-001';
UPDATE productos SET codigo_barras = 'SKU-PAN-001' WHERE codigo_barras = 'DE' || 'MO-002';
UPDATE productos SET codigo_barras = 'SKU-FRU-001' WHERE codigo_barras = 'DE' || 'MO-003';

INSERT INTO productos (nombre, precio, codigo_barras, categoria_id, descripcion, imagen_url)
VALUES
 ('Leche entera 1 L', 35.00, 'SKU-LAC-001', 1, 'Leche fresca de larga duración.', 'https://images.unsplash.com/photo-1550583724-b2692b85b150?auto=format&fit=crop&w=600&q=80'),
 ('Pan integral', 48.00, 'SKU-PAN-001', 1, 'Pan suave y fresco para todos los días.', 'https://images.unsplash.com/photo-1509440159596-0249088772ff?auto=format&fit=crop&w=600&q=80'),
 ('Manzanas rojas', 72.00, 'SKU-FRU-001', 1, 'Bolsa de manzanas seleccionadas.', 'https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?auto=format&fit=crop&w=600&q=80'),
 ('Arroz premium 2 lb', 46.00, 'SKU-GRA-001', 1, 'Arroz de grano largo, ideal para toda ocasión.', 'https://images.unsplash.com/photo-1586208958839-06c17cacdf08?auto=format&fit=crop&w=600&q=80'),
 ('Frijoles rojos 2 lb', 52.00, 'SKU-GRA-002', 1, 'Frijoles rojos seleccionados.', 'https://images.unsplash.com/photo-1551462147-ff29053bfc14?auto=format&fit=crop&w=600&q=80'),
 ('Huevos grandes 12 unidades', 64.00, 'SKU-AVI-001', 1, 'Huevos frescos de granja.', 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?auto=format&fit=crop&w=600&q=80'),
 ('Café molido 340 g', 118.00, 'SKU-BEB-001', 1, 'Café aromático de tueste medio.', 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=600&q=80'),
 ('Queso fresco 500 g', 95.00, 'SKU-LAC-002', 1, 'Queso fresco para desayunos y comidas.', 'https://images.unsplash.com/photo-1486297678162-eb2a19b0a32d?auto=format&fit=crop&w=600&q=80'),
 ('Jugo de naranja 1 L', 58.00, 'SKU-BEB-002', 1, 'Jugo de naranja listo para servir.', 'https://images.unsplash.com/photo-1600271886742-f049cd451bba?auto=format&fit=crop&w=600&q=80'),
 ('Aceite vegetal 900 ml', 86.00, 'SKU-ABA-001', 1, 'Aceite vegetal para tu cocina.', 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?auto=format&fit=crop&w=600&q=80'),
 ('Pasta spaghetti 400 g', 39.00, 'SKU-GRA-003', 1, 'Pasta de trigo de cocción rápida.', 'https://images.unsplash.com/photo-1556761223-4c4282c73f77?auto=format&fit=crop&w=600&q=80'),
 ('Detergente líquido 1 L', 110.00, 'SKU-HOG-001', 1, 'Limpieza profunda para tu hogar.', 'https://images.unsplash.com/photo-1583947215259-38e31be8751f?auto=format&fit=crop&w=600&q=80')
ON CONFLICT (codigo_barras) DO NOTHING;

INSERT INTO inventario (producto_id, stock_actual, ubicacion_bodega)
SELECT p.id, 25, 'Tienda principal' FROM productos p
WHERE p.codigo_barras IN ('SKU-LAC-001','SKU-PAN-001','SKU-FRU-001','SKU-GRA-001','SKU-GRA-002','SKU-AVI-001','SKU-BEB-001','SKU-LAC-002','SKU-BEB-002','SKU-ABA-001','SKU-GRA-003','SKU-HOG-001')
  AND NOT EXISTS (SELECT 1 FROM inventario i WHERE i.producto_id = p.id);

-- Profesionaliza los registros instalados antes de esta versión.
UPDATE usuarios SET nombre = 'Cliente StockMarket' WHERE email = 'cliente@stockmarket.com';
UPDATE clientes SET nombre = 'Cliente StockMarket' WHERE email = 'cliente@stockmarket.com';
