<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//conexion a la base de datos
require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['rol_usuario'])) {
    header("location: ./login.php");
    exit();
}
$mysql = new MySQL();
$mysql->conectar();

$rol= $_SESSION['rol_usuario'];
$nombre=$_SESSION['correo_'.$rol];

//consulta para obtener los trabajos
$resultado=$mysql->efectuarConsulta("SELECT * FROM trabajos");
?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> EduSena </title>

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="AdminLTE v4 | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta
      name="description"
      content="AdminLTE is a Free Bootstrap 5 Admin Dashboard, 30 example pages using Vanilla JS. Fully accessible with WCAG 2.1 AA compliance."
    />
    <meta
      name="keywords"
      content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel, WCAG compliant"
    />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="../css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q="
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="../css/adminlte.css" />
    <!--end::Required Plugin(AdminLTE)-->
    <!-- Estilo propio -->
     <link rel="stylesheet" href="../css/style.css">

    <!-- apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0="
      crossorigin="anonymous"
    />

    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4="
      crossorigin="anonymous"
    />
    <!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- esto es para que funcione chars.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- DataTables + Bootstrap -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables núcleo -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

<!-- Integración Bootstrap 5 -->
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

<!-- Extensión Responsive (versión compatible 2.5.0) -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

<!-- Extensión Column Control (si de verdad la usas) -->
<link href="https://cdn.datatables.net/columncontrol/1.1.0/css/columnControl.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/columncontrol/1.1.0/js/dataTables.columnControl.min.js"></script>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>

    .btn-info {
  background: linear-gradient(135deg, #17a2b8, #5bc0de);
  border: none;
  transition: all 0.3s ease;
  color: white;
  font-weight: 500;
  letter-spacing: 0.3px;
}

.btn-info:hover {
  transform: translateY(-5px) scale(1.05);
  background: linear-gradient(135deg, #5bc0de, #17a2b8);
  box-shadow: 0 8px 15px rgba(0, 123, 255, 0.3);
}

.btn-info:active {
  transform: scale(0.98);
  box-shadow: 0 3px 6px rgba(0,0,0,0.2);
}

    .card {
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 8px 20px rgba(0,0,0,0.2);
  cursor: pointer;
}

.container-documentos {
  display: flex;
  justify-content: center;
  align-items: flex-start;
  flex-wrap: wrap;           
  gap: 30px;                 /* espacio entre columnas */
  margin: 40px auto;
  max-width: 1400px;
  padding: 20px;
}

.card-documento {
  flex: 1 1 30%;             
  min-width: 350px;          /* ancho mínimo para pantallas pequeñas */
  background-color: #ffffff;
  padding: 30px 35px;
  border-radius: 16px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}


.card-documento:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 18px rgba(0, 0, 0, 0.12);
}

.titulo-seccion {
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 25px;
  font-size: 1.2rem;
  display: flex;
  align-items: center;
  gap: 10px;
}

.form-documentos {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.row-form {
  display: flex;
  flex-direction: column; 
  gap: 16px;
  width: 100%;
}

.form-group {
  display: flex;
  flex-direction: column;
  flex: 1;
  min-width: 180px;
}

.form-group label {
  font-weight: 600;
  color: #334155;
  margin-bottom: 5px;
}

.form-group input[type="date"],
.form-group select {
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  padding: 8px 10px;
  background-color: #f8fafc;
  color: #0f172a;
  transition: all 0.3s ease;
}

.form-group input[type="date"]:focus,
.form-group select:focus {
  border-color: #2563eb;
  box-shadow: 0 0 6px rgba(37, 99, 235, 0.3);
  outline: none;
}

.btn-generar {
  background-color: #048db7dd;
  color: #ffffff;
  font-weight: 600;
  border: none;
  border-radius: 8px;
  padding: 12px 18px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  cursor: pointer;
  align-self: flex-start; /* alinea a la izquierda */
  transition: background-color 0.3s ease, transform 0.2s ease;
}

.btn-generar:hover {
  background-color: #ff0000ff;
  transform: translateY(-2px);
}

.btn-generar i {
  font-size: 16px;
}

.card-documento {
  min-height: 500px; 
}

.btn-group {
  display: flex;
  gap: 10px;
  align-items: center;
}
/* ... dentro de <style> ... */

/* Modificación a .btn-group para alinear los botones */
.btn-group {
    display: flex;
    gap: 15px; /* Aumenta el espacio entre botones */
    align-items: center;
    /* Nuevo: Añade esto para que los botones crezcan y se repartan el espacio */
    width: 100%; 
}

/* Ajustes al botón de Excel para que se vea igual que el de PDF */
.btn-excel {
    background-color: #00a390ff;
    color: #fff;
    font-weight: 600; /* Asegura el mismo peso de fuente */
    border: none;
    border-radius: 8px; /* Usa el mismo radio que .btn-generar */
    padding: 12px 18px; /* Usa el mismo padding que .btn-generar */
    text-decoration: none;
    display: inline-flex; /* Para alinear icono y texto */
    align-items: center;
    justify-content: center;
    gap: 8px;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.2s ease;
    
    flex-grow: 1; 
}

.btn-excel:hover {
    background-color: #13882cff;
    transform: translateY(-2px);
    color: #fff;
}

/* Asegura que el botón de PDF también crezca equitativamente en un grupo */
.btn-group .btn-generar {
    flex-grow: 1; 
    margin-top: 0; /* Anula cualquier margen que pueda tener */
    align-self: unset; /* Anula align-self: flex-start; del estilo anterior */
}

/* ... otras clases CSS ... */
</style>

<!-- script de los graficos -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  </head>
  <!--end::Head-->
  <!--begin::Body-->
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
        <!--begin::Container-->
        <div class="container-fluid">
          <!--begin::Start Navbar Links-->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" data-lte-toggle="sidebar" href="index.php" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="index.php" class="nav-link">Inicio</a>
            </li>
            
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">

            <!--begin::User Menu Dropdown-->
            <li class="nav-item dropdown user-menu">
  <a href="#" class="nav-link dropdown-toggle text-white fw-semibold" data-bs-toggle="dropdown">
    <span class="d-none d-md-inline"><i class="bi bi-person-circle me-1"></i><?php echo $nombre; ?></span>
  </a>

              <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width: 230px;">
                <!-- Cabecera del usuario -->
                <li class="bg-primary text-white text-center rounded-top py-3">
                  <p class="mb-0 fw-bold fs-5"><?php echo  $nombre; ?></p>
                  <small><?php echo $rol; ?></small>
                </li>

                <!-- Separador -->
                <li><hr class="dropdown-divider m-0"></li>

                <!-- Opciones del menu -->

                <!-- Separador -->
                <li><hr class="dropdown-divider m-0"></li>

                <!-- Opción de cerrar sesión -->
                <li>
                  <a href="../controllers/logout.php" class="dropdown-item d-flex align-items-center text-danger py-2">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                  </a>
                </li>
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
        <!--end::Container-->
      </nav>
      <!--end::Header-->
      <!--begin::Sidebar-->
      <aside class="app-sidebar verde shadow">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="./index.php" class="brand-link">
            <!--begin::Brand Image-->
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="title"> senaEdu </span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
<!-- REEMPLAZA EL SIDEBAR COMPLETO EN gestionTrabajos.php (línea ~180-220) -->

<aside class="app-sidebar verde shadow">

  <div class="sidebar-wrapper">
    <nav class="mt-2">
      <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation">
        <?php if ($rol == 'aprendiz'): ?>
          <li class="nav-item">
            <a href="./gestionTrabajos.php" class="nav-link active">
              <i class="bi bi-calendar-check me-2"></i>
              <span>Trabajos</span>
            </a>
          </li>
          <li class="nav-item">
            <a href="./misCalificaciones.php" class="nav-link">
              <i class="bi bi-star me-2"></i>
              <span>Mis Calificaciones</span>
            </a>
          </li>
        <?php endif; ?>
      </ul>
    </nav>
  </div>
</aside>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <!--begin::Container-->
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <div class="container-fluid">
            <div class="row">
              <div class="table-responsive">
                  <div class="col"> 
                        <button class="btn btn-sm btn-primary btnReservar mb-4 w-100" onclick="subirTrabajo()">
                            <i class="bi bi-upload"></i> Subir Trabajo
                        </button>
                  </div>
                  
                  <table id="tablaTrabajos" class="table table-striped table-bordered" width="100%">
                      <thead class="table-success">
                          <tr>
                              <th>ID</th>
                              <th>Nombre del Trabajo</th>
                              <th>Fecha Limite del Trabajo</th>
                              <th>Acciones</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php while($fila = $resultado->fetch_assoc()): ?>
                              <tr>
                                  <td><?= $fila['id_trabajo'] ?></td>
                                  <td><?= $fila['nombre_trabajo'] ?></td>
                                  <td><?= $fila['fecha_trabajo'] ?></td>
                                  <td class="justify-content-center d-flex gap-1">
                                  <!-- se agrega ../para que sirva la ruta y pueda visualizar el archivo -->
                                  <a class="btn btn-info btn-sm" title="Ver trabajo" href="<?php echo '../'.$fila['ruta_trabajo']; ?>" 
   target="_blank">
    <i class="bi bi-eye"></i>
</a> |
                                  <a class="btn btn-warning btn-sm"  title="editar" onclick="editarTrabajo(<?php echo $fila['id_trabajo']; ?>)">
          <i class="bi bi-pencil-square"></i>
          </a>
          | 
          <a class="btn btn-danger btn-sm"  
          href="javascript:void(0);" 
          onclick="eliminarTrabajo(<?php echo $fila['id_trabajo']; ?>)" 
          title="Eliminar"> 
              <i class="bi bi-trash"></i>
          </a>

                            </td>
                              </tr>
                          <?php endwhile; ?>
                      </tbody>
                  </table>
              </div>
            </div>
          </div>
        </div>
       <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2014-2025&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">senaEdu</a>.
        </strong>
        All rights reserved.
        <!--end::Copyright-->
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="../public/js/adminlte.js"></script>
    <!--end::Required Plugin(AdminLTE)--><!--begin::OverlayScrollbars Configure-->
    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

        // Disable OverlayScrollbars on mobile devices to prevent touch interference
        const isMobile = window.innerWidth <= 992;

        if (
          sidebarWrapper &&
          OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
          !isMobile
        ) {
          OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
            scrollbars: {
              theme: Default.scrollbarTheme,
              autoHide: Default.scrollbarAutoHide,
              clickScroll: Default.scrollbarClickScroll,
            },
          });
        }
      });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <!-- jsvectormap -->
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
      integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y="
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
      integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY="
      crossorigin="anonymous"
    ></script>
    <script>
