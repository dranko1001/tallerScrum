<?php
<<<<<<< HEAD
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
=======
require_once '../models/MySQL.php';

header('Content-Type: application/json');

$mysql = new MySQL();
$mysql->conectar();

$sql = "
SELECT  
    t.id_trabajo,  
    t.nombre_trabajo,  
    t.fecha_trabajo,  
    a.id_aprendiz,  
    a.correo_aprendiz  
FROM trabajos t  
LEFT JOIN aprendices a  
    ON a.id_aprendiz = t.aprendices_id_aprendiz  
LEFT JOIN notas n  
    ON n.usuarios_id_usuario = a.id_aprendiz  
    AND n.trabajos_id_trabajo = t.id_trabajo  
WHERE n.id_nota IS NULL;
";

$resultado = $mysql->efectuarConsulta($sql);

$data = [];

while ($fila = $resultado->fetch_assoc()) {
    $data[] = $fila;
}

echo json_encode($data);
?>
>>>>>>> 037605655a0f7b8bfbd7076cee02b1b5272f840c
