<?php
require_once '../models/MySQL.php';

header('Content-Type: application/json');

$mysql = new MySQL();
$mysql->conectar();

$query = $_POST['query'] ?? '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

// Escapar manualmente usando la conexión de MySQL
$queryEscapado = mysqli_real_escape_string($mysql->getConexion(), $query);

// Consulta que verifica si el aprendiz ya tiene FICHA
$sql = "
    SELECT 
        a.id_aprendiz,
        a.correo_aprendiz,
        CASE 
            WHEN fha.fichas_id_ficha IS NOT NULL THEN 1 
            ELSE 0 
        END as tiene_ficha,
        f.nombre_ficha as ficha_actual
    FROM aprendices a
    LEFT JOIN fichas_has_aprendices fha ON a.id_aprendiz = fha.aprendices_id_aprendiz
    LEFT JOIN fichas f ON fha.fichas_id_ficha = f.id_ficha
    WHERE a.correo_aprendiz LIKE '%$queryEscapado%'
    LIMIT 10
";

$resultado = $mysql->efectuarConsulta($sql);

$aprendices = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $aprendices[] = [
            'id_aprendiz' => $fila['id_aprendiz'],
            'correo_aprendiz' => $fila['correo_aprendiz'],
            'tiene_ficha' => (int)$fila['tiene_ficha'],
            'ficha_actual' => $fila['ficha_actual'] ?? 'N/A'
        ];
    }
}

echo json_encode($aprendices);
?>