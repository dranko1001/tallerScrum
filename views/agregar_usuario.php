<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once '../models/MySQL.php'; 
session_start();

// este es solo para que el admin vea el agregar usuarios
if (!isset($_SESSION['rol_usuario']) || $_SESSION['rol_usuario'] !== 'admin') {
    header("location: ./login.php"); 
    exit();
}

$mysql = new MySQL();
$mysql->conectar();

$rol = $_SESSION['rol_usuario'];
$nombre = $_SESSION['correo_'.$rol] ?? '';
$idUsuarioSesion = $_SESSION['id_'.$rol] ?? '';

$id_user = $idUsuarioSesion;
$correo_user = $nombre; 
?>

<!doctype html>
<html lang="es">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title> Agregar Usuario | EduSena </title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" media="print" onload="this.media='all'" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="../css/adminlte.css" /> 
    <link rel="stylesheet" href="../css/style.css"> 

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
      .card-header-custom {
          background: linear-gradient(90deg, #198754, #20c997);
          color: white;
          font-weight: bold;
          border-radius: 0.5rem 0.5rem 0 0;
      }
      .btn-success-custom {
          background-color: #28a745;
          border-color: #28a745;
          transition: transform 0.2s;
      }
      .btn-success-custom:hover {
          background-color: #218838;
          border-color: #1e7e34;
          transform: translateY(-2px);
      }
    </style>
</head>
<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
      
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="../index.php" role="button">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="../index.php" class="nav-link">Inicio</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
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
                                <a href="../controllers/logout.php" class="dropdown-item d-flex align-items-center text-danger py-2">
                                    <i class="bi bi-box-arrow-right me-2"></i> Cerrar sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        
        <aside class="app-sidebar verde shadow">
            <div class="sidebar-brand">
                <a href="../index.php" class="brand-link">
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
                            <a href="../index.php" class="nav-link">
                                <i class="nav-icon bi bi-speedometer me-2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <?php if ($rol == 'admin'): ?>
                        <li class="nav-item">
                            <a href="./usuarios.php" class="nav-link active">
                                <i class="bi bi-file-earmark-person me-2"></i>
                                <span>Usuarios</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./inventario.php" class="nav-link">
                                <i class="bi bi-book me-2"></i>
                                <span>Libros</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./reservas.php" class="nav-link">
                                <i class="bi bi-journal-richtext me-2"></i>
                                <span>Reservas</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./historialPrestamosAdmin.php" class="nav-link">
                                <i class="bi bi-journal-arrow-down me-2"></i>
                                <span>Prestamos</span>
                            </a>
                        </li>
                        <?php endif; ?>
                        <?php if ($rol == 'aprendiz'): ?>
                        <li class="nav-item">
                            <a href="./gestionTrabajos.php" class="nav-link">
                                <i class="bi bi-calendar-check me-2"></i>
                                <span>Trabajos</span>
                            </a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </aside>

        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <h3 class="mb-0">Agregar Nuevo Usuario</h3>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-end">
                                <li class="breadcrumb-item"><a href="../index.php">Inicio</a></li>
                                <li class="breadcrumb-item"><a href="./usuarios.php">Usuarios</a></li>
                                <li class="breadcrumb-item active">Agregar Usuario</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    
                    <div class="row"> 
                        <div class="col-12"> 
                            <div class="card shadow-lg border-0 rounded-3">
                                
                                <div class="card-header card-header-custom">
                                    <h4 class="card-title mb-0"></i> Datos del Nuevo Usuario</h4>
                                </div>
                                
                                <div class="card-body p-4 p-md-5">
                                   <form id="formAgregarUsuario" action="../controllers/agregar_usuario.php" method="POST">
                          <div class="row g-4">

                           <div class="col-md-6">
                                 <label for="correo_usuario" class="form-label fw-semibold">Correo Electrónico</label>
                                 <input type="email" class="form-control" id="correo_usuario" name="correo_usuario" required >
                             </div>

                            <div class="col-md-6">
                                <label for="password_usuario" class="form-label fw-semibold">Contraseña</label>
                                <input type="password" class="form-control" id="password_usuario" name="password_usuario" required>
                              </div>

                             <div class="col-md-6">
                               <label for="rol_usuario" class="form-label fw-semibold">Rol</label>
                                <select id="rol_usuario" name="rol_usuario" class="form-select" required>
                               <option value="" disabled selected>Seleccione un rol</option>
                               <option value="admin">Administrador</option>
                               <option value="instructor">Instructor</option>
                                <option value="aprendiz">Aprendiz</option>
                           </select>
            </div>
            
        </div>

        <div class="mt-5 pt-3 border-top d-flex justify-content-end gap-3">
            <a href="./usuarios.php" class="btn btn-secondary">
                 cancelar
            </a>
            <button type="submit" class="btn btn-success btn-success-custom"> 
                 Agregar Usuario
            </button>
                                   </div>
                                   </form>
                            </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
        
        <footer class="app-footer">
            <strong>
                Copyright &copy; 2014-2025
                <a href="https://adminlte.io" class="text-decoration-none">senaEdu</a>.
            </strong>
            All rights reserved.
        </footer>
    </div>
    
    <div class="modal fade" id="modalEditarUsuario" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content shadow-lg border-0 rounded-3">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Editar Perfil</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="../controllers/editar_perfil.php" method="POST">
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
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="../public/js/adminlte.js"></script>

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
      });
    </script>
    
    <script>
    document.querySelector('#formAgregarUsuario').addEventListener('submit', function(e){
        e.preventDefault(); 

        let datos = new FormData(this);

        fetch('../controllers/agregar_usuario.php', {
            method: 'POST',
            body: datos
        })
        .then(res => res.text())
        .then(res => {
            if(res.includes("OK")){ 
        Swal.fire({
            icon: 'success',
            title: 'Usuario agregado correctamente',
            text: 'El nuevo usuario ha sido registrado.',
            showConfirmButton: false,
            timer: 2000
        }).then(() => {
            window.location.href = './agregar_usuario.php'; 
        });
        
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

    <script>
    document.querySelector('#modalEditarUsuario form').addEventListener('submit', function(e){
        e.preventDefault(); 

        let datos = new FormData(this);

        fetch('../controllers/editar_perfil.php', {
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
