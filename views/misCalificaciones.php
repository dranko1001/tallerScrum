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

// Verificar que sea aprendiz
if ($rol !== 'aprendiz') {
    header("location: ../index.php");
    exit();
}

$idAprendiz = $_SESSION['id_aprendiz'];

// Consulta CORREGIDA para obtener los trabajos con sus calificaciones
$sqlCalificaciones = "
    SELECT 
        t.id_trabajo,
        t.nombre_trabajo,
        t.fecha_trabajo,
        t.ruta_trabajo,
        c.nombre_curso,
        i.correo_instructor,
        n.calificacion_nota,
        n.comentario_nota,
        n.id_nota
    FROM trabajos t
    LEFT JOIN cursos c ON t.cursos_id_curso = c.id_curso
    LEFT JOIN notas n ON t.id_trabajo = n.trabajos_id_trabajo
    LEFT JOIN instructor i ON n.instructor_id_instructor = i.id_instructor
    WHERE t.aprendices_id_aprendiz = '$idAprendiz'
    ORDER BY t.fecha_trabajo DESC
";

$resultadoCalificaciones = $mysql->efectuarConsulta($sqlCalificaciones);

// Estadísticas CORREGIDAS
$sqlEstadisticas = "
    SELECT 
        COUNT(t.id_trabajo) as total_trabajos,
        SUM(CASE WHEN n.calificacion_nota = 'A' THEN 1 ELSE 0 END) as aprobados,
        SUM(CASE WHEN n.calificacion_nota = 'D' THEN 1 ELSE 0 END) as desaprobados,
        SUM(CASE WHEN n.calificacion_nota IS NULL THEN 1 ELSE 0 END) as pendientes
    FROM trabajos t
    LEFT JOIN notas n ON t.id_trabajo = n.trabajos_id_trabajo
    WHERE t.aprendices_id_aprendiz = '$idAprendiz'
";

$resultadoStats = $mysql->efectuarConsulta($sqlEstadisticas);
$stats = $resultadoStats->fetch_assoc();
?>

