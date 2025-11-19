<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Notas | Sistema</title>

  <link rel="stylesheet" href="../css/adminlte.css">
  <link rel="stylesheet" href="../css/style.css">

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css" media="print" onload="this.media='all'">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css">

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        </ul>

        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle text-dark fw-semibold" data-bs-toggle="dropdown">
              <img src="../views/img/avatars/avatar.jpg" class="avatar img-fluid rounded me-1">
              <span>Usuario</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
              <li><a class="dropdown-item text-danger" id="btnCerrarSesion">Cerrar sesión</a></li>
            </ul>
          </li>
        </ul>

      </div>
    </nav>

    <aside class="app-sidebar verde shadow">
      <div class="sidebar-brand">
        <a href="#" class="brand-link">
          <span class="title">
            <img src="../public/media/senalibrary icon.png" width="30" height="40"> SenaLibrary
          </span>
        </a>
      </div>

      <div class="sidebar-wrapper">
        <nav class="mt-2">
          <ul class="nav sidebar-menu flex-column">
            <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-speedometer"></i> Dashboard</a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-book"></i> Libros</a></li>
            <li class="nav-item"><a class="nav-link" href="#"><i class="bi bi-people"></i> Usuarios</a></li>
            <li class="nav-item"><a class="nav-link active" href="#"><i class="bi bi-journal-text"></i> Notas</a></li>
          </ul>
        </nav>
      </div>
    </aside>

    <main class="app-main">
      <div class="app-content">
        <div class="container-fluid">

          <h1 class="h3 mb-3 mt-3">Talleres</h1>

          <div class="card shadow">
            <div class="card-body">

              <table class="table table-hover table-striped table-bordered text-center align-middle shadow-sm">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Nombre Trabajo</th>
                    <th>Fecha Trabajo</th>
                    <th>Opción</th>
                  </tr>
                </thead>
                <tbody id="datosReservas"></tbody>
              </table>


            </div>
          </div>

        </div>
      </div>
    </main>

    <footer class="footer bg-light p-2">
      <div class="container-fluid">
        <div class="text-muted text-center">
          Sistema de Notas &copy;
        </div>
      </div>
    </footer>

  </div>

  <script src="../public/js/adminlte.js"></script>
  <script src="../public/js/adminlte.min.js"></script>
  <script src="../public/js/verNotas.js"></script>

</body>

</html>