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
$idAprendiz = $_SESSION['id_' . $rol];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    header("Content-Type: application/json; charset=utf-8");

    $mysql = new MySQL();
    $mysql->conectar();

    $id = isset($_POST['id_trabajo']) ? intval($_POST['id_trabajo']) : 0;
    if ($id <= 0) {
        echo json_encode(["success" => false, "message" => "ID inválido"]);
        exit();
    }

    $nombre = '';
    
    //sanetizar datos
    if (isset($_POST['nombre_trabajo']) && trim($_POST['nombre_trabajo']) != '') {
        $nombre = htmlspecialchars(trim($_POST['nombre_trabajo']), ENT_QUOTES, 'UTF-8');
        $mysql->efectuarConsulta("UPDATE trabajos SET nombre_trabajo='$nombre' WHERE id_trabajo='$id'");
    }

    // Procesar archivo si se sube
    if (isset($_FILES['ruta_trabajo']) && $_FILES['ruta_trabajo']['error'] === UPLOAD_ERR_OK) {
        
        $permitidos = ['application/pdf' => '.pdf',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx'];

        $tipo = mime_content_type($_FILES['ruta_trabajo']['tmp_name']);
        
        if (!isset($permitidos[$tipo])) {
            echo json_encode(['success' => false, 'message' => 'Solo se permiten archivos PDF y DOCX']);
            exit;
        }

        // Guardar archivo nuevo
        $ext = $permitidos[$tipo];
        $nombreArchivo = "trabajo_" . time() . $ext;
        $ruta = "assets/archivos/" . $nombreArchivo;
        $rutaAbsoluta = __DIR__ . "/../" . $ruta;

        if (move_uploaded_file($_FILES['ruta_trabajo']['tmp_name'], $rutaAbsoluta)) {
            // Obtener ruta antigua para eliminarla
            $resultado = $mysql->efectuarConsulta("SELECT ruta_trabajo FROM trabajos WHERE id_trabajo='$id'");
            if ($resultado && $fila = mysqli_fetch_assoc($resultado)) {
                $rutaAntigua = __DIR__ . "/../" . $fila['ruta_trabajo'];
                if (file_exists($rutaAntigua)) {
                    unlink($rutaAntigua);
                }
            }
            
            // Actualizar ruta en base de datos
            $mysql->efectuarConsulta("UPDATE trabajos SET ruta_trabajo='$ruta' WHERE id_trabajo='$id'");
        }
    }

    echo json_encode(['success' => true, 'message' => 'Trabajo actualizado exitosamente']);
    $mysql->desconectar();
}
?>