<!doctype html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>EduSena - Mis Calificaciones</title>

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
        letter-spacing: 0.3px;
        }

        .btn-info:hover {
        transform: translateY(-5px) scale(1.05);
        background: linear-gradient(135deg, #5bc0de, #17a2b8);
        box-shadow: 0 8px 15px rgba(0, 123, 255, 0.3);
        }

        .btn-info:active {
        transform: scale(0.98);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.2);
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
        <?php if ($rol == 'aprendiz'): ?>
                    <li class="nav-item">
                        <a href="./gestionTrabajos.php" class="nav-link">
                            <i class="bi bi-calendar-check me-2"></i>
                            <span>Trabajos</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="./misCalificaciones.php" class="nav-link active">
                            <i class="bi bi-star me-2"></i>
                            <span>Mis Calificaciones</span>
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

                    <!-- Estadísticas -->
                    <div class="row mb-4 mt-4">
                        <div class="col-lg-3 col-6">
                            <div class="card stat-card total">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Total Trabajos</p>
                                            <h3 class="mb-0"><?= $stats['total_trabajos'] ?></h3>
                                        </div>
                                        <div class="text-primary">
                                            <i class="bi bi-file-earmark-text" style="font-size: 2.5rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="card stat-card aprobados">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Aprobados</p>
                                            <h3 class="mb-0 text-success"><?= $stats['aprobados'] ?></h3>
                                        </div>
                                        <div class="text-success">
                                            <i class="bi bi-check-circle-fill" style="font-size: 2.5rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="card stat-card desaprobados">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Desaprobados</p>
                                            <h3 class="mb-0 text-danger"><?= $stats['desaprobados'] ?></h3>
                                        </div>
                                        <div class="text-danger">
                                            <i class="bi bi-x-circle-fill" style="font-size: 2.5rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-6">
                            <div class="card stat-card pendientes">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <p class="text-muted mb-1">Pendientes</p>
                                            <h3 class="mb-0 text-warning"><?= $stats['pendientes'] ?></h3>
                                        </div>
                                        <div class="text-warning">
                                            <i class="bi bi-clock-fill" style="font-size: 2.5rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de Calificaciones -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title mb-0">
                                        <i class="bi bi-award me-2"></i>Mis Calificaciones
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="tablaCalificaciones" class="table table-striped table-bordered"
                                            width="100%">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Trabajo</th>
                                                    <th>Curso</th>
                                                    <th>Fecha Entrega</th>
                                                    <th>Calificación</th>
                                                    <th>Instructor</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($calif = $resultadoCalificaciones->fetch_assoc()): ?>
                                                    <tr>
                                                        <td><?= $calif['id_trabajo'] ?></td>
                                                        <td><?= $calif['nombre_trabajo'] ?></td>
                                                        <td><?= $calif['nombre_curso'] ?? 'N/A' ?></td>
                                                        <td><?= date('d/m/Y', strtotime($calif['fecha_trabajo'])) ?></td>
                                                        <td class="text-center">
                                                            <?php if ($calif['calificacion_nota']): ?>
                                                                <span
                                                                    class="badge badge-calificacion <?= $calif['calificacion_nota'] == 'A' ? 'bg-success' : 'bg-danger' ?>">
                                                                    <?= $calif['calificacion_nota'] ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Pendiente</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td><?= $calif['correo_instructor'] ?? 'No asignado' ?></td>
                                                        <td class="justify-content-center d-flex gap-1">
                                                        
                                                                <?php if ($calif['ruta_trabajo'] && file_exists('../' . $calif['ruta_trabajo'])): ?>
                                                                    <a class="btn btn-info btn-sm" title="Ver mi trabajo"
                                                                        href="<?= '../' . $calif['ruta_trabajo'] ?>"
                                                                        target="_blank">
                                                                        <i class="bi bi-eye"></i>
                                                                    </a>  | 
                                                                <?php endif; ?>

                                                                <?php if ($calif['calificacion_nota'] && $calif['comentario_nota']): ?>
                                                                    <button class="btn btn-primary btn-sm"
                                                                        title="Ver comentario"
                                                                        onclick='verComentario(<?= json_encode($calif, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                                        <i class="bi bi-chat-left-text"></i>
                                                                    </button>
                                                                <?php endif; ?>

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
            $('#tablaCalificaciones').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                },
                pageLength: 10,
                lengthMenu: [5, 10, 20, 50],
                responsive: true,
                autoWidth: true,
                order: [[3, 'desc']]
            });
        });

        function verComentario(calificacion) {
            const tipoCalificacion = calificacion.calificacion_nota === 'A' ? 'Aprobado' : 'Desaprobado';
            const colorIcon = calificacion.calificacion_nota === 'A' ? 'success' : 'error';

            Swal.fire({
                title: `<strong>Calificación: ${calificacion.calificacion_nota}</strong>`,
                icon: colorIcon,
                html: `
          <div class="text-start">
            <p><strong>Trabajo:</strong> ${calificacion.nombre_trabajo}</p>
            <p><strong>Curso:</strong> ${calificacion.nombre_curso || 'N/A'}</p>
            <p><strong>Instructor:</strong> ${calificacion.correo_instructor || 'No asignado'}</p>
            <p><strong>Fecha de entrega:</strong> ${new Date(calificacion.fecha_trabajo).toLocaleDateString('es-ES')}</p>
            <hr>
            <div class="alert alert-${calificacion.calificacion_nota === 'A' ? 'success' : 'danger'}" role="alert">
              <h5><i class="bi bi-chat-square-quote"></i> Comentario del Instructor:</h5>
              <p class="mb-0">${calificacion.comentario_nota}</p>
            </div>
          </div>
        `,
                width: '600px',
                confirmButtonText: 'Cerrar',
                confirmButtonColor: '#007bff'
            });
        }
    </script>
</body>

</html>