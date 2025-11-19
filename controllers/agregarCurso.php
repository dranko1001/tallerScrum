<?php
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$nombre = $_POST['nombre_curso'] ?? null;
$instructor = $_POST['id_instructor'] ?? null;

if(!$nombre || !$instructor){
    echo json_encode(["success"=>false, "message"=>"Datos incompletos"]);
    exit;
}

$sql = "
    INSERT INTO cursos (nombre_curso, id_instructor)
    VALUES ('$nombre', '$instructor')
";

if($mysql->efectuarConsulta($sql)){
    echo json_encode(["success"=>true, "message"=>"el curso fue agregado correctamente"]);
}else{
    echo json_encode(["success"=>false, "message"=>"Error al insertar el curso "]);
}
