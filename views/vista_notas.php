<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Notas</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f8f9fa;
    }

    .app-header {
      background-color: #0d6efd;
      color: white;
      padding: 1rem 2rem;
      font-weight: 600;
    }

    .container-notas {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      margin-top: 30px;
      justify-content: center;
    }

    .card-nota {
      flex: 1 1 300px;
      min-width: 250px;
      max-width: 350px;
      background-color: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .card-nota:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      cursor: pointer;
    }

    .titulo-nota {
      font-weight: 700;
      font-size: 1.2rem;
      margin-bottom: 10px;
    }

    .contenido-nota {
      font-size: 0.95rem;
      color: #495057;
      min-height: 60px;
      margin-bottom: 15px;
    }

    .btn-group-nota {
      display: flex;
      gap: 10px;
    }

    .btn-editar {
      background-color: #ffc107;
      color: #212529;
    }

    .btn-editar:hover {
      background-color: #e0a800;
      color: #fff;
    }

    .btn-borrar {
      background-color: #dc3545;
      color: #fff;
    }

    .btn-borrar:hover {
      background-color: #bb2d3b;
    }

    .btn-agregar {
      background-color: #198754;
      color: #fff;
      font-weight: 600;
      margin-bottom: 20px;
    }

    .btn-agregar:hover {
      background-color: #157347;
    }
  </style>
</head>
<body>

  <header class="app-header text-center">
    Mis Notas
  </header>

  <div class="container mt-4">
    <button class="btn btn-agregar w-100 mb-4">
      + Agregar Nota
    </button>

    <div class="container-notas">
      <!-- Nota 1 -->
      <div class="card-nota">
        <div class="titulo-nota">Nota 1</div>
        <div class="contenido-nota">Esta es una nota de ejemplo. Puedes escribir cualquier cosa aquí.</div>
        <div class="btn-group-nota">
          <button class="btn btn-sm btn-editar w-50">Editar</button>
          <button class="btn btn-sm btn-borrar w-50">Borrar</button>
        </div>
      </div>

      <!-- Nota 2 -->
      <div class="card-nota">
        <div class="titulo-nota">Nota 2</div>
        <div class="contenido-nota">Otra nota más. Este contenido puede ser más largo o más corto según lo que necesites.</div>
        <div class="btn-group-nota">
          <button class="btn btn-sm btn-editar w-50">Editar</button>
          <button class="btn btn-sm btn-borrar w-50">Borrar</button>
        </div>
      </div>

      <!-- Nota 3 -->
      <div class="card-nota">
        <div class="titulo-nota">Nota 3</div>
        <div class="contenido-nota">Notas cortas o largas se adaptan al tamaño de la card automáticamente.</div>
        <div class="btn-group-nota">
          <button class="btn btn-sm btn-editar w-50">Editar</button>
          <button class="btn btn-sm btn-borrar w-50">Borrar</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
