<?php
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

// Verificar que lleguen los datos
$nombre = $_POST['nombre_curso'] ?? null;
$instructores = $_POST['instructores_curso'] ?? null;

// Debug: descomentar estas líneas para ver qué está llegando
// echo json_encode(["debug" => $_POST]);
// exit;

if(!$nombre || !$instructores){
    echo json_encode(["success"=>false, "message"=>"Datos incompletos. Nombre: ".($nombre ? "OK" : "Falta").", Instructores: ".($instructores ? "OK" : "Falta")]);
    exit;
}

// Decodificar el JSON que viene del formulario
$instructoresArray = json_decode($instructores, true);

if(empty($instructoresArray) || !is_array($instructoresArray)){
    echo json_encode(["success"=>false, "message"=>"Debe seleccionar al menos un instructor"]);
    exit;
}

// 1. Insertar el curso
$sql = "INSERT INTO cursos (nombre_curso) VALUES ('$nombre')";

if(!$mysql->efectuarConsulta($sql)){
    echo json_encode(["success"=>false, "message"=>"Error al insertar el curso"]);
    exit;
}

// 2. Obtener el ID del curso recién insertado
$sqlId = "SELECT LAST_INSERT_ID() as id";
$resultado = $mysql->efectuarConsulta($sqlId);
$fila = $resultado->fetch_assoc();
$id_curso = $fila['id'];

// 3. Insertar cada relación instructor-curso en la tabla intermedia
$errores = 0;
foreach($instructoresArray as $id_instructor){
    $id_instructor = intval($id_instructor);
    
    $sqlRelacion = "
        INSERT INTO instructor_has_cursos (instructor_id_usuario, cursos_id_curso)
        VALUES ($id_instructor, $id_curso)
    ";
    
    if(!$mysql->efectuarConsulta($sqlRelacion)){
        $errores++;
    }
}

if($errores > 0){
    echo json_encode(["success"=>false, "message"=>"El curso fue creado pero hubo errores al asociar algunos instructores"]);
}else{
    echo json_encode(["success"=>true, "message"=>"El curso y sus instructores fueron agregados correctamente"]);
}
?>