$(document).ready(function() {
   $('#tablaTrabajos').DataTable({
    language: {
        url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
    },
    pageLength: 5,
    lengthMenu: [5, 10, 20, 50],
    responsive: true,
    autoWidth: true
});

});
</script>
<script> 
function editarTrabajo(id) {
  Swal.fire({
    title: 'Editar Trabajo',
    html: `
      <form id="formEditarTrabajo" class="text-start" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Nombre del trabajo</label>
          <input type="text" class="form-control" id="nombre_trabajo" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Subir Nueva Evidencia (opcional)</label>
          <input type="file" accept=".pdf, .docx" class="form-control" id="ruta_trabajo">
        </div>
      </form>
    `,
    confirmButtonText: 'Guardar Cambios',
    showCancelButton: true,
    cancelButtonText: 'Cancelar',
    preConfirm: () => {
      const nombre = document.getElementById('nombre_trabajo').value.trim();
      const archivo = document.getElementById('ruta_trabajo').files[0];

      if (!nombre) {
        Swal.showValidationMessage('El nombre del trabajo es obligatorio.');
        return false;
      }

      const formData = new FormData();
      formData.append('id_trabajo', id);
      formData.append('nombre_trabajo', nombre);
      if (archivo) {
        formData.append('ruta_trabajo', archivo);
      }

      return formData;
    }
  }).then(result => {
    if (result.isConfirmed) {
      $.ajax({
        url: '../controllers/editarTrabajo.php',
        type: 'POST',
        data: result.value,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
          Swal.fire('Éxito', response.message, 'success').then(() => location.reload());
        },
        error: function() {
          Swal.fire('Error', 'El servidor no respondió', 'error');
        }
      });
    }
  });
}
</script>

