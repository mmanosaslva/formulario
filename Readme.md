# Formulario PHP con Bootstrap (XAMPP + MySQL)

Este proyecto se desarrollará paso a paso, con buenas prácticas y explicaciones claras.
En esta primera etapa definimos **los campos del formulario y sus reglas de validación**.

---

## ✅ Paso 1: Campos del formulario y reglas

Usaremos un formulario básico de registro con los siguientes campos:

| Campo | Tipo HTML | Reglas principales | Motivo |
|------|-----------|--------------------|--------|
| nombre | text | requerido, solo letras, 2-50 caracteres | Identifica al usuario |
| apellido | text | requerido, solo letras, 2-50 caracteres | Complemento del nombre |
| documento | text | requerido, solo números, 7-12 caracteres, **único** | Identificador único |
| email | email | requerido, formato válido, **único** | Contacto |
| telefono | text | requerido, solo números, 7-15 caracteres | Contacto |
| edad | number | requerido, rango 18-99 | Validación lógica |

### Reglas a aplicar (resumen)

**Frontend (HTML5 + JS):**
- `required` en todos los campos.
- `minlength` y `maxlength` donde corresponda.
- Validación por patrón (solo letras / solo números).
- Mensajes de error con Bootstrap.

**Backend (PHP):**
- Sanitizar con `trim()` y `htmlspecialchars()`.
- Validar longitud y tipo.
- Evitar campos vacíos.
- Evitar caracteres especiales en nombres/apellidos.
- Preparar consultas con *prepared statements*.

**Base de datos (MySQL):**
- Campos `NOT NULL`.
- `UNIQUE` en `email` y `documento`.
- Tipos correctos (`VARCHAR`, `INT`).
- Longitudes coherentes con validaciones.

---

📌 **Siguiente paso:** crear la base de datos y la tabla con SQL, explicando cada campo.
