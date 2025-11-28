<?php
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$query = $mysql->efectuarConsulta("  SELECT id_instructor, correo_instructor AS correo 
    FROM instructor");
$data = [];

while ($fila = $query->fetch_assoc()) {
    $data[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($data);


codigo para agregar aprendices con el curso, desacgtualizado, solo es pegar dentro de donde dice APRENDICES:
     <div class="mb-3">
          <label for="aprendiz" class="form-label">Aprendices (Opcional)</label>
          <input type="text" id="busquedaAprendiz" class="form-control" placeholder="Buscar Aprendiz..." onkeyup="buscarAprendiz(this.value)">
          <input type="hidden" id="aprendices_curso" name="aprendices_curso">
          <div id="sugerenciasAprendiz" style="text-align:left; max-height:200px; overflow-y:auto; margin-top: 5px;"></div>
          <div id="aprendicesSeleccionados" style="margin-top: 10px;"></div>
        </div>
      </form>

      codigo ficha.php, solo muestra la ficha, desactualizado:
      <?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['rol_usuario'])) {
    header("location: ../views/login.php");
    exit();
}
$mysql = new MySQL();
$mysql->conectar();

$rol = $_SESSION['tipo_usuario'];
$nombre = $_SESSION['nombre_usuario'];

// Consulta para obtener las fichas
$resultado = $mysql->efectuarConsulta("
    SELECT 
        f.id_ficha,
        f.nombre_ficha
    FROM fichas f
");
?>

<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Taller Scrum - Fichas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="../css/adminlte.css" />
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <!-- JS Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="layout-fixed sidebar-expand-lg sidebar-open bg-body-tertiary">
    <div class="app-wrapper">
        <!-- Header -->
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
                            <span class="d-none d-md-inline"><?php echo $nombre; ?></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2">
                            <li class="bg-primary text-white text-center rounded-top py-3">
                                <p class="mb-0 fw-bold fs-5"><?php echo $nombre; ?></p>
                                <small><?php echo $rol; ?></small>
                            </li>
                            <li><hr class="dropdown-divider m-0"></li>
                            <li>
                                <a href="./perfilUsuario.php" class="dropdown-item d-flex align-items-center py-2">
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

        <!-- Sidebar -->
        <aside class="app-sidebar verde shadow">
            <div class="sidebar-brand">
                <a href="../index.php" class="brand-link">
                    <span class="title">Taller Scrum</span>
                </a>
            </div>
            <div class="sidebar-wrapper">
                <nav class="mt-2">
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" role="navigation">
                        <li class="nav-item">
                            <a href="../index.php" class="nav-link">
                                <i class="bi bi-speedometer me-2"></i>
                                <span>Inicio</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="app-main">
            <div class="app-content-header">
                <div class="container-fluid">
                    <div class="position-relative">
                        <h3 class="text-center">
                            <i class="bi bi-collection-fill"></i> Fichas
                        </h3>
                        <ol class="breadcrumb position-absolute end-0 top-50 translate-middle-y">
                            <li class="breadcrumb-item"><a href="./fichas.php">Fichas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Lista de fichas</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <?php if ($rol == 'Administrador'): ?>
                                <button type="button" class="btn btn-success" onclick="agregarFicha()">
                                    ➕ Nueva Ficha
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="table-responsive">
                            <table id="tablaFichas" class="table table-striped table-bordered">
                                <thead class="table-info">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre Ficha</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($f = $resultado->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= $f['id_ficha'] ?></td>
                                        <td><?= $f['nombre_ficha'] ?></td>
                                        <td>
                                            <button class="btn btn-info btn-sm" onclick="verDetallesFicha(<?= $f['id_ficha'] ?>)">
                                                <i class="bi bi-eye"></i> Ver
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
        </main>

        <!-- Footer -->
        <footer class="app-footer">
            <strong>Copyright &copy; 2014-2025 <a href="https://adminlte.io" class="text-decoration-none">Taller Scrum</a>.</strong>
            All rights reserved.
        </footer>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <script src="../public/js/adminlte.js"></script>

    <script>
    $(document).ready(function() {
        $('#tablaFichas').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            },
            pageLength: 5,
            lengthMenu: [5, 10, 20, 50],
            responsive: true,
            autoWidth: true
        });
    });

    // Variables globales
    let aprendicesSeleccionados = [];
    let cursosSeleccionados = [];

    // Función para agregar ficha
    function agregarFicha() {
        aprendicesSeleccionados = [];
        cursosSeleccionados = [];
        
        Swal.fire({
            title: 'Crear Nueva Ficha',
            html: `
                <form id="formAgregarFicha" class="text-start">
                    <div class="mb-3">
                        <label for="nombre_ficha" class="form-label">Nombre de la Ficha *</label>
                        <input type="text" class="form-control" id="nombre_ficha" name="nombre_ficha" 
                               placeholder="Ej: ADSO-2024-001" required>
                    </div>
                    
                    <!-- Aprendices -->
                    <div class="mb-3">
                        <label for="aprendiz" class="form-label">Aprendices (Opcional)</label>
                        <input type="text" id="busquedaAprendiz" class="form-control" 
                               placeholder="Buscar por correo..." onkeyup="buscarAprendiz(this.value)">
                        <input type="hidden" id="aprendices_ficha" name="aprendices_ficha">
                        <div id="sugerenciasAprendiz" style="text-align:left; max-height:200px; overflow-y:auto; margin-top: 5px;"></div>
                        <div id="aprendicesSeleccionados" style="margin-top: 10px;"></div>
                    </div>

                    <!-- Cursos -->
                    <div class="mb-3">
                        <label for="curso" class="form-label">Cursos (Opcional)</label>
                        <input type="text" id="busquedaCurso" class="form-control" 
                               placeholder="Buscar curso..." onkeyup="buscarCurso(this.value)">
                        <input type="hidden" id="cursos_ficha" name="cursos_ficha">
                        <div id="sugerenciasCurso" style="text-align:left; max-height:200px; overflow-y:auto; margin-top: 5px;"></div>
                        <div id="cursosSeleccionados" style="margin-top: 10px;"></div>
                    </div>
                </form>
            `,
            width: '600px',
            confirmButtonText: 'Crear Ficha',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            preConfirm: () => {
                const nombre = document.getElementById('nombre_ficha').value.trim();
                const aprendices = document.getElementById('aprendices_ficha').value.trim();
                const cursos = document.getElementById('cursos_ficha').value.trim();

                if (!nombre) {
                    Swal.showValidationMessage('Por favor, ingrese el nombre de la ficha.');
                    return false;
                }

                const formData = new FormData();
                formData.append('nombre_ficha', nombre);
                formData.append('aprendices_ficha', aprendices || '[]');
                formData.append('cursos_ficha', cursos || '[]');
                
                return formData;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '../controllers/agregarFicha.php',
                    type: 'POST',
                    data: result.value,
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
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                        Swal.fire('Error', 'No se pudo crear la ficha.', 'error');
                    }
                });
            }
        });
    }

    // Buscar aprendices
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
            success: function(aprendices) {
                let html = '<ul class="list-group">';
                
                if (aprendices.length > 0) {
                    aprendices.forEach(aprendiz => {
                        if (aprendiz.tiene_ficha == 1) {
                            html += `
                                <li class="list-group-item list-group-item-warning" style="cursor: not-allowed; opacity: 0.6;">
                                    <div>
                                        <span>${aprendiz.correo_aprendiz}</span>
                                        <br>
                                        <small class="text-danger">
                                            <i class="bi bi-exclamation-triangle"></i> 
                                            Ya está en: ${aprendiz.ficha_actual}
                                        </small>
                                    </div>
                                </li>
                            `;
                        } else {
                            html += `
                                <li class="list-group-item list-group-item-action" style="cursor: pointer;" 
                                    onclick="seleccionarAprendiz(${aprendiz.id_aprendiz}, '${aprendiz.correo_aprendiz.replace(/'/g, "\\'")}')">
                                    ${aprendiz.correo_aprendiz}
                                    <span class="badge bg-success float-end">Disponible</span>
                                </li>
                            `;
                        }
                    });
                    html += '</ul>';
                } else {
                    html = '<div class="alert alert-info mb-0"><small>No se encontraron aprendices</small></div>';
                }

                document.getElementById('sugerenciasAprendiz').innerHTML = html;
            },
            error: function() {
                document.getElementById('sugerenciasAprendiz').innerHTML = 
                    '<div class="text-danger">Error al buscar aprendices.</div>';
            }
        });
    }

    function seleccionarAprendiz(id, correo) {
        id = parseInt(id);
        
        if (aprendicesSeleccionados.includes(id)) return;

        aprendicesSeleccionados.push(id);
        document.getElementById('aprendices_ficha').value = JSON.stringify(aprendicesSeleccionados);

        const contenedor = document.getElementById('aprendicesSeleccionados');
        const chip = document.createElement('span');
        chip.style.cssText = `
            display: inline-flex; align-items: center; background-color: #fff3e0;
            color: #e65100; padding: 6px 12px; border-radius: 30px;
            font-size: 14px; border: 1px solid #ffcc80; margin: 0 5px 5px 0;
        `;
        chip.innerHTML = `
            ${correo}
            <span onclick="eliminarAprendiz(${id}, this)" 
                  style="margin-left: 8px; font-size: 16px; cursor: pointer; font-weight: bold;">&times;</span>
        `;

        contenedor.appendChild(chip);
        document.getElementById('sugerenciasAprendiz').innerHTML = '';
        document.getElementById('busquedaAprendiz').value = '';
    }

    function eliminarAprendiz(id, elemento) {
        const index = aprendicesSeleccionados.indexOf(id);
        if (index > -1) aprendicesSeleccionados.splice(index, 1);
        
        document.getElementById('aprendices_ficha').value = JSON.stringify(aprendicesSeleccionados);
        elemento.parentElement.remove();
    }

    // Buscar cursos
    function buscarCurso(texto) {
        if (texto.length < 2) {
            document.getElementById('sugerenciasCurso').innerHTML = '';
            return;
        }

        $.ajax({
            url: '../controllers/buscarCurso.php',
            type: 'POST',
            dataType: 'json',
            data: { query: texto },
            success: function(cursos) {
                let html = '<ul class="list-group">';
                
                if (cursos.length > 0) {
                    cursos.forEach(curso => {
                        html += `
                            <li class="list-group-item list-group-item-action" style="cursor: pointer;" 
                                onclick="seleccionarCurso(${curso.id_curso}, '${curso.nombre_curso.replace(/'/g, "\\'")}')">
                                ${curso.nombre_curso}
                            </li>
                        `;
                    });
                    html += '</ul>';
                } else {
                    html = '<div class="alert alert-info mb-0"><small>No se encontraron cursos</small></div>';
                }

                document.getElementById('sugerenciasCurso').innerHTML = html;
            }
        });
    }

    function seleccionarCurso(id, nombre) {
        id = parseInt(id);
        
        if (cursosSeleccionados.includes(id)) return;

        cursosSeleccionados.push(id);
        document.getElementById('cursos_ficha').value = JSON.stringify(cursosSeleccionados);

        const contenedor = document.getElementById('cursosSeleccionados');
        const chip = document.createElement('span');
        chip.style.cssText = `
            display: inline-flex; align-items: center; background-color: #e3f2fd;
            color: #1565c0; padding: 6px 12px; border-radius: 30px;
            font-size: 14px; border: 1px solid #90caf9; margin: 0 5px 5px 0;
        `;
        chip.innerHTML = `
            ${nombre}
            <span onclick="eliminarCurso(${id}, this)" 
                  style="margin-left: 8px; font-size: 16px; cursor: pointer; font-weight: bold;">&times;</span>
        `;

        contenedor.appendChild(chip);
        document.getElementById('sugerenciasCurso').innerHTML = '';
        document.getElementById('busquedaCurso').value = '';
    }

    function eliminarCurso(id, elemento) {
        const index = cursosSeleccionados.indexOf(id);
        if (index > -1) cursosSeleccionados.splice(index, 1);
        
        document.getElementById('cursos_ficha').value = JSON.stringify(cursosSeleccionados);
        elemento.parentElement.remove();
    }

    // Ver detalles de ficha
    function verDetallesFicha(idFicha) {
        $.ajax({
            url: '../controllers/obtenerDetallesFicha.php',
            type: 'POST',
            data: { id_ficha: idFicha },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    mostrarModalDetalles(response);
                } else {
                    Swal.fire('Error', response.message, 'error');
                }
            },
            error: function() {
                Swal.fire('Error', 'No se pudieron cargar los detalles', 'error');
            }
        });
    }

    function mostrarModalDetalles(data) {
        let htmlAprendices = '<p class="text-muted">No hay aprendices asignados</p>';
        if (data.aprendices.length > 0) {
            htmlAprendices = '<ul class="list-group mb-3">';
            data.aprendices.forEach(a => {
                htmlAprendices += `<li class="list-group-item">${a.correo_aprendiz}</li>`;
            });
            htmlAprendices += '</ul>';
        }

        let htmlCursos = '<p class="text-muted">No hay cursos asignados</p>';
        if (data.cursos.length > 0) {
            htmlCursos = '<ul class="list-group mb-3">';
            data.cursos.forEach(c => {
                htmlCursos += `<li class="list-group-item">${c.nombre_curso}</li>`;
            });
            htmlCursos += '</ul>';
        }

        Swal.fire({
            title: `<i class="bi bi-collection-fill"></i> ${data.ficha.nombre_ficha}`,
            html: `
                <div class="text-start">
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">Aprendices (${data.aprendices.length})</h5>
                        ${htmlAprendices}
                    </div>
                    <div class="mb-3">
                        <h5 class="border-bottom pb-2">Cursos (${data.cursos.length})</h5>
                        ${htmlCursos}
                    </div>
                </div>
            `,
            width: '600px',
            confirmButtonText: 'Cerrar'
        });
    }
    </script>
</body>
</html>