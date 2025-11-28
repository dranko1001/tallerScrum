<?php
require_once '../models/MySQL.php';

header('Content-Type: application/json; charset=utf-8');

$mysql = new MySQL();
$mysql->conectar();

$id_ficha = $_POST['id_ficha'] ?? null;

if (!$id_ficha) {
    echo json_encode(['success' => false, 'message' => 'ID de ficha no proporcionado']);
    exit;
}

$id_ficha = intval($id_ficha);

// Obtener datos de la ficha
$sqlFicha = "SELECT id_ficha, nombre_ficha FROM fichas WHERE id_ficha = $id_ficha";
$resultadoFicha = $mysql->efectuarConsulta($sqlFicha);

if (!$resultadoFicha || $resultadoFicha->num_rows == 0) {
    echo json_encode(['success' => false, 'message' => 'Ficha no encontrada']);
    exit;
}

$ficha = $resultadoFicha->fetch_assoc();

// Obtener cursos de la ficha
$sqlCursos = "
    SELECT c.id_curso, c.nombre_curso
    FROM cursos c
    INNER JOIN fichas_has_cursos fhc ON c.id_curso = fhc.cursos_id_curso
    WHERE fhc.fichas_id_ficha = $id_ficha
    ORDER BY c.nombre_curso
";
$resultadoCursos = $mysql->efectuarConsulta($sqlCursos);

$cursos = [];
if ($resultadoCursos) {
    while ($curso = $resultadoCursos->fetch_assoc()) {
        $cursos[] = $curso;
    }
}

// Obtener aprendices de la ficha compañeritos
$sqlAprendices = "
    SELECT a.id_aprendiz, a.correo_aprendiz
    FROM aprendices a
    INNER JOIN fichas_has_aprendices fha ON a.id_aprendiz = fha.aprendices_id_aprendiz
    WHERE fha.fichas_id_ficha = $id_ficha
    ORDER BY a.correo_aprendiz
";
$resultadoAprendices = $mysql->efectuarConsulta($sqlAprendices);

$aprendices = [];
if ($resultadoAprendices) {
    while ($aprendiz = $resultadoAprendices->fetch_assoc()) {
        $aprendices[] = $aprendiz;
    }
}
//el json que mandabmos a la DB
echo json_encode([
    'success' => true,
    'ficha' => $ficha,
    'cursos' => $cursos,
    'aprendices' => $aprendices
]);
?>