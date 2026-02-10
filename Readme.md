
## Requisitos para ejecutar el proyecto

1. Tener instalado **XAMPP** (o WAMP, Laragon, MAMP, etc.)
2. Iniciar los módulos **Apache** y **MySQL**
3. Colocar la carpeta del proyecto en:  
   `C:\xampp\htdocs\proyecto_crud\` 

## Instalación paso a paso

1. **Crear la base de datos**
   - Abre **phpMyAdmin**[](http://localhost/phpmyadmin)
   - Crea una nueva base de datos llamada `crud_php`
   - Selecciona la base de datos y ve a la pestaña **SQL**
   - Copia y pega el contenido de `database.sql` → **Ejecutar**


2. **Acceder al sistema**
   - Abre tu navegador y ve a:  
     http://localhost/proyecto_crud/index.php

## Características implementadas

- Las 4 operaciones **CRUD** completas
- Uso obligatorio de **sentencias preparadas** (prepare + execute) en todas las consultas
- Validación estricta de datos:
  - Nombre: solo letras (incluye ñ y acentos), espacios, guiones y apóstrofes
  - Email: formato válido + **solo caracteres ASCII** (sin acentos, ñ, emojis, etc.) + único en la base de datos
  - Teléfono: opcional, solo números + formatos comunes (+, -, espacios, paréntesis)
- Mensajes de éxito y error con **alertas Bootstrap**
- Confirmación JavaScript antes de eliminar
- Interfaz responsive y consistente en todas las páginas (Bootstrap 5.3)
- Protección contra inyección SQL
- Manejo de errores con try-catch

## Resumen técnico – Flujo de datos

1. El usuario interactúa con formularios HTML o la tabla de listados.
2. Las solicitudes (GET/POST) llegan a los archivos PHP correspondientes.
3. Cada archivo incluye `conexion.php`, que establece una conexión **PDO** segura con manejo de excepciones.
4. Todas las operaciones a la base de datos (INSERT, SELECT, UPDATE, DELETE) utilizan **sentencias preparadas** para evitar inyección SQL.
5. Se realizan validaciones tanto en el frontend (atributos HTML5 `pattern`, `required`) como en el backend (expresiones regulares, verificación de duplicados, filtro ASCII para email).
6. Los mensajes de éxito o error se almacenan en sesión y se muestran mediante alertas Bootstrap.
7. Después de cada operación (crear, editar, eliminar), se redirige al usuario a `index.php`.

## Capturas de pantalla (para el entregable)

Se incluyen en el documento de evidencias las siguientes imágenes:

- Pantalla principal con lista de perfiles
- Formulario de creación (estado inicial y con errores de validación)
- Formulario de edición
- Mensaje de éxito al crear/editar/eliminar
- Mensaje de error (ej. email duplicado o formato inválido)

## Criterios de evaluación cumplidos

| Criterio              | Cumplido                                                                 |
|-----------------------|--------------------------------------------------------------------------|
| Seguridad             | Uso obligatorio de sentencias preparadas en todas las operaciones       |
| Funcionalidad         | Las 4 operaciones CRUD funcionan correctamente                          |
| Código                | Limpio, indentado, con comentarios breves en la lógica principal        |
| Experiencia de usuario| Interfaz funcional, responsive, mensajes claros de retroalimentación    |

