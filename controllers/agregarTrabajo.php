<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");

require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['rol_usuario'])) {
    header("location: ../views/login.php");
    exit();
}

$rol = $_SESSION['rol_usuario'];
$idAprendiz = $_SESSION['id_' . $rol]; // aprendices_id_aprendiz para capturar el id 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header("Content-Type: application/json; charset=utf-8");

    // Campos requeridos correctos
    $required = ['nombre_trabajo', 'cursos_id_curso'];
    foreach ($required as $campo) {
        if (empty($_POST[$campo])) {
            echo json_encode(['success' => false, 'message' => "Falta el campo $campo"]);
            exit;
        }
    }

    if (!isset($_FILES['ruta_trabajo']) || $_FILES['ruta_trabajo']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['success' => false, 'message' => 'Debe subir un archivo PDF o DOCX']);
        exit;
    }

    $permitidos = ['application/pdf' => '.pdf',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx'];

    $tipo = mime_content_type($_FILES['ruta_trabajo']['tmp_name']);
    if (!isset($permitidos[$tipo])) {
        echo json_encode(['success' => false, 'message' => 'Formato no permitido']);
        exit;
    }

    // Guardar archivo
    $ext = $permitidos[$tipo];
    $nombreArchivo = "trabajo_" . time() . $ext;

    $ruta = "assets/archivos/" . $nombreArchivo;
    $rutaAbsoluta = __DIR__ . "/../" . $ruta;

    move_uploaded_file($_FILES['ruta_trabajo']['tmp_name'], $rutaAbsoluta);

    $mysql = new MySQL();
    $mysql->conectar();

    $nombre = htmlspecialchars(trim($_POST['nombre_trabajo']), ENT_QUOTES, 'UTF-8');
    $curso  = intval($_POST['cursos_id_curso']);
    $añoActual= date("Y");
    $fechaLimite= $añoActual . "-12-31";

    $Consulta = "
        INSERT INTO trabajos 
        (nombre_trabajo, fecha_trabajo, ruta_trabajo, aprendices_id_aprendiz, cursos_id_curso)
        VALUES 
        ('$nombre', '$fechaLimite', '$ruta', '$idAprendiz', '$curso')
    ";

    if ($mysql->efectuarConsulta($Consulta)) {
        echo json_encode(['success' => true, 'message' => 'Trabajo agregado exitosamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al guardar en la BD']);
    }

    $mysql->desconectar();
}
?>