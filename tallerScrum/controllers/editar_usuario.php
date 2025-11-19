<?php
require_once '../config/conexion.php';
require_once '../models/editarusuario.php';

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'instructor'; 
$tabla = ($tipo === 'aprendiz') ? 'aprendices' : 'instructor';

$editarUsuario = new EditarUsuario($conexion, $tabla);

if(isset($_GET['id'])){
    $editarUsuario->setId($_GET['id']);
    $usuario = $editarUsuario->getUsuario();
} else {
    echo "No se especificó el usuario.";
    exit;
}


if(isset($_POST['actualizar'])){
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $rol = $_POST['rol'];

    $editarUsuario->setId($_POST['id']);
    if($editarUsuario->actualizarUsuario($nombre, $email, $rol)){
        header("Location: lista_usuarios.php?tipo={$tipo}"); 
        exit;
    } else {
        echo "Error al actualizar el usuario.";
    }
}
?>