<script>
function eliminarTrabajo(id) {
  Swal.fire({
    title: 'Eliminar Trabajo',
    text: '¿Está seguro de que desea eliminar el trabajo con ID: ' + id + '?',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Sí, eliminar"
  }).then((result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Eliminado!",
        text: "El trabajo ha sido eliminado exitosamente.",
        icon: "success",
        timer: 2000,      // el tiempo que se demora en cerrar el alert 
        showConfirmButton: false
      }).then(() => {
        window.location.href = '../controllers/eliminarTrabajo.php?id_trabajo=' + id;
      });
    }
  });
}
</script>

<script>
function subirTrabajo() {
  Swal.fire({
    title: 'Subir Evidencia',
    html: `
      <form id="formAgregarTrabajo" class="text-start" enctype="multipart/form-data">
        <div class="mb-3">
          <label class="form-label">Nombre del trabajo</label>
          <input type="text" class="form-control" id="nombre_trabajo" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Subir Evidencia</label>
          <input type="file" accept=".pdf, .docx" class="form-control" id="ruta_trabajo" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Curso</label>
          <select class="form-select" id="curso" required>
            <option value="" disabled selected>Seleccione un curso</option>
            <?php 
              $consultaCursos = $mysql->efectuarConsulta("SELECT id_curso, nombre_curso FROM cursos");
              while($fila=$consultaCursos->fetch_assoc()):
            ?>
              <option value="<?= $fila['id_curso'] ?>">
                <?= $fila['nombre_curso'] ?>
              </option>
            <?php endwhile; ?>
          </select>
        </div>
      </form>
    `,
    confirmButtonText: 'Agregar',
    showCancelButton: true,
    cancelButtonText: 'Cancelar',
    preConfirm: () => {
      const nombre = document.getElementById('nombre_trabajo').value.trim();
      const archivo = document.getElementById('ruta_trabajo').files[0];
      const curso = document.getElementById('curso').value;

      if (!nombre || !archivo || !curso) {
        Swal.showValidationMessage('Complete todos los campos.');
        return false;
      }

      const formData = new FormData();
      formData.append('nombre_trabajo', nombre);
      formData.append('ruta_trabajo', archivo);
      formData.append('cursos_id_curso', curso); 

      return formData;
    }
  }).then(result => {
    if (result.isConfirmed) {
      $.ajax({
        url: '../controllers/agregarTrabajo.php',
        type: 'POST',
        data: result.value,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
          Swal.fire('Éxito', response.message, 'success').then(() => location.reload());
        },
        error: function() {
          Swal.fire('Error', 'El servidor no respondió', 'error');
        }
      });
    }
  });
}
</script>

  </body>
  <!--end::Body-->
</html>
