# 📄 Especificación de Requisitos de Software (SRS)

## 📌 Requisitos Funcionales (RF - Mínimo 15)

1. **RF01:** El sistema debe permitir el registro de usuarios con rol de operador o administrador.
2. **RF02:** El sistema debe validar las credenciales de inicio de sesión y retornar un token JWT.
3. **RF03:** El sistema debe permitir crear, consultar, actualizar y eliminar categorías de productos[cite: 2].
4. **RF04:** El sistema debe registrar productos vinculados a una categoría específica (relación 1:N)[cite: 2].
5. **RF05:** El sistema debe validar que el código de barras de un producto sea único en el catálogo.
6. **RF06:** El sistema debe registrar la información legal y de contacto de los proveedores (RTN, nombre, teléfono)[cite: 2].
7. **RF07:** El sistema debe validar que el RTN del proveedor no se encuentre duplicado.
8. **RF08:** El sistema debe permitir registrar y actualizar las existencias de stock por producto (relación 1:N)[cite: 2].
9. **RF09:** El sistema debe registrar la ubicación física dentro de la bodega para cada lote de inventario.
10. **RF10:** El sistema debe registrar órdenes de compra dirigidas a un proveedor seleccionado[cite: 2].
11. **RF11:** El sistema debe asociar múltiples productos a una misma orden de compra mediante una tabla intermedia (relación N:M)[cite: 2].
12. **RF12:** El sistema debe calcular automáticamente el total financiero de una orden de compra según las cantidades y precios unitarios.
13. **RF13:** El sistema debe mostrar un Dashboard principal con alertas de productos con bajo stock[cite: 2].
14. **RF14:** El sistema debe permitir la consulta detallada e historial de las órdenes de compra emitidas[cite: 2].
15. **RF15:** El sistema debe permitir el cierre de sesión destruyendo la persistencia del token en el cliente[cite: 2].

---

## ⚙️ Requisitos No Funcionales (RNF - Mínimo 15)

1. **RNF01:** La arquitectura debe estar desacoplada en 6 microservicios independientes[cite: 2].
2. **RNF02:** Cada microservicio debe contar con su propio almacenamiento de datos bajo el patrón *Database per Service*[cite: 2].
3. **RNF03:** La comunicación entre microservicios debe realizarse mediante peticiones HTTP/RESTful en formato JSON[cite: 2].
4. **RNF04:** Todos los endpoints sensibles deben estar protegidos mediante autenticación basada en tokens JWT[cite: 2].
5. **RNF05:** Las respuestas de error de la API deben ser estandarizadas (códigos 400, 401, 404, 500)[cite: 2].
6. **RNF06:** La base de datos debe estar normalizada hasta la Tercera Forma Normal (3FN)[cite: 2].
7. **RNF07:** El tiempo de respuesta de las consultas GET de lectura debe ser menor a 2 segundos[cite: 1].
8. **RNF08:** La interfaz web debe ser responsiva y adaptable a dispositivos de escritorio y tabletas[cite: 1, 2].
9. **RNF09:** Las contraseñas de los usuarios deben encriptarse mediante algoritmos seguros (bcrypt o similar).
10. **RNF10:** Cada microservicio debe incluir su propia especificación de endpoints en Swagger/OpenAPI o Postman[cite: 2].
11. **RNF11:** La configuración de las credenciales debe manejarse mediante variables de entorno en archivos `.env`[cite: 2].
12. **RNF12:** El sistema debe permitir el control de acceso basado en roles para delimitar permisos[cite: 1].
13. **RNF13:** El código debe estar respaldado en un repositorio de Git con historial de contribuciones de todo el equipo[cite: 2].
14. **RNF14:** El frontend no debe utilizar datos estáticos ("hardcodeados"); todo debe consumirse desde los microservicios[cite: 2].
15. **RNF15:** El diseño de la interfaz debe ofrecer mensajes visibles de validación y de error al usuario final[cite: 2].
