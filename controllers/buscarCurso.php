<?php
require_once '../models/MySQL.php';

header('Content-Type: application/json; charset=utf-8');

$mysql = new MySQL();
$mysql->conectar();

$query = $_POST['query'] ?? '';

if (empty($query)) {
    echo json_encode([]);
    exit;
}

// Limpiar el query de caracteres peligrosos
$query = str_replace(["'", '"', '\\'], '', $query);

$sql = "
    SELECT 
        id_curso,
        nombre_curso
    FROM cursos
    WHERE nombre_curso LIKE '%$query%'
    ORDER BY nombre_curso ASC
    LIMIT 10
";

$resultado = $mysql->efectuarConsulta($sql);

$cursos = [];
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $cursos[] = [
            'id_curso' => $fila['id_curso'],
            'nombre_curso' => $fila['nombre_curso']
        ];
    }
}

echo json_encode($cursos);
?>