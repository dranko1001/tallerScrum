<?php
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

$id     = $_POST['id'] ?? "";
$rol    = $_POST['rol'] ?? "";
$correo = $_POST['correo'] ?? "";
$pass   = $_POST['password'] ?? "";

if(empty($id) || empty($rol) || empty($correo)){
    echo "Datos incompletos";
    exit();
}

switch ($rol) {
    case "admin":
        $tabla = "administrador";
        $campoId = "id_admin";
        $campoCorreo = "correo_admin";
        $campoPass = "pass_admin";
        break;

    case "instructor":
        $tabla = "instructor";
        $campoId = "id_instructor";
        $campoCorreo = "correo_instructor";
        $campoPass = "pass_instructor";
        break;

    case "aprendiz":
        $tabla = "aprendices";
        $campoId = "id_aprendiz";
        $campoCorreo = "correo_aprendiz";
        $campoPass = "pass_aprendiz";
        break;

    default:
        echo "Rol inválido";
        exit();
}

if (!empty($pass)) {
    $passHash = password_hash($pass, PASSWORD_BCRYPT);

    $query = "
        UPDATE $tabla 
        SET $campoCorreo = '$correo',
            $campoPass   = '$passHash'
        WHERE $campoId = $id
    ";
} else {
    $query = "
        UPDATE $tabla 
        SET $campoCorreo = '$correo'
        WHERE $campoId = $id
    ";
}

$resultado = $mysql->efectuarConsulta($query);

echo $resultado ? "OK" : "Error al actualizar";

$mysql->desconectar();
?>
