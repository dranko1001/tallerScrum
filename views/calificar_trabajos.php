<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['rol_usuario'])) {
    header("location: ./views/login.php");
    exit();
}

$mysql = new MySQL();
$mysql->conectar();

$rol = $_SESSION['rol_usuario'];
$nombre = $_SESSION['correo_' . $rol];
?>

<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>EduSena | Calificar Trabajos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS necesarios -->
    <link rel="stylesheet" href="../css/adminlte.css">
    <link rel="stylesheet" href="../css/style.css">

    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Tu JS -->

</head>

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">

    <div class="app-wrapper">

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
                            <span class="d-none d-md-inline"><i
                                    class="bi bi-person-circle me-1"></i><?php echo $nombre; ?></span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2"
                            style="min-width: 230px;">
                            <!-- Cabecera del usuario -->
                            <li class="bg-primary text-white text-center rounded-top py-3">
                                <p class="mb-0 fw-bold fs-5"><?php echo $nombre; ?></p>
                                <small><?php echo $rol; ?></small>
                            </li>

                            <!-- Separador -->
                            <li>
                                <hr class="dropdown-divider m-0">
                            </li>

                            <!-- Opciones del menu -->
                            <li>
                                <a href="./views/perfilUsuario.php"
                                    class="dropdown-item d-flex align-items-center py-2">
                                    <i class="bi bi-person me-2 text-secondary"></i> Perfil
                                </a>
                            </li>

                            <!-- Separador -->
                            <li>
                                <hr class="dropdown-divider m-0">
                            </li>

                            <!-- Opción de cerrar sesión -->
                            <li>
                                <a href="./controllers/logout.php"
                                    class="dropdown-item d-flex align-items-center text-danger py-2">
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

        <!-- Sidebar -->
        <aside class="app-sidebar bg-body-secondary shadow">
            <div class="sidebar-brand">
                <a href="#" class="brand-link">
                    <span class="brand-text fw-light">EduSena</span>
                </a>
            </div>
        </aside>

        <!-- Contenido principal -->
        <main class="app-main">
            <div class="container-fluid">

                <h4 class="mb-4">
                    <i class="bi bi-check2-square me-2"></i> Calificar Notas
                </h4>

                <div class="table-responsive">
                    <input type="hidden" id="id_usuario_sesion" value="<?php echo $_SESSION['rol_usuario']; ?>">

                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID trabajo</th>
                                <th>Trabajo</th>
                                <th>Fecha</th>
                                <th>Aprendiz</th>
                                <th>Calificación</th>
                                <th>Comentario</th>
                                <th>Acción</th>
                            </tr>
                        </thead>

                        <tbody id="tablaNotas"></tbody>
                    </table>
                </div>

            </div>
        </main>

    </div>
<script src="../public/js/calificar_notas.js"></script>
</body>

</html>