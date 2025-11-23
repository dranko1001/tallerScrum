<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Content-Type: application/json; charset=utf-8");

require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['rol_usuario']) || $_SESSION['rol_usuario'] !== 'instructor') {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idInstructor = $_SESSION['id_instructor'];

    // Validar campos requeridos
    if (empty($_POST['id_trabajo']) || empty($_POST['calificacion']) || empty($_POST['comentario'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
        exit();
    }

    $mysql = new MySQL();
    $mysql->conectar();

    $idTrabajo = intval($_POST['id_trabajo']);
    $calificacion = trim($_POST['calificacion']);
    $comentario = htmlspecialchars(trim($_POST['comentario']), ENT_QUOTES, 'UTF-8');

    // Validar que la calificación sea A o D
    if ($calificacion !== 'A' && $calificacion !== 'D') {
        echo json_encode(['success' => false, 'message' => 'Calificación inválida']);
        exit();
    }

    // Verificar que el trabajo pertenece a un curso del instructor
    $verificacion = $mysql->efectuarConsulta("
        SELECT t.id_trabajo 
        FROM trabajos t
        INNER JOIN cursos c ON t.cursos_id_curso = c.id_curso
        INNER JOIN instructor_has_cursos ihc ON c.id_curso = ihc.cursos_id_curso
        WHERE t.id_trabajo = '$idTrabajo' AND ihc.instructor_id_usuario = '$idInstructor'
    ");

    if (mysqli_num_rows($verificacion) == 0) {
        echo json_encode(['success' => false, 'message' => 'No tiene permisos para calificar este trabajo']);
        exit();
    }

    // Verificar si ya existe una calificación para este trabajo
    $consultaExistente = $mysql->efectuarConsulta("
        SELECT id_nota FROM notas WHERE trabajos_id_trabajo = '$idTrabajo'
    ");

    if (mysqli_num_rows($consultaExistente) > 0) {
        // Actualizar calificación existente
        $fila = mysqli_fetch_assoc($consultaExistente);
        $idNota = $fila['id_nota'];

        $sqlUpdate = "
            UPDATE notas 
            SET calificacion_nota = '$calificacion', 
                comentario_nota = '$comentario',
                instructor_id_instructor = '$idInstructor'
            WHERE id_nota = '$idNota'
        ";

        if ($mysql->efectuarConsulta($sqlUpdate)) {
            echo json_encode([
                'success' => true,
                'message' => 'Calificación actualizada exitosamente',
                'calificacion' => $calificacion
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar la calificación']);
        }

    } else {
        // Insertar nueva calificación
        $sqlInsert = "
            INSERT INTO notas (calificacion_nota, comentario_nota, trabajos_id_trabajo, instructor_id_instructor)
            VALUES ('$calificacion', '$comentario', '$idTrabajo', '$idInstructor')
        ";

        if ($mysql->efectuarConsulta($sqlInsert)) {
            echo json_encode([
                'success' => true,
                'message' => 'Trabajo calificado exitosamente',
                'calificacion' => $calificacion
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar la calificación']);
        }
    }

    $mysql->desconectar();

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>