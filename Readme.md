# Proyecto CRUD en PHP + MySQL

## Descripción
Este proyecto es un sistema CRUD básico para gestionar perfiles de usuarios, desarrollado con PHP y MySQL. Cumple con las operaciones de Crear, Leer, Actualizar y Eliminar (CRUD), utilizando PDO para conexiones seguras y sentencias preparadas para prevenir inyecciones SQL.

## Instrucciones de Instalación
1. **Entorno**: Instala XAMPP (o equivalente) y asegúrate de que Apache y MySQL estén corriendo.
2. **Base de Datos**:
   - Abre phpMyAdmin[](http://localhost/phpmyadmin).
   - Importa el archivo `database.sql` para crear la BD `crud_php` y la tabla `perfiles`.
3. **Archivos**: Coloca la carpeta `proyecto_crud` en `C:/xampp/htdocs/` (o tu directorio htdocs).
4. **Acceso**: Abre el navegador y ve a `http://localhost/proyecto_crud/index.php`.
5. **Configuración**: Si es necesario, ajusta las credenciales en `conexion.php` (por defecto: user 'root', password '').

## Flujo de Datos (Resumen Técnico)
- **Conexión**: `conexion.php` establece una conexión PDO con la BD, maneja errores con try-catch y configura UTF-8.
- **Lectura (READ)**: `index.php` consulta todos los registros con `SELECT * FROM perfiles` y los muestra en una tabla HTML con botones para editar/eliminar.
- **Creación (CREATE)**: `crear.php` recibe datos via POST, valida/sanitiza inputs (e.g., filter_var para email), inserta con prepared statements y redirige a index con mensaje en sesión.
- **Actualización (UPDATE)**: `editar.php` recibe ID via GET, consulta el registro, prellena el formulario, actualiza con prepared statements al POST y redirige.
- **Eliminación (DELETE)**: `eliminar.php` recibe ID via GET, ejecuta DELETE con prepared statement y redirige con mensaje.
- **Seguridad**: Todos los inputs se sanitizan/validan; se usan bindParam para queries; confirmación JS antes de eliminar.
- **Mensajes**: Usan sesiones para retroalimentación temporal (éxito/error).

Este flujo simula una arquitectura web básica: frontend (HTML formas/tablas) interactúa con backend (PHP) que persiste datos en MySQL.

Tiempo de desarrollo estimado: 6-10 horas (como en la actividad).