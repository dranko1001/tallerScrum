# Taller Scrum (SENA)

Resumen
-------
Taller Scrum es una aplicación web desarrollada para gestionar usuarios, cursos, fichas y trabajos en un entorno educativo (SENA). La app utiliza AdminLTE para la interfaz y PHP + MySQL en el backend. Permite a distintos roles (admin, instructor, aprendiz) autenticarse y acceder a funciones según su rol.

Stack
-----
- PHP 7/8
- MySQL / MariaDB
- Bootstrap 5 + AdminLTE
- jQuery + DataTables + SweetAlert2
- XAMPP (o servidor LAMP/WAMP) como entorno de desarrollo

Características principales
--------------------------
- Autenticación por rol: admin, instructor, aprendiz
- Gestión de usuarios (alta, edición, sesiones)
- Gestión de cursos e instructores
- Gestión de fichas (cohortes) y su relación con aprendices y cursos
- CRUD de trabajos y visualización de detalles
- Integración con DataTables para tablas dinámicas
- APIs internas (controllers) para operaciones AJAX

Estructura del proyecto
----------------------
La estructura principal del repositorio:

```
index.php (raíz) -> redirige a ./tallerScrum/index.php
tallerScrum/
  controllers/  (API endpoints y acciones del servidor)
  models/       (clase MySQL + modelos de ayuda)
  views/        (vistas y formularios)
  public/js     (scripts públicos, adminlte.js)
  css/          (estilos: adminlte.css, style.css)
  database/     (dump SQL & definición del esquema)
```

Instalación / Setup
-------------------
1. Coloca el proyecto en la carpeta pública de tu servidor (ej. `C:\xampp\htdocs\tallerScrum`).
2. Asegúrate de tener las extensiones de PHP necesarias instaladas (mysqli, json, mbstring).
3. Importa la base de datos y los datos de ejemplo desde `tallerScrum/database/taller_scrum.sql` (usa phpMyAdmin o consola MySQL):
   - Base de datos por defecto: `taller_scrum`
   - Credenciales por defecto (usadas en `models/MySQL.php`): host `localhost`, user `root`, sin contraseña.
4. Abre el navegador en: `http://localhost/tallerScrum/` o en la URL configurada para Apache.

Notas de configuración y troubleshooting
-------------------------------------
- Si el `index.php` raíz redirige: la app principal se encuentra en `tallerScrum/index.php`.
- Si ves errores del tipo "Table 'dbname.table' doesn't exist", importa `taller_scrum.sql` y revisa los nombres de tabla.
- Si recibes warnings sobre `$_SESSION` indefinidas, asegúrate de iniciar sesión (login) y que `session_start()` se llame antes de usar `$_SESSION`.
- Si el servidor local no reconoce `php` en PowerShell, usa la consola de XAMPP o agrega la ruta de PHP al PATH para ejecutarlo desde la terminal.

Notas técnicas y convenciones
----------------------------
- Se corrigieron varias rutas `require_once` en el proyecto para usar `__DIR__` y evitar problemas con includes.
- La clase `MySQL` se encuentra en `tallerScrum/models/MySQL.php` y expone métodos para conectar, ejecutar consultas y escapar valores.
- Algunos endpoints AJAX esperan `Content-Type: application/json` en las respuestas y devuelven objetos JSON con `success` y `message`.

Ejecución local y comprobaciones
--------------------------------
1. Inicia XAMPP y activa Apache + MySQL.
2. Importa la base de datos.
3. Abre: `http://localhost/tallerScrum/`.
4. Inicia sesión: si el sistema no tiene cuentas predeterminadas, crea un usuario en la tabla correspondiente (p. ej. `administrador`) o registra un usuario manualmente.

Contribuciones
--------------
Si quieres contribuir:
- Haz un fork, crea una rama nueva y envía PRs con cambios claros.
- Escribe tests y verifica que las rutas relativas y requerimientos de PHP funcionan antes de crear PR.

Contacto
-------
Para dudas o errores puedes abrir una nueva issue con los pasos para reproducir el problema y los mensajes de error.

Licencia
--------
Coloca aquí la licencia si tienes alguna (por ejemplo MIT). Si no, deja sin licencia o agrega la correspondiente.
