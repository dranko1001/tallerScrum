<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Content-Type: application/json; charset=utf-8");

require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['rol_usuario']) || $_SESSION['rol_usuario'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['id']) || empty($_POST['tipo_usuario'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan datos para eliminar']);
        exit();
    }

    $mysql = new MySQL();
    $mysql->conectar();

    $id = intval($_POST['id']);
    $tipoUsuario = trim($_POST['tipo_usuario']);

    // Determinar tabla y campo según el tipo de usuario
    if ($tipoUsuario === 'admin') {
        $tabla = 'administrador';
        $campoId = 'id_admin';
    } elseif ($tipoUsuario === 'instructor') {
        $tabla = 'instructor';
        $campoId = 'id_instructor';
    } elseif ($tipoUsuario === 'aprendiz') {
        $tabla = 'aprendices';
        $campoId = 'id_aprendiz';
    } else {
        echo json_encode(['success' => false, 'message' => 'Tipo de usuario inválido']);
        exit();
    }

    // Evitar que el admin elimine su propia cuenta
    if ($tipoUsuario === 'admin' && $id == $_SESSION['id_admin']) {
        echo json_encode(['success' => false, 'message' => 'No puedes eliminar tu propia cuenta']);
        exit();
    }

    // Eliminar usuario
    $sqlDelete = "DELETE FROM $tabla WHERE $campoId = '$id'";

    if ($mysql->efectuarConsulta($sqlDelete)) {
        echo json_encode(['success' => true, 'message' => 'Usuario eliminado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al eliminar el usuario']);
    }

    $mysql->desconectar();

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>