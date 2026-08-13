# 🗄️ Diseño de Base de Datos - StockMarket-Inventario

Este documento define las 6 tablas principales del sistema organizadas bajo el patrón **Database per Service**, cumpliendo con normalización en **3FN**, integridad referencial y claves primarias/foráneas[cite: 1].

---

## 📐 Diagrama Entidad-Relación

```mermaid
erDiagram
    USUARIOS {
        int id PK
        string nombre
        string email UK
        string password
        string rol
    }

    CATEGORIAS {
        int id PK
        string nombre
        string descripcion
    }

    PRODUCTOS {
        int id PK
        string codigo_barras UK
        string nombre
        decimal precio
        int categoria_id FK
    }

    PROVEEDORES {
        int id PK
        string rtn UK
        string nombre_empresa
        string telefono
    }

    INVENTARIO {
        int id PK
        int producto_id FK
        int stock_actual
        string ubicacion_bodega
    }

    ORDENES_COMPRA {
        int id PK
        int proveedor_id FK
        date fecha_emision
        decimal total
    }

    DETALLE_ORDENES {
        int id PK
        int orden_id FK
        int producto_id FK
        int cantidad
        decimal precio_unitario
    }

    CATEGORIAS ||--o{ PRODUCTOS : posee
    PRODUCTOS ||--o{ INVENTARIO : tiene
    PROVEEDORES ||--o{ ORDENES_COMPRA : emite
    ORDENES_COMPRA ||--|{ DETALLE_ORDENES : contiene
    PRODUCTOS ||--|{ DETALLE_ORDENES : incluye



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

-- BD 3: MS-Productos
CREATE TABLE productos (
    id SERIAL PRIMARY KEY,
    codigo_barras VARCHAR(50) UNIQUE NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    precio DECIMAL(10,2) NOT NULL,
    categoria_id INT NOT NULL
);

-- BD 4: MS-Proveedores
CREATE TABLE proveedores (
    id SERIAL PRIMARY KEY,
    rtn VARCHAR(20) UNIQUE NOT NULL,
    nombre_empresa VARCHAR(100) NOT NULL,
    telefono VARCHAR(20) NOT NULL
);

-- BD 5: MS-Inventario
CREATE TABLE inventario (
    id SERIAL PRIMARY KEY,
    producto_id INT NOT NULL,
    stock_actual INT NOT NULL DEFAULT 0,
    ubicacion_bodega VARCHAR(50) NOT NULL
);

-- BD 6: MS-Ordenes
CREATE TABLE ordenes_compra (
    id SERIAL PRIMARY KEY,
    proveedor_id INT NOT NULL,
    fecha_emision DATE NOT NULL,
    total DECIMAL(10,2) NOT NULL
);

CREATE TABLE detalle_ordenes (
    id SERIAL PRIMARY KEY,
    orden_id INT NOT NULL,
    producto_id INT NOT NULL,
    cantidad INT NOT NULL,
    precio_unitario DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (orden_id) REFERENCES ordenes_compra(id) ON DELETE CASCADE
);
