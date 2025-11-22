<?php
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$id_curso = $_POST['id_curso'] ?? null;

if(!$id_curso){
    echo json_encode(["success"=>false, "message"=>"ID de curso no proporcionado"]);
    exit;
}

//info del curso
$sqlCurso = "SELECT id_curso, nombre_curso FROM cursos WHERE id_curso = $id_curso";
$resultadoCurso = $mysql->efectuarConsulta($sqlCurso);
$curso = $resultadoCurso->fetch_assoc();

if(!$curso){
    echo json_encode(["success"=>false, "message"=>"Curso no encontrado"]);
    exit;
}

// instructores asociados al curso
$sqlInstructores = "
    SELECT i.id_instructor, i.correo_instructor 
    FROM instructor i
    INNER JOIN instructor_has_cursos ihc ON i.id_instructor = ihc.instructor_id_usuario
    WHERE ihc.cursos_id_curso = $id_curso
";
$resultadoInstructores = $mysql->efectuarConsulta($sqlInstructores);
$instructores = [];
if($resultadoInstructores){
    while($fila = $resultadoInstructores->fetch_assoc()){
        $instructores[] = $fila;
    }
}

// aprendices asociados al curso
$sqlAprendices = "
    SELECT a.id_aprendiz, a.correo_aprendiz 
    FROM aprendices a
    INNER JOIN cursos_has_aprendices cha ON a.id_aprendiz = cha.aprendices_id_aprendiz
    WHERE cha.cursos_id_curso = $id_curso
";
$resultadoAprendices = $mysql->efectuarConsulta($sqlAprendices);
$aprendices = [];
if($resultadoAprendices){
    while($fila = $resultadoAprendices->fetch_assoc()){
        $aprendices[] = $fila;
    }
}

// devuelve toda la info
echo json_encode([
    "success" => true,
    "curso" => $curso,
    "instructores" => $instructores,
    "aprendices" => $aprendices
]);
?>