<?php
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$nombre = $_POST['nombre_curso'] ?? null;
$instructores = $_POST['instructores_curso'] ?? null;

if (!$nombre || !$instructores) {
    echo json_encode(["success" => false, "message" => "Datos incompletos. Debe ingresar nombre del curso y al menos un instructor."]);
    exit;
}

// Decodificar el JSON de instructores
$instructoresArray = json_decode($instructores, true);

if (empty($instructoresArray) || !is_array($instructoresArray)) {
    echo json_encode(["success" => false, "message" => "Debe seleccionar al menos un instructor"]);
    exit;
}

// Insertar el curso
$sql = "INSERT INTO cursos (nombre_curso) VALUES ('$nombre')";

if (!$mysql->efectuarConsulta($sql)) {
    echo json_encode(["success" => false, "message" => "Error al insertar el curso"]);
    exit;
}

// Obtener el ID del curso recién insertado
$sqlId = "SELECT LAST_INSERT_ID() as id";
$resultado = $mysql->efectuarConsulta($sqlId);
$fila = $resultado->fetch_assoc();
$id_curso = $fila['id'];

// Insertar cada relación instructor con curso
$erroresInstructor = 0;
foreach ($instructoresArray as $id_instructor) {
    $id_instructor = intval($id_instructor);
    
    $sqlRelacion = "
        INSERT INTO instructor_has_cursos (instructor_id_usuario, cursos_id_curso)
        VALUES ($id_instructor, $id_curso)
    ";
    
    if (!$mysql->efectuarConsulta($sqlRelacion)) {
        $erroresInstructor++;
    }
}

// Respuesta según los resultados
if ($erroresInstructor > 0) {
    $mensaje = "El curso fue creado pero $erroresInstructor instructor(es) no se asociaron.";
    echo json_encode(["success" => false, "message" => $mensaje]);
} else {
    echo json_encode(["success" => true, "message" => "El curso fue agregado correctamente con " . count($instructoresArray) . " instructor(es)"]);
}
?>