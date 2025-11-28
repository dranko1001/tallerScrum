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

//sanitizacion o filtro mas bien de busqueda de aprendices, que no venga una comilla a putearnos la consulta
$query = str_replace(["'", '"', '\\'], '', $query);
//consulta extensa que busca al aprendiz y si tiene ficha o no
$sql = "
    SELECT 
        a.id_aprendiz,
        a.correo_aprendiz,
        CASE 
            WHEN fha.aprendices_id_aprendiz IS NOT NULL THEN 1
            ELSE 0
        END AS tiene_ficha,
        f.nombre_ficha AS ficha_actual
    FROM aprendices a
    LEFT JOIN fichas_has_aprendices fha ON a.id_aprendiz = fha.aprendices_id_aprendiz
    LEFT JOIN fichas f ON fha.fichas_id_ficha = f.id_ficha
    WHERE a.correo_aprendiz LIKE '%$query%'
    ORDER BY tiene_ficha ASC, a.correo_aprendiz ASC
    LIMIT 10
";

$resultado = $mysql->efectuarConsulta($sql);
//el arreglo que los muestra, en la documentacion de Javascript hay mas detalles de como funciona
$aprendices = [];
if ($resultado) {
    while ($fila = $resultado->fetch_assoc()) {
        $aprendices[] = [
            'id_aprendiz' => $fila['id_aprendiz'],
            'correo_aprendiz' => $fila['correo_aprendiz'],
            'tiene_ficha' => $fila['tiene_ficha'],
            'ficha_actual' => $fila['ficha_actual'] ?? ''
        ];
    }
}

echo json_encode($aprendices);
?>