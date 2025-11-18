<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once '../models/MySQL.php';
session_start();

if (isset($_POST['email']) && !empty($_POST['email']) && 
    isset($_POST['password']) && !empty($_POST['password'])) {

    $mysql = new MySQL();
    $mysql->conectar();

    // Sanitizacion de datos 
    $correo = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    // Consultas del usuario 
    $resultado = $mysql->efectuarConsulta("SELECT * FROM administrador WHERE correo_admin='".$correo."'");
    $resultadoInstructor= $mysql->efectuarConsulta("SELECT * FROM intructor WHERE correo_instructor='".$correo."'");
    $resultadoAprendiz = $mysql->efectuarConsulta("SELECT * FROM aprendices WHERE correo_aprendiz='".$correo."'");

    $mysql->desconectar();

    $administrador = mysqli_fetch_assoc($resultado);
    $aprendiz = mysqli_fetch_assoc($resultadoAprendiz);
    $instructor = mysqli_fetch_assoc($resultadoInstructor);

    $usuario_encontrado = null;
    $hash_password = '';
    $rol = '';

    // Logica para determinar el usuario encontrado y su rol
    if ($administrador) {
        $usuario_encontrado = $administrador;
        $hash_password = $administrador['password_admin'];
        $rol = 'admin';
    } elseif ($instructor) {
        $usuario_encontrado = $instructor;
        $hash_password = $instructor['password_instructor']; 
        $rol = 'instructor';
    } elseif ($aprendiz) {
        $usuario_encontrado = $aprendiz;
        $hash_password = $aprendiz['password_aprendiz']; 
        $rol = 'aprendiz';
    }

    // Comprobar si se encontro un usuario
    if ($usuario_encontrado) {
        

        if (password_verify($password, $hash_password)) {
            
            $id_key = 'id_'.$rol; 
            
            $_SESSION['id_usuario'] = $usuario_encontrado[$id_key]; 
            $_SESSION['correo_usuario'] = $correo; 
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