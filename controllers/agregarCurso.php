<?php
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$nombre = $_POST['nombre_curso'] ?? null;
$instructores = $_POST['instructores_curso'] ?? null;
$aprendices = $_POST['aprendices_curso'] ?? null;

if(!$nombre || !$instructores){
    echo json_encode(["success"=>false, "message"=>"Datos incompletos. Debe ingresar nombre del curso y al menos un instructor."]);
    exit;
}
// Esto cesar no lo explico pero tienen que decodificar el json que llega de la db para que funcione lo de agregar
$instructoresArray = json_decode($instructores, true);
$aprendicesArray = json_decode($aprendices, true);

if(empty($instructoresArray) || !is_array($instructoresArray)){
    echo json_encode(["success"=>false, "message"=>"Debe seleccionar al menos un instructor"]);
    exit;
}

// es opcional agregar un aprendiz compañeritos, pero en caso de que no tiene que venir un array si o si, sino sale un enlace todo raro como error
if($aprendices && (!is_array($aprendicesArray) || $aprendicesArray === null)){
    echo json_encode(["success"=>false, "message"=>"Error en la selección de aprendices"]);
    exit;
}

// consulta con la insercion de codigo
$sql = "INSERT INTO cursos (nombre_curso) VALUES ('$nombre')";

if(!$mysql->efectuarConsulta($sql)){
    echo json_encode(["success"=>false, "message"=>"Error al insertar el curso"]);
    exit;
}

//Obtenemos el ID del curso recién insertado, ingles basico "lAST ID"=ULTIMO ID
$sqlId = "SELECT LAST_INSERT_ID() as id";
$resultado = $mysql->efectuarConsulta($sqlId);
$fila = $resultado->fetch_assoc();
$id_curso = $fila['id'];

// Insertar cada relación instructor-curso, con la tabla pivote
$erroresInstructor = 0;
foreach($instructoresArray as $id_instructor){
    $id_instructor = intval($id_instructor);
    
    $sqlRelacion = "
        INSERT INTO instructor_has_cursos (instructor_id_usuario, cursos_id_curso)
        VALUES ($id_instructor, $id_curso)
    ";
    
    if(!$mysql->efectuarConsulta($sqlRelacion)){
        $erroresInstructor++;
    }
}

//Insertar cada relación aprendiz-curso (si hay aprendices seleccionados)
$erroresAprendiz = 0;
if(!empty($aprendicesArray)){
    foreach($aprendicesArray as $id_aprendiz){
        $id_aprendiz = intval($id_aprendiz);
        
        $sqlRelacion = "
            INSERT INTO cursos_has_aprendices (cursos_id_curso, aprendices_id_aprendiz)
            VALUES ($id_curso, $id_aprendiz)
        ";
        
        if(!$mysql->efectuarConsulta($sqlRelacion)){
            $erroresAprendiz++;
        }
    }
}

// la respuesta segun los resultados, con los if como nos enseño bladimir para validar los resultados
if($erroresInstructor > 0 || $erroresAprendiz > 0){
    $mensaje = "El curso fue creado pero hubo errores: ";
    if($erroresInstructor > 0) $mensaje .= "$erroresInstructor instructor(es) no se asociaron. ";
    if($erroresAprendiz > 0) $mensaje .= "$erroresAprendiz aprendiz(ces) no se asociaron.";
    echo json_encode(["success"=>false, "message"=>$mensaje]);
}else{
    $totalAsociados = count($instructoresArray) + count($aprendicesArray);
    echo json_encode(["success"=>true, "message"=>"El curso fue agregado correctamente con $totalAsociados usuario(s) asociado(s)"]);
}
?>