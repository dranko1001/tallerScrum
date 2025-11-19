<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

// Consulta
$sql = "SELECT id_nota, calificacion_nota, comentario_nota FROM notas";
$resultado = $mysql->efectuarConsulta($sql);

// Inicializamos arrayx 
$notas = array();

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $notas[] = $fila;
    }
}

// Retornamos JSON
echo json_encode($notas, JSON_UNESCAPED_UNICODE);

$mysql->desconectar();
