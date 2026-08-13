# 🛒 StockMarket-Inventario
**Sistema Web de Gestión de Inventario para Supermercados - Proyecto Grupo #4**  
*Ingeniería de Software II - Universidad José Cecilio del Valle*

---

## 📌 1. Descripción del Proyecto
Aplicación web desacoplada para el control, monitoreo y gestión en tiempo real de inventarios en supermercados, desarrollada con una arquitectura basada en **6 microservicios independientes**.

* **Problema:** Control ineficiente de stock, falta de trazabilidad en entradas/salidas y retrasos en actualización de existencias.
* **Objetivo:** Implementar un sistema de gestión de inventario seguro, escalable y accesible vía web consumiendo APIs RESTful[cite: 2].
* **Alcance:** Gestión de usuarios (JWT)[cite: 2], catálogo de productos, categorías, proveedores, control de stock y registro de órdenes de compra.

---

## 🛠️ 2. Arquitectura del Sistema (6 Microservicios)

| Microservicio | Responsabilidad | DBMS / Tabla | Endpoints CRUD |
| --- | --- | --- | --- |
| **MS-Auth** | Autenticación y gestión de usuarios[cite: 2] | PostgreSQL (`usuarios`) | POST /login, POST /register |
| **MS-Productos** | Catálogo general de productos[cite: 2] | PostgreSQL (`productos`) | CRUD completo (GET, POST, PUT, DELETE)[cite: 2] |
| **MS-Categorias** | Clasificación de productos | PostgreSQL (`categorias`) | CRUD completo[cite: 2] |
| **MS-Proveedores** | Registro e historial de proveedores | PostgreSQL (`proveedores`) | CRUD completo[cite: 2] |
| **MS-Inventario** | Control de existencias (Relación 1:N)[cite: 2] | PostgreSQL (`inventario`) | CRUD completo[cite: 2] |
| **MS-Ordenes** | Órdenes de compra (Relación N:M)[cite: 2] | PostgreSQL (`ordenes_compra`, `detalle_ordenes`) | CRUD completo[cite: 2] |

---

## 📱 3. Frontend (6 Pantallas Obligatorias)
1. **Login:** Autenticación con JWT[cite: 2].
2. **Dashboard:** Métricas generales de stock y alertas[cite: 2].
3. **Gestión de Productos:** Tabla + formulario de creación/edición[cite: 2].
4. **Gestión de Proveedores:** Tabla + formulario de creación/edición[cite: 2].
5. **Control de Inventario:** Tabla + ajuste de existencias[cite: 2].
6. **Reporte / Detalle:** Consulta detallada de órdenes e historial[cite: 2].

---

## 👥 4. Integrantes y Asignación de Roles

| Nombre del Integrante | Rol en el Proyecto |
| --- | --- |
| *Nombre Integrante 1* | Project Manager / Scrum Master |
| *Nombre Integrante 2* | Desarrollador Backend |
| *Nombre Integrante 3* | Desarrollador Frontend / UI/UX |
| *Nombre Integrante 4* | Tester / QA / Requisitos |

---

## 📅 5. Planificación de Sprints (Scrum)

### 🔹 Segundo Parcial (50 pts por Sprint)
* **Sprint 1 (50 pts):** Configuración de repositorios, montado de BDs (6 tablas en 3FN) y MS-Auth + MS-Productos[cite: 2].
* **Sprint 2 (50 pts):** Implementación de MS-Categorias, MS-Proveedores y primeras 3 pantallas en Frontend[cite: 2].

### 🔹 Tercer Parcial (25 pts por Sprint)
* **Sprint 1 (25 pts):** Implementación de MS-Inventario y comunicación síncrona/asíncrona entre servicios[cite: 2].
* **Sprint 2 (25 pts):** Implementación de MS-Ordenes (Relación N:M) y Swagger/Postman[cite: 2].
* **Sprint 3 (25 pts):** Integración total del Frontend (6 pantallas) consumiendo APIs[cite: 2].
* **Sprint 4 (25 pts):** Pruebas integrales, documentación final y despliegue/containerización[cite: 2].

---

## 📂 6. Documentación Detallada
* 📄 [01. Especificación de Requisitos SRS (15 RF / 15 RNF)](docs/01-srs.md)
* 📝 [02. Historias de Usuario (US001 - US006)](docs/02-historias-usuario.md)
* 🗄️ [03. Diseño de Base de Datos y Scripts SQL](docs/03-diseno-bd.md)
