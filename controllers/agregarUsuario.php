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

    if (empty($_POST['rol']) || empty($_POST['correo']) || empty($_POST['password'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
        exit();
    }

    $mysql = new MySQL();
    $mysql->conectar();

    $rol = trim($_POST['rol']);
    $correo = htmlspecialchars(trim($_POST['correo']), ENT_QUOTES, 'UTF-8');
    $password = $_POST['password'];

    // Validar rol
    if (!in_array($rol, ['admin', 'instructor', 'aprendiz'])) {
        echo json_encode(['success' => false, 'message' => 'Rol inválido']);
        exit();
    }

    // Hashear contraseña
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // Determinar tabla y campos según el rol
    if ($rol === 'admin') {
        $tabla = 'administrador';
        $campoId = 'id_admin';
        $campoCorreo = 'correo_admin';
        $campoPassword = 'password_admin';
        $rolTexto = 'Administrador';
    } elseif ($rol === 'instructor') {
        $tabla = 'instructor';
        $campoId = 'id_instructor';
        $campoCorreo = 'correo_instructor';
        $campoPassword = 'password_instructor';
        $rolTexto = 'Instructor';
    } else {
        $tabla = 'aprendices';
        $campoId = 'id_aprendiz';
        $campoCorreo = 'correo_aprendiz';
        $campoPassword = 'password_aprendiz';
        $rolTexto = 'Aprendiz';
    }

    // Verificar si el correo ya existe en cualquier tabla
    $verificarAdmin = $mysql->efectuarConsulta("SELECT id_admin FROM administrador WHERE correo_admin = '$correo'");
    $verificarInstructor = $mysql->efectuarConsulta("SELECT id_instructor FROM instructor WHERE correo_instructor = '$correo'");
    $verificarAprendiz = $mysql->efectuarConsulta("SELECT id_aprendiz FROM aprendices WHERE correo_aprendiz = '$correo'");

    if (
        mysqli_num_rows($verificarAdmin) > 0 ||
        mysqli_num_rows($verificarInstructor) > 0 ||
        mysqli_num_rows($verificarAprendiz) > 0
    ) {
        echo json_encode(['success' => false, 'message' => 'El correo ya está registrado']);
        exit();
    }

    // Insertar nuevo usuario
    $sqlInsert = "
        INSERT INTO $tabla (rol_usuario, $campoCorreo, $campoPassword)
        VALUES ('$rolTexto', '$correo', '$passwordHash')
    ";

    if ($mysql->efectuarConsulta($sqlInsert)) {
        echo json_encode(['success' => true, 'message' => 'Usuario agregado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar el usuario']);
    }

    $mysql->desconectar();

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>