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
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Determinar tabla y campos según el rol
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

    // Construir query de actualización
    if (!empty($password)) {
        // Actualizar correo y contraseña
        if (strlen($password) < 6) {
            echo json_encode(['success' => false, 'message' => 'La contraseña debe tener al menos 6 caracteres']);
            exit();
        }
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sqlUpdate = "UPDATE $tabla SET $campoCorreo = '$correo', $campoPassword = '$passwordHash' WHERE $campoId = '$id'";
    } else {
        // Solo actualizar correo
        $sqlUpdate = "UPDATE $tabla SET $campoCorreo = '$correo' WHERE $campoId = '$id'";
    }

    if ($mysql->efectuarConsulta($sqlUpdate)) {
        // Actualizar sesión
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