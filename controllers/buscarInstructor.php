<?php
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$query = $_POST['query'] ?? '';

if(strlen($query) < 2){
    echo json_encode([]);
    exit;
}

$sql = "
    SELECT id_instructor, correo_instructor 
    FROM instructor 
    WHERE correo_instructor LIKE '%$query%'
    LIMIT 10
";

$resultado = $mysql->efectuarConsulta($sql);

$instructores = [];
if($resultado){
    while($fila = $resultado->fetch_assoc()){
        $instructores[] = $fila;
    }
}

echo json_encode($instructores);
?>