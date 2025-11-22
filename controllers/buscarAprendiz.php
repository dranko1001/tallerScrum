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
    SELECT id_aprendiz, correo_aprendiz 
    FROM aprendices 
    WHERE correo_aprendiz LIKE '%$query%'
    LIMIT 10
";

$resultado = $mysql->efectuarConsulta($sql);

$aprendices = [];
if($resultado){
    while($fila = $resultado->fetch_assoc()){
        $aprendices[] = $fila;
    }
}

echo json_encode($aprendices);
?>