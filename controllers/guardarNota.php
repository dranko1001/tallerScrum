<?php
require_once '../models/MySQL.php';
header('Content-Type: application/json');

if (!isset($_POST['id_aprendiz'], $_POST['id_trabajo'], $_POST['calificacion'], $_POST['comentario'])) {
    echo json_encode(['success' => false, 'msg' => 'Faltan datos']);
    exit;
}

$id_aprendiz = $_POST['id_aprendiz'];
$id_trabajo  = $_POST['id_trabajo'];
$calificacion = $_POST['calificacion'];
$comentario = $_POST['comentario'];

$mysql = new MySQL();
$mysql->conectar();

$sql = "
INSERT INTO notas (usuarios_id_usuario, trabajos_id_trabajo, calificacion_nota, comentario_nota)
VALUES ('$id_aprendiz', '$id_trabajo', '$calificacion', '$comentario')
ON DUPLICATE KEY UPDATE 
    calificacion_nota = VALUES(calificacion_nota),
    comentario_nota = VALUES(comentario_nota)

";

$result = $mysql->efectuarConsulta($sql);

echo json_encode(['success' => $result ? true : false]);
?>
