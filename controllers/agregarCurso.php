<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

require_once '../models/MySQL.php';
session_start();


if (!isset($_SESSION['tipo_usuario'])) {
    header("location: ./login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $mysql = new MySQL();
    $mysql->conectar();

    // Validar campos requeridos
    $required = [/*'id_curso',*/ 'nombre_curso'];
    foreach ($required as $campo) {
        if (!isset($_POST[$campo]) || empty(trim($_POST[$campo]))) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => "Falta el campo $campo"]);
            exit;
        }
    }
//sanitizar y asignar variables
    $nombre   = htmlspecialchars(trim($_POST['nombre_curso']), ENT_QUOTES, 'UTF-8');

    // Verificar si el curso ya está registrado
    $consultaExiste = "SELECT id_curso FROM cursos WHERE nombre_curso = '$nombre'";
    $resultado = $mysql->efectuarConsulta($consultaExiste);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        echo json_encode(['success' => false, 'message' => 'El curso ya está registrado.']);
        $mysql->desconectar();
        exit;
    }

    $consultaInsert = "
        INSERT INTO cursos (nombre_curso)
        VALUES ('$nombre')
    ";

    if ($mysql->efectuarConsulta($consultaInsert)) {
        echo json_encode(['success' => true, 'message' => 'El curso agregado exitosamente.']);
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Error al agregar el curso.']);
    }

    $mysql->desconectar();
}
?>

