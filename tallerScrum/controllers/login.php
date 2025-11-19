<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once '../models/MySQL.php';
session_start();

if (isset($_POST['email']) && !empty($_POST['email']) && 
    isset($_POST['password']) && !empty($_POST['password'])&& 
    isset($_POST['rol']) && !empty($_POST['rol'])) {

    $mysql = new MySQL();
    $mysql->conectar();

    // Sanitizacion de datos 
    $correo = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];
    $rolSeleccionado = $_POST['rol'];

    // Consultas del usuario 
    if ($rolSeleccionado === 'admin') {
        $resultado = $mysql->efectuarConsulta("SELECT * FROM administrador WHERE correo_admin='".$correo."'");
    } elseif ($rolSeleccionado === 'instructor') {
        $resultado = $mysql->efectuarConsulta("SELECT * FROM instructor WHERE correo_instructor='".$correo."'");
    } elseif ($rolSeleccionado === 'aprendiz') {
        $resultado = $mysql->efectuarConsulta("SELECT * FROM aprendices WHERE correo_aprendiz='".$correo."'");
    } else {

        // Rol no valido
        echo "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Rol no válido',
                text: 'Por favor, seleccione un rol válido.'
            }).then(() => {
                window.location = '../views/login.php';
            });
            </script>
        </body>
        </html>";
        exit();
    }

    $usuario_encontrado = mysqli_fetch_assoc($resultado);

    $mysql->desconectar();

    $hash_password = '';
    $rol = $rolSeleccionado; 

    // Logica para determinar el usuario encontrado y su rol
   if ($rol === 'admin') {
    $hash_password = $usuario_encontrado['password_admin'];
    $id_key = 'id_admin';
} elseif ($rol === 'instructor') {
    $hash_password = $usuario_encontrado['password_instructor'];
    $id_key = 'id_instructor';
} elseif ($rol === 'aprendiz') {
    $hash_password = $usuario_encontrado['password_aprendiz'];
    $id_key = 'id_aprendiz';
}

// Comprobar si se encontro un usuario
if ($usuario_encontrado) {

    if (password_verify($password, $hash_password)) {

        $_SESSION['id_'.$rol] = $usuario_encontrado[$id_key];
        $_SESSION['correo_'.$rol] = $correo;
        $_SESSION['rol_usuario'] = $rol;

            echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Bienvenido',
                    text: 'Hola, ".$correo."',
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    window.location = '../index.php';
                });
                </script>
            </body>
            </html>";
            exit();

        } else {
            // Contraseña incorrecta
            echo "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Contraseña incorrecta',
                    text: 'Por favor, inténtalo nuevamente.'
                }).then(() => {
                    window.location = '../views/login.php';
                });
                </script>
            </body>
            </html>";
            exit();
        }

    } else {
         echo "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Usuario no encontrado',
                text: 'Verifica el correo e inténtalo de nuevo.'
            }).then(() => {
                window.location = '../views/login.php';
            });
            </script>
        </body>
        </html>";
        exit();
    }

} else {
    header("Location: ../views/login.php");
    exit();
}
?>