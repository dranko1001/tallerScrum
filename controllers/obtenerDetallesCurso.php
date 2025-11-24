<?php
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$id_curso = $_POST['id_curso'] ?? null;

if (!$id_curso) {
    echo json_encode(["success" => false, "message" => "ID de curso no proporcionado"]);
    exit;
}

// Información del curso
$sqlCurso = "SELECT id_curso, nombre_curso FROM cursos WHERE id_curso = $id_curso";
$resultadoCurso = $mysql->efectuarConsulta($sqlCurso);
$curso = $resultadoCurso->fetch_assoc();

if (!$curso) {
    echo json_encode(["success" => false, "message" => "Curso no encontrado"]);
    exit;
}

// Instructores asociados al curso
$sqlInstructores = "
    SELECT i.id_instructor, i.correo_instructor 
    FROM instructor i
    INNER JOIN instructor_has_cursos ihc ON i.id_instructor = ihc.instructor_id_usuario
    WHERE ihc.cursos_id_curso = $id_curso
    ORDER BY i.correo_instructor ASC
";
$resultadoInstructores = $mysql->efectuarConsulta($sqlInstructores);
$instructores = [];
if ($resultadoInstructores) {
    while ($fila = $resultadoInstructores->fetch_assoc()) {
        $instructores[] = $fila;
    }
}

// Devuelve solo la info del curso con sus instructores, en un commit anterior esta como devuelve con aprendices
echo json_encode([
    "success" => true,
    "curso" => $curso,
    "instructores" => $instructores
]);
?>