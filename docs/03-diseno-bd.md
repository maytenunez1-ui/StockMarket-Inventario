# 🗄️ Diseño de Base de Datos - StockMarket-Inventario

Este documento define las 6 tablas principales del sistema organizadas bajo el patrón **Database per Service**, cumpliendo con normalización en **3FN**, integridad referencial y las relaciones 1:N y N:M exigidas.

---

## 🛠️ Script SQL de Creación

```sql
-- BD 1: MS-Auth
CREATE TABLE usuarios (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    rol VARCHAR(20) DEFAULT 'OPERADOR'
);

-- BD 2: MS-Categorias
CREATE TABLE categorias (
    id SERIAL PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT
);

-- BD 3: MS-Productos (Relación 1:N con Categorías)
CREATE TABLE productos (
    id SERIAL PRIMARY KEY,
    codigo_barras VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10, 2) NOT NULL,
    categoria_id INT,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id)
);

-- BD 4: MS-Proveedores
CREATE TABLE proveedores (
    id SERIAL PRIMARY KEY,
    rtn VARCHAR(20) UNIQUE NOT NULL,
    nombre_empresa VARCHAR(100) NOT NULL,
    telefono VARCHAR(20)
);

-- BD 5: MS-Inventario (Relación 1:N con Productos)
CREATE TABLE inventario (
    id SERIAL PRIMARY KEY,
    producto_id INT NOT NULL,
    stock_actual INT NOT NULL DEFAULT 0,
    ubicacion_bodega VARCHAR(50),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);

-- BD 6: MS-Ordenes (Relación N:M entre Productos y Proveedores)
CREATE TABLE ordenes_compra (
    id SERIAL PRIMARY KEY,
    proveedor_id INT NOT NULL,
    fecha_emision DATE DEFAULT CURRENT_DATE,
    total DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id)
);

CREATE TABLE detalle_ordenes (
    id SERIAL PRIMARY KEY,
    orden_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (orden_id) REFERENCES ordenes_compra(id),
    FOREIGN KEY (producto_id) REFERENCES productos(id)
);
