<?php
require_once(__DIR__ . "/../models/MySQL.php");

$mysql = new MySQL();
$conn = $mysql->conectar();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $rol = trim($_POST['rol_usuario']);
    $correo = trim($_POST['correo_usuario']);
    $pass = trim($_POST['password_usuario']);

    $consulta = "SELECT id_usuario FROM usuarios WHERE correo_usuario = '$correo'";
    $resultado = $mysql->efectuarConsulta($consulta);

    if (mysqli_num_rows($resultado) > 0) {
        header("Location: agregar_usuario.php?error=email");
        exit;
    }

    $passHash = password_hash($pass, PASSWORD_DEFAULT);

    $insert = "INSERT INTO usuarios (rol_usuario, correo_usuario, password_usuario)
               VALUES ('$rol', '$correo', '$passHash')";

    if ($mysql->efectuarConsulta($insert)) {
        header("Location: agregar_usuario.php?success=1");
        exit;
    } else {
        header("Location: agregar_usuario.php?error=insert");
        exit;
    }
}

$mysql->desconectar();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Agregar Usuario</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body style="background:#f2f2f2;">

<div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">

  <div class="card shadow p-4" style="width:420px; border-radius:12px;">

      <h3 class="text-center mb-4" style="color:#183c57; font-weight:700;">
        Agregar Usuario
      </h3>

      <form method="post" action="">

        <select name="rol_usuario" class="form-select mb-3" required>
          <option value="" disabled selected>Selecciona un Rol</option>
          <option value="instructor">Instructor</option>
          <option value="aprendiz">Aprendiz</option>
        </select>

        <input type="email" name="correo_usuario" class="form-control mb-2" placeholder="Correo" required>

        <input type="password" name="password_usuario" class="form-control mb-2" placeholder="Contraseña" required>

        <button type="submit" class="btn btn-primary w-100">Guardar</button>

        <a href="usuarios.php" class="btn btn-outline-secondary w-100 mt-2">Volver</a>
      </form>

  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

  const params = new URLSearchParams(window.location.search);

  if (params.has("success")) {
    Swal.fire({
      icon: "success",
      title: "Usuario agregado",
      text: "El usuario fue registrado correctamente.",
      confirmButtonColor: "#183c57"
    });
  }

  if (params.has("error")) {
    let msg = "Ocurrió un error.";

    if (params.get("error") === "email") {
      msg = "El correo ya está registrado.";
    } else if (params.get("error") === "insert") {
      msg = "No se pudo guardar el usuario.";
    }

    Swal.fire({
      icon: "error",
      title: "Error",
      text: msg,
      confirmButtonColor: "#b18757"
    });
  }
});
</script>

</body>
</html>
