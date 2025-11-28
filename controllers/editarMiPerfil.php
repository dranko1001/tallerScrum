<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Content-Type: application/json; charset=utf-8");

require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['rol_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No hay sesión activa']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['correo'])) {
        echo json_encode(['success' => false, 'message' => 'El correo es obligatorio']);
        exit();
    }

    $mysql = new MySQL();
    $mysql->conectar();

    $rol = $_SESSION['rol_usuario'];
    $correo = htmlspecialchars(trim($_POST['correo']), ENT_QUOTES, 'UTF-8');
    $passwordActual = isset($_POST['password_actual']) ? trim($_POST['password_actual']) : '';
    $passwordNueva = isset($_POST['password_nueva']) ? trim($_POST['password_nueva']) : '';

    if ($rol === 'admin') {
        $tabla = 'administrador';
        $campoId = 'id_admin';
        $campoCorreo = 'correo_admin';
        $campoPassword = 'password_admin';
        $id = $_SESSION['id_admin'];
    } elseif ($rol === 'instructor') {
        $tabla = 'instructor';
        $campoId = 'id_instructor';
        $campoCorreo = 'correo_instructor';
        $campoPassword = 'password_instructor';
        $id = $_SESSION['id_instructor'];
    } elseif ($rol === 'aprendiz') {
        $tabla = 'aprendices';
        $campoId = 'id_aprendiz';
        $campoCorreo = 'correo_aprendiz';
        $campoPassword = 'password_aprendiz';
        $id = $_SESSION['id_aprendiz'];
    } else {
        echo json_encode(['success' => false, 'message' => 'Rol no válido']);
        exit();
    }

    if (!empty($passwordNueva)) {

        if (empty($passwordActual)) {
            echo json_encode(['success' => false, 'message' => 'Debe ingresar su contraseña actual']);
            exit();
        }

        if (strlen($passwordNueva) < 6) {
            echo json_encode(['success' => false, 'message' => 'La nueva contraseña debe tener al menos 6 caracteres']);
            exit();
        }

        $consultaPassword = $mysql->efectuarConsulta("SELECT $campoPassword FROM $tabla WHERE $campoId = '$id'");
        $userData = mysqli_fetch_assoc($consultaPassword);

        if (!password_verify($passwordActual, $userData[$campoPassword])) {
            echo json_encode(['success' => false, 'message' => 'La contraseña actual es incorrecta']);
            exit();
        }

        $passwordHash = password_hash($passwordNueva, PASSWORD_DEFAULT);
        $sqlUpdate = "UPDATE $tabla SET $campoCorreo = '$correo', $campoPassword = '$passwordHash' WHERE $campoId = '$id'";
    } else {
        $sqlUpdate = "UPDATE $tabla SET $campoCorreo = '$correo' WHERE $campoId = '$id'";
    }

    if ($mysql->efectuarConsulta($sqlUpdate)) {
        $_SESSION['correo_' . $rol] = $correo;
        echo json_encode(['success' => true, 'message' => 'Perfil actualizado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al actualizar el perfil']);
    }

    $mysql->desconectar();

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>