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
    
    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" crossorigin="anonymous" />
    
    <!-- Third Party Plugins -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    
    <!-- AdminLTE -->
    <link rel="stylesheet" href="../css/adminlte.css" />
    <link rel="stylesheet" href="../css/style.css">
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    
    <!-- SweetAlert2 -->
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
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 mt-2" style="min-width: 230px;">
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
                            <i class="bi bi-folder-fill"></i> Fichas
                        </h3>
                        <ol class="breadcrumb position-absolute end-0 top-50 translate-middle-y">
                            <li class="breadcrumb-item"><a href="./agregarFicha.php">Fichas</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Lista de fichas</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="app-content">
                <div class="container-fluid">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-success" onclick="agregarFicha()">
                                ➕ Ficha
                            </button>
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
                                                <i class="bi bi-eye"></i>
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
            <strong>
                Copyright &copy; 2014-2025&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">Taller Scrum</a>.
            </strong>
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

    // Variables globales para almacenar selecciones
    let cursosSeleccionados = [];
    let aprendicesSeleccionados = [];

    function agregarFicha() {
        // Resetear arreglos
        cursosSeleccionados = [];
        aprendicesSeleccionados = [];
        
        Swal.fire({
            title: 'Agregar Nueva Ficha',
            html: `
                <form id="formAgregarFicha" class="text-start">
                    <div class="mb-3">
                        <label for="nombre_ficha" class="form-label">Número de Ficha *</label>
                        <input type="text" class="form-control" id="nombre_ficha" name="nombre_ficha" 
                               placeholder="Ej: 3064749" required>
                    </div>
                    
                    <!-- Cursos -->
                    <div class="mb-3">
                        <label for="curso" class="form-label">Cursos (Opcional)</label>
                        <input type="text" id="busquedaCurso" class="form-control" 
                               placeholder="Buscar Curso..." onkeyup="buscarCurso(this.value)">
                        <input type="hidden" id="cursos_ficha" name="cursos_ficha">
                        <div id="sugerenciasCurso" style="text-align:left; max-height:200px; overflow-y:auto; margin-top: 5px;"></div>
                        <div id="cursosSeleccionados" style="margin-top: 10px;"></div>
                    </div>

                    <!-- Aprendices -->
                    <div class="mb-3">
                        <label for="aprendiz" class="form-label">Aprendices (Opcional)</label>
                        <input type="text" id="busquedaAprendiz" class="form-control" 
                               placeholder="Buscar Aprendiz..." onkeyup="buscarAprendiz(this.value)">
                        <input type="hidden" id="aprendices_ficha" name="aprendices_ficha">
                        <div id="sugerenciasAprendiz" style="text-align:left; max-height:200px; overflow-y:auto; margin-top: 5px;"></div>
                        <div id="aprendicesSeleccionados" style="margin-top: 10px;"></div>
                    </div>
                </form>
            `,
            width: '600px',
            confirmButtonText: 'Agregar',
            showCancelButton: true,
            cancelButtonText: 'Cancelar',
            focusConfirm: false,
            didOpen: () => {
                document.getElementById('cursosSeleccionados').innerHTML = '';
                document.getElementById('cursos_ficha').value = '';
                document.getElementById('aprendicesSeleccionados').innerHTML = '';
                document.getElementById('aprendices_ficha').value = '';
            },
            preConfirm: () => {
                const nombre = document.getElementById('nombre_ficha').value.trim();
                const cursos = document.getElementById('cursos_ficha').value.trim();
                const aprendices = document.getElementById('aprendices_ficha').value.trim();

                if (!nombre) {
                    Swal.showValidationMessage('Por favor, ingrese el número de ficha.');
                    return false;
                }

                const formData = new FormData();
                formData.append('nombre_ficha', nombre);
                formData.append('cursos_ficha', cursos || '[]');
                formData.append('aprendices_ficha', aprendices || '[]');
                
                return formData;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const formData = result.value;

                $.ajax({
                    url: '../controllers/agregarFicha.php',
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
                            <li class="list-group-item list-group-item-action" 
                                style="cursor: pointer;" 
                                onclick="seleccionarCurso(${curso.id_curso}, '${curso.nombre_curso.replace(/'/g, "\\'")}')">
                                ${curso.nombre_curso}
                            </li>
                        `;
                    });
                    html += '</ul>';
                } else {
                    html += `
                        <div class="alert alert-info mb-0">
                            <small>No se encontró el curso "${texto}"</small>
                        </div>
                    `;
                }

                document.getElementById('sugerenciasCurso').innerHTML = html;
            },
            error: function(xhr, status, error) {
                console.error("❌ Error en la búsqueda:", error);
                document.getElementById('sugerenciasCurso').innerHTML = 
                    '<div class="text-danger ps-2">Error al buscar cursos.</div>';
            }
        });
    }

    function seleccionarCurso(id, nombre) {
        id = parseInt(id);
        
        if (cursosSeleccionados.includes(id)) {
            document.getElementById('sugerenciasCurso').innerHTML = '';
            document.getElementById('busquedaCurso').value = '';
            return;
        }

        cursosSeleccionados.push(id);
        document.getElementById('cursos_ficha').value = JSON.stringify(cursosSeleccionados);

        const contenedor = document.getElementById('cursosSeleccionados');
        const chip = document.createElement('span');
        
        chip.style.cssText = `
            display: inline-flex;
            align-items: center;
            background-color: #d4edda;
            color: #155724;
            padding: 6px 12px;
            border-radius: 30px;
            font-size: 14px;
            border: 1px solid #c3e6cb;
            margin-right: 5px;
            margin-bottom: 5px;
        `;
        chip.innerHTML = `
            ${nombre}
            <span 
                onclick="eliminarCurso(${id}, this)" 
                style="
                    margin-left: 8px;
                    font-size: 16px;
                    cursor: pointer;
                    font-weight: bold;
                "
            >&times;</span>
        `;

        contenedor.appendChild(chip);
        document.getElementById('sugerenciasCurso').innerHTML = '';
        document.getElementById('busquedaCurso').value = '';
    }

    function eliminarCurso(id, elemento) {
        const index = cursosSeleccionados.indexOf(id);
        if (index > -1) {
            cursosSeleccionados.splice(index, 1);
        }
        
        document.getElementById('cursos_ficha').value = JSON.stringify(cursosSeleccionados);
        elemento.parentElement.remove();
    }

    // Buscar aprendices disponibles (sin ficha)
    function buscarAprendiz(texto) {
        if (texto.length < 2) {
            document.getElementById('sugerenciasAprendiz').innerHTML = '';
            return;
        }

        $.ajax({
            url: '../controllers/buscarAprendizSinFicha.php',
            type: 'POST',
            dataType: 'json',
            data: { query: texto },
            success: function(aprendices) {
                let html = '<ul class="list-group">';
                
                if (aprendices.length > 0) {
                    aprendices.forEach(aprendiz => {
                        if (aprendiz.tiene_ficha == 1) {
                            html += `
                                <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center" 
                                    style="cursor: not-allowed; opacity: 0.6;">
                                    <div>
                                        <span>${aprendiz.correo_aprendiz}</span>
                                        <br>
                                        <small class="text-danger">
                                            <i class="bi bi-exclamation-triangle"></i> 
                                            Ya está en ficha: ${aprendiz.ficha_actual}
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
            error: function(xhr, status, error) {
                console.error("❌ Error en la búsqueda:", error);
                document.getElementById('sugerenciasAprendiz').innerHTML = 
                    '<div class="text-danger ps-2">Error al buscar aprendices.</div>';
            }
        });
    }

    function seleccionarAprendiz(id, correo) {
        id = parseInt(id);
        
        if (aprendicesSeleccionados.includes(id)) {
            document.getElementById('sugerenciasAprendiz').innerHTML = '';
            document.getElementById('busquedaAprendiz').value = '';
            return;
        }

        aprendicesSeleccionados.push(id);
        document.getElementById('aprendices_ficha').value = JSON.stringify(aprendicesSeleccionados);

        const contenedor = document.getElementById('aprendicesSeleccionados');
        const chip = document.createElement('span');

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
        
        document.getElementById('aprendices_ficha').value = JSON.stringify(aprendicesSeleccionados);
        elemento.parentElement.remove();
    }

    // Ver detalles de la ficha
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
            error: function(xhr, status, error) {
                console.error("Error al obtener detalles:", error);
                Swal.fire('Error', 'Hubo un problema al cargar los detalles', 'error');
            }
        });
    }

    function mostrarModalDetalles(data) {
        // HTML de cursos
        let htmlCursos = '';
        if (data.cursos.length > 0) {
            htmlCursos = '<ul class="list-group mb-3">';
            data.cursos.forEach(curso => {
                htmlCursos += `
                    <li class="list-group-item d-flex align-items-center">
                        <span>${curso.nombre_curso}</span>
                    </li>
                `;
            });
            htmlCursos += '</ul>';
        } else {
            htmlCursos = '<p class="text-muted">No hay cursos asignados</p>';
        }

        // HTML de aprendices
        let htmlAprendices = '';
        if (data.aprendices.length > 0) {
            htmlAprendices = '<ul class="list-group mb-3">';
            data.aprendices.forEach(aprendiz => {
                htmlAprendices += `
                    <li class="list-group-item d-flex align-items-center">
                        <span>${aprendiz.correo_aprendiz}</span>
                    </li>
                `;
            });
            htmlAprendices += '</ul>';
        } else {
            htmlAprendices = '<p class="text-muted">No hay aprendices inscritos</p>';
        }

        Swal.fire({
            title: `<i class="bi bi-folder-fill"></i> Ficha ${data.ficha.nombre_ficha}`,
            html: `
                <div class="text-start">
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2">
                            Cursos (${data.cursos.length})
                        </h5>
                        ${htmlCursos}
                    </div>
                    
                    <div class="mb-3">
                        <h5 class="border-bottom pb-2">
                            Aprendices (${data.aprendices.length})
                        </h5>
                        ${htmlAprendices}
                    </div>
                </div>
            `,
            width: '600px',
            confirmButtonText: 'Cerrar',
            confirmButtonColor: '#6c757d'
        });
    }
    </script>
</body>
</html>