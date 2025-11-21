<?php
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$query = $mysql->efectuarConsulta("  SELECT id_instructor, correo_instructor AS correo 
    FROM instructor");
$data = [];

while ($fila = $query->fetch_assoc()) {
    $data[] = $fila;
}

header('Content-Type: application/json');
echo json_encode($data);
