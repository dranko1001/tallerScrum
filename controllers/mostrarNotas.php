<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../models/MySQL.php';

$mysql = new MySQL();
$mysql->conectar();

// Consulta
$sql = "SELECT id_trabajo, nombre_trabajo, fecha_trabajo FROM trabajos";
$resultado = $mysql->efectuarConsulta($sql);

// Inicializamos array
$trabajos = array();

if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $trabajos[] = $fila;
    }
}

// Retornamos JSON
echo json_encode($trabajos, JSON_UNESCAPED_UNICODE);

$mysql->desconectar();
