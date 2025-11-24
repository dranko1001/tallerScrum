<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
//conexion a la base de datos
require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['rol_usuario'])) {
    header("location: ../views/login.php");
    exit();
}
$mysql = new MySQL();
$mysql->conectar();


  $rol= $_SESSION['rol_usuario'];
$nombre=$_SESSION['correo_'.$rol];

//consulta para obtener los libros
$resultado = $mysql->efectuarConsulta(" SELECT 
        c.id_curso,
        c.nombre_curso
    FROM cursos c
    ");
?>

<!doctype html>
<html lang="en">
  <!--begin::Head-->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> Taller Scrum </title>

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

<!-- DataTables + Bootstrap -->

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

<!-- estilo para la tabla cuando muestre los detalles del curso., lo dejo comentado por si alguien quiere ver como quedaria con css, personalmente lo prefiero mas simple -->
<!-- <style>
.detalles-curso-modal .list-group-item {
    border-left: 3px solid #012040ff;
    transition: all 0.3s ease;
}

.detalles-curso-modal .list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.detalles-curso-modal h5 {
    color: #495057;
    font-weight: 600;
}

.detalles-curso-modal .bi {
    font-size: 1.2rem;
}
</style> -->
<!-- fin del css de la tabla -->


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
              <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button">
                <i class="bi bi-list"></i>
              </a>
            </li>
            <li class="nav-item d-none d-md-block">
              <a href="../index.php" class="nav-link">Inicio</a>
            </li>
            
          </ul>
          <!--end::Start Navbar Links-->

          <!--begin::End Navbar Links-->
          <ul class="navbar-nav ms-auto">
            <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
              <a href="#" class="nav-link dropdown-toggle text-white fw-semibold" data-bs-toggle="dropdown">
                <span class="d-none d-md-inline"><?php echo $nombre; ?></span>
              </a>
              <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width: 230px;">
                <!-- Cabecera del usuario -->
                <li class="bg-primary text-white text-center rounded-top py-3">
                  <p class="mb-0 fw-bold fs-5"><?php echo $nombre; ?></p>
                  <small><?php echo $rol; ?></small>
                </li>
                <!-- Separador -->
                <li><hr class="dropdown-divider m-0"></li>
                <!-- Opciones del menu -->
                <li>
                  <a href="./perfilUsuario.php" class="dropdown-item d-flex align-items-center py-2">
                    <i class="bi bi-person me-2 text-secondary"></i> Perfil
                  </a>
                </li>
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
          <a href="../index.php" class="brand-link">
            <!--begin::Brand Image-->
           
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="title">Taller Scrum</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item">
                <a href="../index.php" class="nav-link">
                  <i class="bi bi-speedometer me-2"></i>
                  <span>
                    Inicio     
                  </span>
                  </a>
              </li>
            </ul>
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
          <div class="container-fluid">
            <!--begin::Row-->
 <div class="position-relative">
  <h3 class="text-center">
    <i class="bi bi-mortarboard-fill"></i> Cursos
  </h3>
  <ol class="breadcrumb position-absolute end-0 top-50 translate-middle-y">
    <li class="breadcrumb-item"><a href="./agregarCurso.php">Cursos</a></li>
    <li class="breadcrumb-item active" aria-current="page">Lista de cursos</li>
  </ol>
</div>
            <!--end::Row-->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content Header-->
        <!--begin::App Content-->
        <div class="app-content">
          <!--begin::Container-->
          <div class="container-fluid">
            <!--begin::Row-->
            <div class="row mb-3 align-items-center">
                <div class="col-md-6 d-flex gap-2">
                <?php if ($rol == 'Administrador'): ?>
                     
                <?php endif; ?>
                </div>
            </div>
            <div class="row">
              <button type="button" class="btn btn-success" onclick="agregarCurso()">➕ Curso</button>
              <!--begin::Col-->
                <div class="table-responsive">
<table id="tablaCursos" class="table table-striped table-bordered">
    <thead class="table-info">
        <tr>
            <th>ID</th>
            <th>Curso</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>

    
        
        <?php while  ($c = $resultado->fetch_assoc()): ?>
            
        <tr>
            <td><?= $c['id_curso'] ?></td>
            <td><?= $c['nombre_curso'] ?></td>
            <td>
                <button class="btn btn-info btn-sm" onclick="verDetallesCurso(<?= $c['id_curso'] ?>)">
    <i class="bi bi-eye"></i> 
</button>
                
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>






                </div>
                
              <!-- /.Start col -->
            </div>
            <!-- /.row (main row) -->
          </div>
          <!--end::Container-->
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->
      <!--begin::Footer-->
      <footer class="app-footer">
        
        <!--begin::Copyright-->
        <strong>
          Copyright &copy; 2014-2025&nbsp;
          <a href="https://adminlte.io" class="text-decoration-none">Taller Scrum</a>.
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
$('#tablaCursos').DataTable({
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

<!-- inicio de la funcion que agrega los cursos  -->
<script>
// Script para agregar cursos aprendices
function agregarCurso() {
  // Resetea el arreglo de instructores al abrir el modal
  instructoresSeleccionados = [];
  
  Swal.fire({
    title: 'Agregar Nuevo Curso',
    html: `
      <form id="formAgregarCurso" class="text-start">
        <div class="mb-3">
          <label for="nombre_curso" class="form-label">Nombre del Curso *</label>
          <input type="text" class="form-control" id="nombre_curso" name="nombre_curso" required>
        </div>
        
        <!-- Instructores -->
        <div class="mb-3">
          <label for="instructor" class="form-label">Instructores *</label>
          <input type="text" id="busquedaInstructor" class="form-control" placeholder="Buscar Instructor..." onkeyup="buscarInstructor(this.value)">
          <input type="hidden" id="instructores_curso" name="instructores_curso">
          <div id="sugerenciasInstructor" style="text-align:left; max-height:200px; overflow-y:auto; margin-top: 5px;"></div>
          <div id="instructoresSeleccionados" style="margin-top: 10px;"></div>
        </div>
      </form>
    `,
    width: '600px',
    confirmButtonText: 'Agregar',
    showCancelButton: true,
    cancelButtonText: 'Cancelar',
    focusConfirm: false,
    didOpen: () => {
      document.getElementById('instructoresSeleccionados').innerHTML = '';
      document.getElementById('instructores_curso').value = '';
    },
    preConfirm: () => {
      const nombre = document.getElementById('nombre_curso').value.trim();
      const instructores = document.getElementById('instructores_curso').value.trim();

      if (!nombre) {
        Swal.showValidationMessage('Por favor, ingrese el nombre del curso.');
        return false;
      }

      if (!instructores || instructores === '[]') {
        Swal.showValidationMessage('Por favor, seleccione al menos un instructor.');
        return false;
      }

      const formData = new FormData();
      formData.append('nombre_curso', nombre);
      formData.append('instructores_curso', instructores);
      
      return formData;
    }
  }).then((result) => {
    if (result.isConfirmed) {
      const formData = result.value;

      $.ajax({
        url: '../controllers/agregarCurso.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        dataType: 'json',
        success: function(response) {
          if (response.success) {
            Swal.fire('Éxito', response.message, 'success').then(() => {
              location.reload();
            });
          } else {
            Swal.fire('Atención', response.message, 'warning');
          }
        },
        error: function(xhr, status, error) {
          console.error("Error AJAX:", error);
          console.error("Respuesta del servidor:", xhr.responseText);
          Swal.fire('Error', 'El servidor no respondió correctamente.', 'error');
        }
      });
    }
  });
}

// Función que busca y agrega a los instructores por su correo
function buscarInstructor(texto) {
    if (texto.length < 2) {
        document.getElementById('sugerenciasInstructor').innerHTML = '';
        return;
    }

    $.ajax({
        url: '../controllers/buscarInstructor.php', 
        type: 'POST',
        dataType: 'json', 
        data: { query: texto },
        success: function (instructores) {
            let html = '<ul class="list-group">';
            
            if (instructores.length > 0) {
                instructores.forEach(instructor => {
                    html += `
                        <li class="list-group-item list-group-item-action" 
                            style="cursor: pointer;" 
                            onclick="seleccionarInstructor(${instructor.id_instructor}, '${instructor.correo_instructor.replace(/'/g, "\\'")}')">
                            ${instructor.correo_instructor}
                        </li>
                    `;
                });
                html += '</ul>';
            } else {
                html += `
                    <div class="alert alert-info mb-0">
                        <small>No se encontró el instructor "${texto}"</small>
                    </div>
                `;
            }

            document.getElementById('sugerenciasInstructor').innerHTML = html;
        },
        error: function (xhr, status, error) {
            console.error("❌ Error en la búsqueda:", error);
            document.getElementById('sugerenciasInstructor').innerHTML = '<div class="text-danger ps-2">Error al buscar instructores.</div>';
        }
    });
}

let instructoresSeleccionados = [];

function seleccionarInstructor(id, correo) {    
    id = parseInt(id);
    
    if (instructoresSeleccionados.includes(id)) {
        document.getElementById('sugerenciasInstructor').innerHTML = '';
        document.getElementById('busquedaInstructor').value = '';
        return;
    }

    instructoresSeleccionados.push(id);
    document.getElementById('instructores_curso').value = JSON.stringify(instructoresSeleccionados);

    const contenedor = document.getElementById('instructoresSeleccionados');
    const chip = document.createElement('span');
    
    chip.style.cssText = `
        display: inline-flex;
        align-items: center;
        background-color: #e3f2fd;
        color: #1565c0;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 14px;
        border: 1px solid #90caf9;
        margin-right: 5px;
        margin-bottom: 5px;
    `;
    chip.innerHTML = `
        ${correo}
        <span 
            onclick="eliminarInstructor(${id}, this)" 
            style="
                margin-left: 8px;
                font-size: 16px;
                cursor: pointer;
                font-weight: bold;
            "
        >&times;</span>
    `;

    contenedor.appendChild(chip);

    document.getElementById('sugerenciasInstructor').innerHTML = '';
    document.getElementById('busquedaInstructor').value = '';
}

function eliminarInstructor(id, elemento) {
    const index = instructoresSeleccionados.indexOf(id);
    if (index > -1) {
        instructoresSeleccionados.splice(index, 1);
    }
    
    document.getElementById('instructores_curso').value = JSON.stringify(instructoresSeleccionados);
    elemento.parentElement.remove();
}
//fin de la funcion que agrega instructores
//FUNCION AGREGAR APRENDICES DESACTUALIZADA, ELIMINARLA DE SER NECESARIO PERO AHORA NO SE NECESITA
// inicio de la funcion que agrega los aprendices, toma los  datos de la db  por correo, el modal fue eliminado y ahora los aprendices se agregan en fichas
function buscarAprendiz(texto) {
    if (texto.length < 2) {
        document.getElementById('sugerenciasAprendiz').innerHTML = '';
        return;
    }

    $.ajax({
        url: '../controllers/buscarAprendiz.php', 
        type: 'POST',
        dataType: 'json', 
        data: { query: texto },
        success: function (aprendices) {
            let html = '<ul class="list-group">';
            
            if (aprendices.length > 0) {
                aprendices.forEach(aprendiz => {
                    // Verifica si ya tiene curso
                    if(aprendiz.tiene_curso == 1){
                        html += `
                            <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center" 
                                style="cursor: not-allowed; opacity: 0.6;">
                                <div>
                                    <span>${aprendiz.correo_aprendiz}</span>
                                    <br>
                                    <small class="text-danger">
                                        <i class="bi bi-exclamation-triangle"></i> 
                                        Ya está en: ${aprendiz.curso_actual}
                                    </small>
                                </div>
                                <span class="badge bg-warning text-dark">No disponible</span>
                            </li>
                        `;
                    } else {
                        html += `
                            <li class="list-group-item list-group-item-action" 
                                style="cursor: pointer;" 
                                onclick="seleccionarAprendiz(${aprendiz.id_aprendiz}, '${aprendiz.correo_aprendiz.replace(/'/g, "\\'")}')">
                                ${aprendiz.correo_aprendiz}
                                <span class="badge bg-success float-end">Disponible</span>
                            </li>
                        `;
                    }
                });
                html += '</ul>';
            } else {
                html += `
                    <div class="alert alert-info mb-0">
                        <small>No se encontró el aprendiz "${texto}"</small>
                    </div>
                `;
            }

            document.getElementById('sugerenciasAprendiz').innerHTML = html;
        },
        error: function (xhr, status, error) {
            console.error("❌ Error en la búsqueda:", error);
            document.getElementById('sugerenciasAprendiz').innerHTML = '<div class="text-danger ps-2">Error al buscar aprendices.</div>';
        }
    });
}
//fin de la funcion que agrega aprendices 


//el arreglo de los aprendices, basado en el codigo de cesar, aqui se utiliza para alamacenar cada dato seleccionado de la db
let aprendicesSeleccionados = [];

function seleccionarAprendiz(id, correo) {    
    id = parseInt(id);
    
    if (aprendicesSeleccionados.includes(id)) {
        document.getElementById('sugerenciasAprendiz').innerHTML = '';
        document.getElementById('busquedaAprendiz').value = '';
        return;
    }

    aprendicesSeleccionados.push(id);
    document.getElementById('aprendices_curso').value = JSON.stringify(aprendicesSeleccionados);

    const contenedor = document.getElementById('aprendicesSeleccionados');
    const chip = document.createElement('span');

    //compañeritos, este es el color con el que saldran los estudiantes, pueden cambiarlo segun el css que mas le guste
    chip.style.cssText = `
        display: inline-flex;
        align-items: center;
        background-color: #fff3e0;
        color: #e65100;
        padding: 6px 12px;
        border-radius: 30px;
        font-size: 14px;
        border: 1px solid #ffcc80;
        margin-right: 5px;
        margin-bottom: 5px;
    `;
    chip.innerHTML = `
        ${correo}
        <span 
            onclick="eliminarAprendiz(${id}, this)" 
            style="
                margin-left: 8px;
                font-size: 16px;
                cursor: pointer;
                font-weight: bold;
            "
        >&times;</span>
    `;

    contenedor.appendChild(chip);

    document.getElementById('sugerenciasAprendiz').innerHTML = '';
    document.getElementById('busquedaAprendiz').value = '';
}

function eliminarAprendiz(id, elemento) {
    const index = aprendicesSeleccionados.indexOf(id);
    if (index > -1) {
        aprendicesSeleccionados.splice(index, 1);
    }
    
    document.getElementById('aprendices_curso').value = JSON.stringify(aprendicesSeleccionados);
    elemento.parentElement.remove();
}
</script>


<!-- funcion que muestra los detalles del curso -->
<!-- Función que muestra los detalles del curso -->
<script>
function verDetallesCurso(idCurso) {
    $.ajax({
        url: '../controllers/obtenerDetallesCurso.php',
        type: 'POST',
        data: { id_curso: idCurso },
        dataType: 'json',
        success: function(response) {
            if(response.success) {
                mostrarModalDetalles(response);
            } else {
                Swal.fire('Error', response.message, 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al obtener detalles:", error);
            Swal.fire('Error', 'Hubo un problema al cargar los detalles', 'error');
        }
    });
}

function mostrarModalDetalles(data) {
    // HTML de instructores
    let htmlInstructores = '';
    if(data.instructores && data.instructores.length > 0) {
        htmlInstructores = '<ul class="list-group mb-3">';
        data.instructores.forEach(instructor => {
            htmlInstructores += `
                <li class="list-group-item d-flex align-items-center">
                    <span>${instructor.correo_instructor}</span>
                </li>
            `;
        });
        htmlInstructores += '</ul>';
    } else {
        htmlInstructores = '<p class="text-muted">No hay instructores asignados</p>';
    }

    // Modal con la información del curso, ahora ya no muestra los aprendicves, porque se asocian a las fichas, en la DB hay una relacion igualmente que quedaria vacia
    Swal.fire({
        title: `<i class="bi bi-mortarboard-fill"></i> ${data.curso.nombre_curso}`,
        html: `
            <div class="text-start">
                <div class="mb-4">
                    <h5 class="border-bottom pb-2">
                        Instructores (${data.instructores ? data.instructores.length : 0})
                    </h5>
                    ${htmlInstructores}
                </div>
            </div>
        `,
        width: '600px',
        confirmButtonText: 'Cerrar',
        confirmButtonColor: '#6c757d',
        customClass: {
            container: 'detalles-curso-modal'
        }
    });
}
</script>






  </body>
  <!--end::Body-->
</html>
