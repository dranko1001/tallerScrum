<?php 
require_once '../models/MySQL.php';

session_start();

$mysql = new MySQL();
$mysql->conectar();
//eliminacion del trabajo

 $id=$_GET['id_trabajo'];
 
        $mysql->efectuarConsulta("delete from trabajos WHERE id_trabajo='$id'");
        $mysql->desconectar();

        header('location:../views/gestionTrabajos.php');
        exit();
?>
