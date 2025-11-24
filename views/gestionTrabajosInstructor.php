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

// Verificar que sea instructor
if ($rol !== 'instructor') {
    header("location: ../index.php");
    exit();
}

$idInstructor = $_SESSION['id_instructor'];

// Consulta para obtener los cursos del instructor
$consultaCursosInstructor = $mysql->efectuarConsulta("
    SELECT c.id_curso, c.nombre_curso 
    FROM cursos c
    INNER JOIN instructor_has_cursos ihc ON c.id_curso = ihc.cursos_id_curso
    WHERE ihc.instructor_id_usuario = '$idInstructor'
");

// Filtro por curso
$filtro_curso = isset($_GET['curso']) ? intval($_GET['curso']) : 0;

// Consulta para obtener los trabajos de los aprendices en los cursos del instructor
$sqlTrabajos = "
    SELECT 
        t.id_trabajo,
        t.nombre_trabajo,
        t.fecha_trabajo,
        t.ruta_trabajo,
        a.correo_aprendiz,
        a.id_aprendiz,
        c.nombre_curso,
        c.id_curso,
        n.calificacion_nota,
        n.comentario_nota
    FROM trabajos t
    INNER JOIN aprendices a ON t.aprendices_id_aprendiz = a.id_aprendiz
    INNER JOIN cursos c ON t.cursos_id_curso = c.id_curso
    INNER JOIN instructor_has_cursos ihc ON c.id_curso = ihc.cursos_id_curso
    LEFT JOIN notas n ON t.id_trabajo = n.trabajos_id_trabajo
    WHERE ihc.instructor_id_usuario = '$idInstructor'
";

if ($filtro_curso > 0) {
    $sqlTrabajos .= " AND c.id_curso = '$filtro_curso'";
}

$sqlTrabajos .= " ORDER BY t.fecha_trabajo DESC";

$resultadoTrabajos = $mysql->efectuarConsulta($sqlTrabajos);
?>

<!doctype html>
<html lang="es">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>EduSena - Calificar Trabajos</title>

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

        .card {
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .badge-calificacion {
            font-size: 1.1rem;
            padding: 8px 15px;
            font-weight: 600;
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
                        <?php if ($rol == 'instructor'): ?>
                            <li class="nav-item">
                                <a href="./gestionTrabajosInstructor.php" class="nav-link active">
                                    <i class="nav-icon bi bi-check2-square me-2"></i>
                                    <span>Calificar Trabajos</span>
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
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-header bg-primary text-white">
                                    <h3 class="card-title mb-0">
                                        <i class="bi bi-clipboard-check me-2"></i>Gestión de Calificaciones
                                    </h3>
                                </div>

                                <div class="card-body">
                                    <!-- Filtro por curso -->
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Filtrar por Curso:</label>
                                            <select id="filtroCurso" class="form-select" onchange="filtrarPorCurso()">
                                                <option value="0" <?= $filtro_curso == 0 ? 'selected' : '' ?>>Todos los
                                                    cursos</option>
                                                <?php
                                                mysqli_data_seek($consultaCursosInstructor, 0);
                                                while ($curso = $consultaCursosInstructor->fetch_assoc()):
                                                    ?>
                                                    <option value="<?= $curso['id_curso'] ?>"
                                                        <?= $filtro_curso == $curso['id_curso'] ? 'selected' : '' ?>>
                                                        <?= $curso['nombre_curso'] ?>
                                                    </option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="table-responsive">
                                        <table id="tablaTrabajos" class="table table-striped table-bordered"
                                            width="100%">
                                            <thead class="table-success">
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Trabajo</th>
                                                    <th>Aprendiz</th>
                                                    <th>Curso</th>
                                                    <th>Fecha Límite</th>
                                                    <th>Calificación</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($trabajo = $resultadoTrabajos->fetch_assoc()): ?>
                                                    <tr id="trabajo-<?= $trabajo['id_trabajo'] ?>">
                                                        <td><?= $trabajo['id_trabajo'] ?></td>
                                                        <td><?= $trabajo['nombre_trabajo'] ?></td>
                                                        <td><?= $trabajo['correo_aprendiz'] ?></td>
                                                        <td><?= $trabajo['nombre_curso'] ?></td>
                                                        <td><?= date('d/m/Y', strtotime($trabajo['fecha_trabajo'])) ?></td>
                                                        <td class="text-center calificacion-cell">
                                                            <?php if ($trabajo['calificacion_nota']): ?>
                                                                <span
                                                                    class="badge badge-calificacion <?= $trabajo['calificacion_nota'] == 'A' ? 'bg-success' : 'bg-danger' ?>">
                                                                    <?= $trabajo['calificacion_nota'] ?>
                                                                </span>
                                                            <?php else: ?>
                                                                <span class="badge bg-secondary">Sin calificar</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="btn-group" role="group">
                                                                <?php if ($trabajo['ruta_trabajo'] && file_exists('../' . $trabajo['ruta_trabajo'])): ?>
                                                                    <a class="btn btn-info btn-sm" title="Ver trabajo"
                                                                        href="<?= '../' . $trabajo['ruta_trabajo'] ?>"
                                                                        target="_blank">
                                                                        <i class="bi bi-eye"></i>
                                                                    </a>
                                                                <?php endif; ?>

                                                                <button class="btn btn-warning btn-sm" title="Calificar"
                                                                    onclick='calificarTrabajo(<?= json_encode($trabajo) ?>)'>
                                                                    <i class="bi bi-pencil-square"></i>
                                                                </button>
                                                            </div>
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
            $('#tablaTrabajos').DataTable({
                language: {
                    url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                },
                pageLength: 10,
                lengthMenu: [5, 10, 20, 50],
                responsive: true,
                autoWidth: true,
                order: [[4, 'desc']]
            });
        });

        function filtrarPorCurso() {
            const curso = document.getElementById('filtroCurso').value;
            window.location.href = 'gestionTrabajosInstructor.php?curso=' + curso;
        }

        function calificarTrabajo(trabajo) {
            const calificacionActual = trabajo.calificacion_nota || '';
            const comentarioActual = trabajo.comentario_nota || '';

            Swal.fire({
                title: 'Calificar Trabajo',
                html: `
          <div class="text-start">
            <p><strong>Trabajo:</strong> ${trabajo.nombre_trabajo}</p>
            <p><strong>Aprendiz:</strong> ${trabajo.correo_aprendiz}</p>
            <p><strong>Curso:</strong> ${trabajo.nombre_curso}</p>
            <hr>
            
            <form id="formCalificar" class="text-start">
              <div class="mb-3">
                <label class="form-label fw-bold">Calificación:</label>
                <select class="form-select" id="calificacion" required>
                  <option value="" disabled ${!calificacionActual ? 'selected' : ''}>Seleccione...</option>
                  <option value="A" ${calificacionActual == 'A' ? 'selected' : ''}>A - Aprobado</option>
                  <option value="D" ${calificacionActual == 'D' ? 'selected' : ''}>D - Desaprobado</option>
                </select>
              </div>
              
              <div class="mb-3">
                <label class="form-label fw-bold">Comentario:</label>
                <textarea class="form-control" id="comentario" rows="4" 
                          placeholder="Escriba un comentario explicativo..." required>${comentarioActual}</textarea>
              </div>
            </form>
          </div>
        `,
                width: '600px',
                confirmButtonText: calificacionActual ? 'Actualizar Calificación' : 'Guardar Calificación',
                showCancelButton: true,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                preConfirm: () => {
                    const calificacion = document.getElementById('calificacion').value;
                    const comentario = document.getElementById('comentario').value.trim();

                    if (!calificacion || !comentario) {
                        Swal.showValidationMessage('Debe completar todos los campos');
                        return false;
                    }

                    return {
                        id_trabajo: trabajo.id_trabajo,
                        calificacion: calificacion,
                        comentario: comentario
                    };
                }
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '../controllers/calificarTrabajo.php',
                        type: 'POST',
                        data: result.value,
                        dataType: 'json',
                        success: function (response) {
                            if (response.success) {
                                // Actualizar el badge dinámicamente SIN recargar la página
                                const fila = $('#trabajo-' + result.value.id_trabajo);
                                const calificacionCell = fila.find('.calificacion-cell');

                                const badgeClass = result.value.calificacion === 'A' ? 'bg-success' : 'bg-danger';
                                const nuevoBadge = `<span class="badge badge-calificacion ${badgeClass}">${result.value.calificacion}</span>`;

                                calificacionCell.html(nuevoBadge);

                                Swal.fire({
                                    icon: 'success',
                                    title: '¡Éxito!',
                                    text: response.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
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