<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['rol_usuario'])) {
    header("location: ./login.php");
    exit();
}

$mysql = new MySQL();
$mysql->conectar();

$rol = $_SESSION['rol_usuario'];
$nombre = $_SESSION['correo_' . $rol];

// Verificar que sea administrador
if ($rol !== 'admin') {
    header("location: ../index.php");
    exit();
}

// Consultar administradores
$consultaAdmins = $mysql->efectuarConsulta("SELECT * FROM administrador ORDER BY id_admin ASC");

// Consultar instructores
$consultaInstructores = $mysql->efectuarConsulta("SELECT * FROM instructor ORDER BY id_instructor ASC");

// Consultar aprendices
$consultaAprendices = $mysql->efectuarConsulta("SELECT * FROM aprendices ORDER BY id_aprendiz ASC");
?>

<!doctype html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>EduSena - Gestión de Usuarios</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <link rel="stylesheet" href="../css/adminlte.css" />
    <link rel="stylesheet" href="../css/style.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .btn-info {
            background: linear-gradient(135deg, #17a2b8, #5bc0de);
            border: none;
            transition: all 0.3s ease;
            color: white;
            font-weight: 500;
        }

        .btn-info:hover {
            transform: translateY(-5px) scale(1.05);
            background: linear-gradient(135deg, #5bc0de, #17a2b8);
            box-shadow: 0 8px 15px rgba(0, 123, 255, 0.3);
        }

        .card {
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
    </style>
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        <nav class="app-header navbar navbar-expand bg-body">
            <div class="container-fluid">
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

                <ul class="navbar-nav ms-auto">
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle text-white fw-semibold" data-bs-toggle="dropdown">
                            <span class="d-none d-md-inline"><i
                                    class="bi bi-person-circle me-1"></i><?php echo $nombre; ?></span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2"
                            style="min-width: 230px;">
                            <li class="bg-primary text-white text-center rounded-top py-3">
                                <p class="mb-0 fw-bold fs-5"><?php echo $nombre; ?></p>
                                <small><?php echo $rol; ?></small>
                            </li>
                            <li>
                                <hr class="dropdown-divider m-0">
                            </li>
                            <li>
                                <button class="dropdown-item d-flex align-items-center py-2" onclick="editarMiPerfil()">
                                    <i class="bi bi-person-gear me-2"></i> Editar mi perfil
                                </button>
                            </li>
                            <li>
                                <hr class="dropdown-divider m-0">
                            </li>
                            <li>
                                <a href="../controllers/logout.php"
                                    class="dropdown-item d-flex align-items-center text-danger py-2">
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
                    <span class="title">senaEdu</span>
                </a>
            </div>

            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation">
                        <li class="nav-item">
                            <a href="../index.php" class="nav-link">
                                <i class="nav-icon bi bi-speedometer me-2"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>

                        <?php if ($rol == 'admin'): ?>
                            <li class="nav-item">
                                <a href="./usuarios.php" class="nav-link active">
                                    <i class="bi bi-people me-2"></i>
                                    <span>Usuarios</span>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </aside>

        <main class="app-main">
            <div class="app-content">
                <div class="container-fluid">

                    <div class="row mb-4">
                        <div class="col-12">
                            <h1 class="mt-4">Gestión de Usuarios</h1>
                            <button class="btn btn-success" onclick="agregarUsuario()">
                                <i class="bi bi-person-plus"></i> Agregar Usuario
                            </button>
                        </div>
                    </div>

                    <!-- Tabla Administradores -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-danger text-white">
                                    <h3 class="card-title mb-0">
                                        <i class="bi bi-shield-check me-2"></i>Administradores
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="tablaAdmins" class="table table-striped table-bordered" width="100%">
                                            <thead class="table-danger">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Correo</th>
                                                    <th>Rol</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($admin = $consultaAdmins->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= $admin['id_admin'] ?></td>
                                                        <td><?= $admin['correo_admin'] ?></td>
                                                        <td><span
                                                                class="badge bg-danger"><?= $admin['rol_usuario'] ?></span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-warning btn-sm" title="Editar"
                                                                onclick='editarUsuario(<?= json_encode($admin) ?>, "admin")'>
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm" title="Eliminar"
                                                                onclick='eliminarUsuario(<?= $admin["id_admin"] ?>, "admin")'>
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla Instructores -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-warning text-dark">
                                    <h3 class="card-title mb-0">
                                        <i class="bi bi-person-badge me-2"></i>Instructores
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="tablaInstructores" class="table table-striped table-bordered"
                                            width="100%">
                                            <thead class="table-warning">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Correo</th>
                                                    <th>Rol</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($instructor = $consultaInstructores->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= $instructor['id_instructor'] ?></td>
                                                        <td><?= $instructor['correo_instructor'] ?></td>
                                                        <td><span
                                                                class="badge bg-warning text-dark"><?= $instructor['rol_usuario'] ?></span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-warning btn-sm" title="Editar"
                                                                onclick='editarUsuario(<?= json_encode($instructor) ?>, "instructor")'>
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm" title="Eliminar"
                                                                onclick='eliminarUsuario(<?= $instructor["id_instructor"] ?>, "instructor")'>
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla Aprendices -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <h3 class="card-title mb-0">
                                        <i class="bi bi-mortarboard me-2"></i>Aprendices
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="tablaAprendices" class="table table-striped table-bordered"
                                            width="100%">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Correo</th>
                                                    <th>Rol</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($aprendiz = $consultaAprendices->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= $aprendiz['id_aprendiz'] ?></td>
                                                        <td><?= $aprendiz['correo_aprendiz'] ?></td>
                                                        <td><span
                                                                class="badge bg-success"><?= $aprendiz['rol_usuario'] ?></span>
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-warning btn-sm" title="Editar"
                                                                onclick='editarUsuario(<?= json_encode($aprendiz) ?>, "aprendiz")'>
                                                                <i class="bi bi-pencil-square"></i>
                                                            </button>
                                                            <button class="btn btn-danger btn-sm" title="Eliminar"
                                                                onclick='eliminarUsuario(<?= $aprendiz["id_aprendiz"] ?>, "aprendiz")'>
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </main>

        <footer class="app-footer">
            <strong>
                Copyright &copy; 2014-2025&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">senaEdu</a>.
            </strong>
            All rights reserved.
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js"
        crossorigin="anonymous"></script>
    <script src="../public/js/adminlte.js"></script>

    <script>
        $(document).ready(function () {
            $('#tablaAdmins, #tablaInstructores, #tablaAprendices').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                },
                pageLength: 10,
                lengthMenu: [5, 10, 20, 50],
                responsive: true,
                autoWidth: true
            });
        });

        function agregarUsuario() {
            Swal.fire({
                title: 'Agregar Usuario',
                html: `
          <form id="formAgregarUsuario" class="text-start">
            <div class="mb-3">
              <label class="form-label fw-bold">Rol:</label>
              <select class="form-select" id="rol" required>
                <option value="" disabled selected>Seleccione un rol</option>
                <option value="admin">Administrador</option>
                <option value="instructor">Instructor</option>
                <option value="aprendiz">Aprendiz</option>
              </select>
            </div>
            
            <div class="mb-3">
              <label class="form-label fw-bold">Correo electrónico:</label>
              <input type="email" class="form-control" id="correo" 
                     placeholder="ejemplo@correo.com" required>
            </div>
            
            <div class="mb-3">
              <label class="form-label fw-bold">Contraseña:</label>
              <input type="password" class="form-control" id="password" 
                     placeholder="Mínimo 6 caracteres" required>
            </div>
          </form>
        `,
                width: '500px',
                confirmButtonText: 'Guardar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                preConfirm: () => {
                    const rol = document.getElementById('rol').value;
                    const correo = document.getElementById('correo').value.trim();
                    const password = document.getElementById('password').value;

                    if (!rol || !correo || !password) {
                        Swal.showValidationMessage('Debe completar todos los campos');
                        return false;
                    }

                    if (password.length < 6) {
                        Swal.showValidationMessage('La contraseña debe tener al menos 6 caracteres');
                        return false;
                    }

                    return { rol, correo, password };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../controllers/agregarUsuario.php',
                        type: 'POST',
                        data: result.value,
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                        }
                    });
                }
            });
        }

        function editarUsuario(usuario, tipoUsuario) {
            let idField, correoField, correoValue;

            if (tipoUsuario === 'admin') {
                idField = 'id_admin';
                correoField = 'correo_admin';
                correoValue = usuario.correo_admin;
            } else if (tipoUsuario === 'instructor') {
                idField = 'id_instructor';
                correoField = 'correo_instructor';
                correoValue = usuario.correo_instructor;
            } else {
                idField = 'id_aprendiz';
                correoField = 'correo_aprendiz';
                correoValue = usuario.correo_aprendiz;
            }

            Swal.fire({
                title: 'Editar Usuario',
                html: `
          <form id="formEditarUsuario" class="text-start">
            <div class="mb-3">
              <label class="form-label fw-bold">Rol:</label>
              <select class="form-select" id="rol_edit" required>
                <option value="admin" ${usuario.rol_usuario === 'Administrador' ? 'selected' : ''}>Administrador</option>
                <option value="instructor" ${usuario.rol_usuario === 'Instructor' ? 'selected' : ''}>Instructor</option>
                <option value="aprendiz" ${usuario.rol_usuario === 'Aprendiz' ? 'selected' : ''}>Aprendiz</option>
              </select>
            </div>
            
            <div class="mb-3">
              <label class="form-label fw-bold">Correo electrónico:</label>
              <input type="email" class="form-control" id="correo_edit" 
                     value="${correoValue}" required>
            </div>
          </form>
        `,
                width: '500px',
                confirmButtonText: 'Actualizar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ffc107',
                preConfirm: () => {
                    const rol = document.getElementById('rol_edit').value;
                    const correo = document.getElementById('correo_edit').value.trim();

                    if (!rol || !correo) {
                        Swal.showValidationMessage('Debe completar todos los campos');
                        return false;
                    }

                    return {
                        id: usuario[idField],
                        rol_anterior: tipoUsuario,
                        rol_nuevo: rol,
                        correo: correo
                    };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../controllers/editarUsuario.php',
                        type: 'POST',
                        data: result.value,
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                        }
                    });
                }
            });
        }

        function eliminarUsuario(id, tipoUsuario) {
            Swal.fire({
                title: '¿Está seguro?',
                text: 'Esta acción eliminará el usuario permanentemente',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../controllers/eliminarUsuario.php',
                        type: 'POST',
                        data: { id: id, tipo_usuario: tipoUsuario },
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Eliminado',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                        }
                    });
                }
            });
        }

        function editarMiPerfil() {
            Swal.fire({
                title: 'Editar mi Perfil',
                html: `
          <form id="formEditarPerfil" class="text-start">
            <div class="mb-3">
              <label class="form-label fw-bold">Correo electrónico:</label>
              <input type="email" class="form-control" id="mi_correo" 
                     value="<?= $nombre ?>" required>
            </div>
            
            <div class="mb-3">
              <label class="form-label fw-bold">Nueva contraseña (opcional):</label>
              <input type="password" class="form-control" id="mi_password" 
                     placeholder="Dejar en blanco para no cambiar">
            </div>
          </form>
        `,
                width: '500px',
                confirmButtonText: 'Actualizar',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#007bff',
                preConfirm: () => {
                    const correo = document.getElementById('mi_correo').value.trim();
                    const password = document.getElementById('mi_password').value;

                    if (!correo) {
                        Swal.showValidationMessage('El correo es obligatorio');
                        return false;
                    }

                    if (password && password.length < 6) {
                        Swal.showValidationMessage('La contraseña debe tener al menos 6 caracteres');
                        return false;
                    }

                    return { correo, password };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../controllers/editarMiPerfil.php',
                        type: 'POST',
                        data: result.value,
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => location.reload());
                            } else {
                                Swal.fire('Error', response.message, 'error');
                            }
                        },
                        error: function () {
                            Swal.fire('Error', 'No se pudo conectar con el servidor', 'error');
                        }
                    });
                }
            });
        }
    </script>
</body>

</html>