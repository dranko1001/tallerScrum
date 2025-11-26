<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Pragma: no-cache");
header("Content-Type: application/json; charset=utf-8");

require_once '../models/MySQL.php';
session_start();

if (!isset($_SESSION['rol_usuario']) || $_SESSION['rol_usuario'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Acceso no autorizado']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (empty($_POST['id']) || empty($_POST['rol_anterior']) || empty($_POST['rol_nuevo']) || empty($_POST['correo'])) {
        echo json_encode(['success' => false, 'message' => 'Faltan campos obligatorios']);
        exit();
    }

    $mysql = new MySQL();
    $mysql->conectar();

    $id = intval($_POST['id']);
    $rolAnterior = trim($_POST['rol_anterior']);
    $rolNuevo = trim($_POST['rol_nuevo']);
    $correo = htmlspecialchars(trim($_POST['correo']), ENT_QUOTES, 'UTF-8');

    // Validar roles
    if (
        !in_array($rolAnterior, ['admin', 'instructor', 'aprendiz']) ||
        !in_array($rolNuevo, ['admin', 'instructor', 'aprendiz'])
    ) {
        echo json_encode(['success' => false, 'message' => 'Rol inválido']);
        exit();
    }

    // Determinar tabla y campos según el rol anterior
    if ($rolAnterior === 'admin') {
        $tablaAnterior = 'administrador';
        $campoIdAnterior = 'id_admin';
        $campoCorreoAnterior = 'correo_admin';
        $campoPasswordAnterior = 'password_admin';
    } elseif ($rolAnterior === 'instructor') {
        $tablaAnterior = 'instructor';
        $campoIdAnterior = 'id_instructor';
        $campoCorreoAnterior = 'correo_instructor';
        $campoPasswordAnterior = 'password_instructor';
    } else {
        $tablaAnterior = 'aprendices';
        $campoIdAnterior = 'id_aprendiz';
        $campoCorreoAnterior = 'correo_aprendiz';
        $campoPasswordAnterior = 'password_aprendiz';
    }

    // Verificar si el correo ya existe en otra cuenta (excepto la actual)
    $verificarAdmin = $mysql->efectuarConsulta("SELECT id_admin FROM administrador WHERE correo_admin = '$correo' AND id_admin != '$id'");
    $verificarInstructor = $mysql->efectuarConsulta("SELECT id_instructor FROM instructor WHERE correo_instructor = '$correo' AND id_instructor != '$id'");
    $verificarAprendiz = $mysql->efectuarConsulta("SELECT id_aprendiz FROM aprendices WHERE correo_aprendiz = '$correo' AND id_aprendiz != '$id'");

    if (
        mysqli_num_rows($verificarAdmin) > 0 ||
        mysqli_num_rows($verificarInstructor) > 0 ||
        mysqli_num_rows($verificarAprendiz) > 0
    ) {
        echo json_encode(['success' => false, 'message' => 'El correo ya está registrado en otro usuario']);
        exit();
    }

    // Si el rol cambió, mover el usuario a otra tabla
    if ($rolAnterior !== $rolNuevo) {

        // Obtener la contraseña actual del usuario
        $resultadoUsuario = $mysql->efectuarConsulta("SELECT $campoPasswordAnterior FROM $tablaAnterior WHERE $campoIdAnterior = '$id'");

        if (mysqli_num_rows($resultadoUsuario) === 0) {
            echo json_encode(['success' => false, 'message' => 'Usuario no encontrado']);
            exit();
        }

        $usuarioData = mysqli_fetch_assoc($resultadoUsuario);
        $passwordHash = $usuarioData[$campoPasswordAnterior];

        // Determinar tabla y campos del nuevo rol
        if ($rolNuevo === 'admin') {
            $tablaNueva = 'administrador';
            $campoCorreoNuevo = 'correo_admin';
            $campoPasswordNuevo = 'password_admin';
            $rolTexto = 'Administrador';
        } elseif ($rolNuevo === 'instructor') {
            $tablaNueva = 'instructor';
            $campoCorreoNuevo = 'correo_instructor';
            $campoPasswordNuevo = 'password_instructor';
            $rolTexto = 'Instructor';
        } else {
            $tablaNueva = 'aprendices';
            $campoCorreoNuevo = 'correo_aprendiz';
            $campoPasswordNuevo = 'password_aprendiz';
            $rolTexto = 'Aprendiz';
        }

        // Insertar en la nueva tabla
        $sqlInsert = "
            INSERT INTO $tablaNueva (rol_usuario, $campoCorreoNuevo, $campoPasswordNuevo)
            VALUES ('$rolTexto', '$correo', '$passwordHash')
        ";

        if (!$mysql->efectuarConsulta($sqlInsert)) {
            echo json_encode(['success' => false, 'message' => 'Error al cambiar el rol del usuario']);
            exit();
        }

        // Eliminar de la tabla anterior
        $sqlDelete = "DELETE FROM $tablaAnterior WHERE $campoIdAnterior = '$id'";
        $mysql->efectuarConsulta($sqlDelete);

        echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente']);

    } else {
        // Solo actualizar el correo en la misma tabla
        $sqlUpdate = "UPDATE $tablaAnterior SET $campoCorreoAnterior = '$correo' WHERE $campoIdAnterior = '$id'";

        if ($mysql->efectuarConsulta($sqlUpdate)) {
            echo json_encode(['success' => true, 'message' => 'Usuario actualizado exitosamente']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar el usuario']);
        }
    }

    $mysql->desconectar();

} else {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>