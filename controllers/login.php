<?php 
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once '../models/MySQL.php';
session_start();

if (
    isset($_POST['email']) && !empty($_POST['email']) && 
    isset($_POST['password']) && !empty($_POST['password'])
) {

    $mysql = new MySQL();
    $conexion = $mysql->conectar();

    // Sanitización
    $correo = trim($_POST['email']);
    $password = $_POST['password'];

    // Consulta preparada
    $stmt = $conexion->prepare("SELECT id_usuario, rol_usuario, correo_usuario, password_usuario 
                                FROM usuarios 
                                WHERE correo_usuario = ?");
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    $usuario = $resultado->fetch_assoc();

    $stmt->close();
    $mysql->desconectar();

    if ($usuario) {

        if (password_verify($password, $usuario['password_usuario'])) {

            // Guardar sesión
            $_SESSION['id_usuario']   = $usuario['id_usuario'];
            $_SESSION['rol_usuario']  = $usuario['rol_usuario'];
            $_SESSION['correo']       = $usuario['correo_usuario'];

            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Bienvenido',
                        text: 'Ingreso exitoso',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location = '../index.php';
                    });
                });
            </script>";

            exit();

        } else {
            echo "
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Contraseña incorrecta',
                        text: 'Intenta nuevamente.'
                    }).then(() => {
                        window.location = '../views/login.php';
                    });
                });
            </script>";
            exit();
        }

    } else {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Usuario no encontrado',
                    text: 'Verifica el correo.'
                }).then(() => {
                    window.location = '../views/login.php';
                });
            });
        </script>";
        exit();
    }

} else {
    header("Location: ../views/login.php");
    exit();
}
?>
