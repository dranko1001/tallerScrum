<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

require_once '../models/MySQL.php';
session_start();

if (!isset($_POST['id_usuario']) || !isset($_POST['rol_usuario'])) {
    echo "ERROR: Datos insuficientes.";
    exit();
}

$mysql = new MySQL();
$mysql->conectar();

$id = intval($_POST['id_usuario']);
$rol = $_POST['rol_usuario'];
$nuevoCorreo = trim($_POST['correo_usuario']);
$nuevoRol = $_POST['nuevo_rol'] ?? $rol;
$nuevaPass = trim($_POST['password_usuario']);

switch ($rol) {
    case 'admin':
        $tabla = 'administrador';
        $campoCorreo = 'correo_admin';
        $campoPass = 'password_admin';
        $campoId = 'id_admin';
        break;

    case 'instructor':
        $tabla = 'instructor';
        $campoCorreo = 'correo_instructor';
        $campoPass = 'password_instructor';
        $campoId = 'id_instructor';
        break;

    case 'aprendiz':
        $tabla = 'aprendices';
        $campoCorreo = 'correo_aprendiz';
        $campoPass = 'password_aprendiz';
        $campoId = 'id_aprendiz';
        break;

    default:
        exit("Rol inválido");
}

$actualizaPass = "";
if (!empty($nuevaPass)) {
    $hash = password_hash($nuevaPass, PASSWORD_DEFAULT);
    $actualizaPass = ", $campoPass = '$hash'";
}

$sql = "
    UPDATE $tabla 
    SET $campoCorreo = '$nuevoCorreo'
    $actualizaPass
    WHERE $campoId = $id
";

$mysql->efectuarConsulta($sql);
$mysql->desconectar();

$_SESSION['correo_'.$rol] = $nuevoCorreo;

echo "OK";
exit();
?>
