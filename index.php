<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once 'models/MySQL.php';
session_start();

if (!isset($_SESSION['rol_usuario'])) {
    header("location: ./views/login.php");
    exit();
}

$mysql = new MySQL();
$mysql->conectar();

$rol    = $_SESSION['rol_usuario'];
$nombre = $_SESSION['correo_'.$rol] ?? '';

$idUsuarioSesion = $_SESSION['id_'.$rol] ?? '';

$resultado = $mysql->efectuarConsulta("SELECT * FROM trabajos");

$resultadolibros = $mysql->efectuarConsulta("SELECT * FROM libros");
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
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="./css/adminlte.css" as="style" />
    <!--end::Accessibility Features-->

    <!-- Fonts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
      crossorigin="anonymous"
      media="print"
      onload="this.media='all'"
    />

    <!-- OverlayScrollbars -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
      crossorigin="anonymous"
    />

    <!-- Bootstrap Icons -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
      crossorigin="anonymous"
    />

    <!-- AdminLTE -->
    <link rel="stylesheet" href="./css/adminlte.css" />
    <link rel="stylesheet" href="./css/style.css">

    <!-- Apexcharts -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
      crossorigin="anonymous"
    />

    <!-- jsvectormap -->
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
      crossorigin="anonymous"
    />
   
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- DataTables + Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

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
        gap: 30px;
        margin: 40px auto;
        max-width: 1400px;
        padding: 20px;
      }
      .card-documento {
        flex: 1 1 30%;
        min-width: 350px;
        background-color: #ffffff;
        padding: 30px 35px;
        border-radius: 16px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        min-height: 500px;
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
        align-self: flex-start;
        transition: background-color 0.3s ease, transform 0.2s ease;
      }
      .btn-generar:hover {
        background-color: #ff0000ff;
        transform: translateY(-2px);
      }
      .btn-generar i {
        font-size: 16px;
      }
      .btn-group {
        display: flex;
        gap: 15px;
        align-items: center;
        width: 100%;
      }
      .btn-excel {
        background-color: #00a390ff;
        color: #fff;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        padding: 12px 18px;
        text-decoration: none;
        display: inline-flex;
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
      .btn-group .btn-generar {
        flex-grow: 1;
        margin-top: 0;
        align-self: unset;
      }
    </style>
  </head>
  <!--end::Head-->

  <!--begin::Body-->
  <body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
      <!--begin::Header-->
      <nav class="app-header navbar navbar-expand bg-body">
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
                <span class="d-none d-md-inline">
                  <i class="bi bi-person-circle me-1"></i><?php echo htmlspecialchars($nombre); ?>
                </span>
              </a>

              <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width: 230px;">
                <li class="bg-primary text-white text-center rounded-top py-3">
                  <p class="mb-0 fw-bold fs-5"><?php echo htmlspecialchars($nombre); ?></p>
                  <small><?php echo htmlspecialchars($rol); ?></small>
                </li>

                <li><hr class="dropdown-divider m-0"></li>

                <!-- es el modal  -->
                <li>
                  <a href="#" 
                     class="dropdown-item d-flex align-items-center py-2"
                     data-bs-toggle="modal"
                     data-bs-target="#modalEditarUsuario">
                    <i class="bi bi-person me-2 text-secondary"></i> Perfil
                  </a>
                </li>

                <li><hr class="dropdown-divider m-0"></li>

                <li>
                  <a href="./controllers/logout.php" class="dropdown-item d-flex align-items-center text-danger py-2">
                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                  </a>
                </li>
              </ul>
            </li>
            <!--end::User Menu Dropdown-->
          </ul>
          <!--end::End Navbar Links-->
        </div>
      </nav>
      <!--end::Header-->

      <!--begin::Sidebar-->
      <aside class="app-sidebar verde shadow">
        <div class="sidebar-brand">
          <a href="./index.php" class="brand-link">
            <span class="title"> senaEdu </span>
          </a>
        </div>

        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              <li class="nav-item">
                <a href="./index.php" class="nav-link active">
                  <i class="nav-icon bi bi-speedometer me-2"></i>
                  <span>Dashboard</span>
                </a>
              </li>

              <?php if ($rol == 'admin'): ?>
              <li class="nav-item">
                <a href="./views/usuarios.php" class="nav-link">
                  <i class="bi bi-file-earmark-person me-2"></i>
                  <span>Usuarios</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./views/inventario.php" class="nav-link">
                  <i class="bi bi-book me-2"></i>
                  <span>Libros</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./views/reservas.php" class="nav-link">
                  <i class="bi bi-journal-richtext me-2"></i>
                  <span>Reservas</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="./views/historialPrestamosAdmin.php" class="nav-link">
                  <i class="bi bi-journal-arrow-down me-2"></i>
                  <span>Prestamos</span>
                </a>
              </li>
              <?php endif; ?>

              <?php if ($rol == 'aprendiz'): ?>
              <li class="nav-item">
                <a href="./views/gestionTrabajos.php" class="nav-link">
                  <i class="bi bi-calendar-check me-2"></i>
                  <span>Trabajos</span>
                </a>
              </li>
              <?php endif; ?>
            </ul>
          </nav>
        </div>
      </aside>
      <!--end::Sidebar-->

      <!--begin::App Main-->
      <main class="app-main">
        <div class="app-content-header"></div>

        <div class="app-content">
          <div class="container-fluid">
            <div class="row">
              <?php if($rol != "admin"): ?>
              <div class="table-responsive">
                <div class="col"> 
                  <button class="btn btn-sm btn-primary btnReservar mb-4 w-100" onclick="abrirCrearReserva()">
                    <i class="bi bi-bookmark-plus"></i> Realizar Reserva
                  </button> 
                </div>
                  
                <table id="tablaLibros" class="table table-striped table-bordered" width="100%">
                  <thead class="table-success">
                    <tr>
                      <th>ID</th>
                      <th>Título</th>
                      <th>Autor</th>
                      <th>ISBN</th>
                      <th>Categoría</th>
                      <th>Cantidad</th>
                      <th>Estado</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while($fila = $resultadolibros->fetch_assoc()): ?>
                    <tr>
                      <td><?= $fila['id_libro'] ?></td>
                      <td><?= $fila['titulo_libro'] ?></td>
                      <td><?= $fila['autor_libro'] ?></td>
                      <td><?= $fila['ISBN_libro'] ?></td>
                      <td><?= $fila['categoria_libro'] ?></td>
                      <td><?= $fila['cantidad_libro'] ?></td>
                      <td>
                        <?php if($fila['cantidad_libro'] == 0): ?>
                          <span class="badge bg-danger">No disponible</span>
                        <?php else: ?>
                          <span class="badge bg-success"><?= $fila['disponibilidad_libro'] ?></span>
                        <?php endif; ?>
                      </td>
                    </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </main>
      <!--end::App Main-->

      <!--begin::Footer-->
      <footer class="app-footer">
        <strong>
          Copyright &copy; 2014-2025
          <a href="https://adminlte.io" class="text-decoration-none">senaEdu</a>.
        </strong>
        All rights reserved.
      </footer>
      <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->


                        <!--sirve para tomar con cual usario se ingresa en el editar perfil-->
    <?php
$rol = $_SESSION['rol_usuario'];
$id_user = $_SESSION['id_'.$rol];
$correo_user = $_SESSION['correo_'.$rol];
?>

<!-- Modal Editar Usuario -->
<div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content shadow-lg border-0 rounded-3">
      
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title">Editar Perfil</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body p-4">

        <form action="./controllers/editar_perfil.php" method="POST">

          <input type="hidden" name="rol_usuario" value="<?php echo $rol; ?>">
          <input type="hidden" name="id_usuario" value="<?php echo $id_user; ?>">

          <div class="row g-3">


            <div class="col-md-8">
              <label class="form-label fw-semibold">Correo</label>
              <input type="email" class="form-control" name="correo_usuario" 
                     value="<?php echo $correo_user; ?>" required>
            </div>

            <?php if($rol === 'admin'): ?>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Rol</label>
              <select name="nuevo_rol" class="form-select">
                <option value="admin" <?php echo ($rol=='admin'?'selected':''); ?>>Administrador</option>
                <option value="instructor">Instructor</option>
                <option value="aprendiz">Aprendiz</option>
              </select>
            </div>
            <?php endif; ?>

            <div class="col-md-6">
            <label class="form-label fw-semibold">Nueva Contraseña </label>
            <input type="password" class="form-control" name="password_usuario">
            </div>

          </div>

          <div class="mt-4 text-end">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
           <button type="submit" class="btn btn-primary"> Guardar</button>
          </div>

        </form>

      </div>

    </div>
  </div>
</div>

    <!--  fin modal editar -->

    <!-- Scripts -->
    <script
      src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
      crossorigin="anonymous"
    ></script>

    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
      crossorigin="anonymous"
    ></script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
      crossorigin="anonymous"
    ></script>

    <script src="public/js/adminlte.js"></script>

    <script>
      const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
      const Default = {
        scrollbarTheme: 'os-theme-light',
        scrollbarAutoHide: 'leave',
        scrollbarClickScroll: true,
      };
      document.addEventListener('DOMContentLoaded', function () {
        const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);
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

        const tabla = document.getElementById('tablaLibros');
        if (tabla) {
          $('#tablaLibros').DataTable({
            responsive: true
          });
        }
      });
    </script>
<!--funcionalidad de el modal editar-->
    <script>
document.querySelector('#modalEditarUsuario form').addEventListener('submit', function(e){
    e.preventDefault(); 

    let datos = new FormData(this);

    fetch('./controllers/editar_perfil.php', {
        method: 'POST',
        body: datos
    })
    .then(res => res.text())
    .then(res => {

        if(res.includes("OK")){
            Swal.fire({
                icon: 'success',
                title: 'Actualizado correctamente',
                timer: 1500,
                showConfirmButton: false
            });

            let modal = bootstrap.Modal.getInstance(document.getElementById('modalEditarUsuario'));
            modal.hide();

            location.reload();
        } 
        else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: res
            });
        }

    });
});
</script>

  </body>
</html>
