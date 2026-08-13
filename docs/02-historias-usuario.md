# 📝 Historias de Usuario y Criterios de Aceptación

Este documento respalda el backlog del proyecto desarrollado bajo la metodología **Scrum**.

---

### HU01: Autenticación de Usuarios
* **ID:** US001
* **Descripción:** Como usuario del sistema, quiero iniciar sesión con correo y contraseña para obtener un token JWT seguro.
* **Criterios de Aceptación:**
  * Recibe email y password.
  * Valida credenciales contra la BD del MS-Auth.
  * Retorna token JWT válido o error HTTP 401[cite: 2].

---

### HU02: Gestión de Categorías
* **ID:** US002
* **Descripción:** Como administrador, quiero gestionar las categorías de productos para mantener el inventario clasificado.
* **Criterios de Aceptación:**
  * Endpoint CRUD completo (GET, POST, PUT, DELETE)[cite: 2].
  * Respuestas en JSON estandarizadas[cite: 2].

---

### HU03: Gestión de Productos (Relación 1:N)
* **ID:** US003
* **Descripción:** Como encargado de inventario, quiero registrar productos vinculándolos a una categoría existente[cite: 2].
* **Criterios de Aceptación:**
  * Valida que el código de barras sea único.
  * Mantiene integridad referencial con la tabla `categorias` (1:N)[cite: 2].

---

### HU04: Gestión de Proveedores
* **ID:** US004
* **Descripción:** Como analista de compras, quiero registrar a los proveedores para llevar el control del abastecimiento.
* **Criterios de Aceptación:**
  * Registra RTN, nombre de empresa y teléfono.
  * Valida que el RTN no se encuentre duplicado.

---

### HU05: Control de Inventario (Relación 1:N)
* **ID:** US005
* **Descripción:** Como personal de bodega, quiero actualizar y consultar el stock físico por producto[cite: 2].
* **Criterios de Aceptación:**
  * Mantiene relación 1:N con la tabla `productos`[cite: 2].
  * Refleja cambios de existencias en tiempo real.

---

### HU06: Órdenes de Compra (Relación N:M)
* **ID:** US006
* **Descripción:** Como comprador, quiero generar órdenes de compra vinculando múltiples productos a un proveedor[cite: 2].
* **Criterios de Aceptación:**
  * Implementa la tabla intermedia `detalle_ordenes` (relación N:M)[cite: 2].
  * Calcula montos totales automáticamente.
