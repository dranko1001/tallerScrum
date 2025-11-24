<?php
require_once '../models/MySQL.php';
$mysql = new MySQL();
$mysql->conectar();

$nombre = $_POST['nombre_ficha'] ?? null;
$cursos = $_POST['cursos_ficha'] ?? null;
$aprendices = $_POST['aprendices_ficha'] ?? null;

if (!$nombre) {
    echo json_encode(["success" => false, "message" => "Debe ingresar el número de ficha."]);
    exit;
}

// Decodificar los JSON
$cursosArray = json_decode($cursos, true);
$aprendicesArray = json_decode($aprendices, true);

// Verificar que los arreglos sean válidos
if ($cursos && (!is_array($cursosArray) || $cursosArray === null)) {
    echo json_encode(["success" => false, "message" => "Error en la selección de cursos"]);
    exit;
}

if ($aprendices && (!is_array($aprendicesArray) || $aprendicesArray === null)) {
    echo json_encode(["success" => false, "message" => "Error en la selección de aprendices"]);
    exit;
}

// Verificar que los aprendices no estén ya en otra ficha
if (!empty($aprendicesArray)) {
    $idsAprendices = implode(',', array_map('intval', $aprendicesArray));
    
    $sqlVerificar = "
        SELECT a.correo_aprendiz, f.nombre_ficha
        FROM aprendices a
        INNER JOIN fichas_has_aprendices fha ON a.id_aprendiz = fha.aprendices_id_aprendiz
        INNER JOIN fichas f ON fha.fichas_id_ficha = f.id_ficha
        WHERE a.id_aprendiz IN ($idsAprendices)
    ";
    
    $resultadoVerificar = $mysql->efectuarConsulta($sqlVerificar);
    
    if ($resultadoVerificar && $resultadoVerificar->num_rows > 0) {
        $aprendicesConFicha = [];
        while ($fila = $resultadoVerificar->fetch_assoc()) {
            $aprendicesConFicha[] = $fila['correo_aprendiz'] . " ya está en la ficha " . $fila['nombre_ficha'];
        }
        
        $mensaje = "Aprendiz(ces) ya registrado(s) en una ficha:\n" . implode("\n", $aprendicesConFicha);
        echo json_encode(["success" => false, "message" => $mensaje]);
        exit;
    }
}

// Insertar la ficha
$sql = "INSERT INTO fichas (nombre_ficha) VALUES ('$nombre')";

if (!$mysql->efectuarConsulta($sql)) {
    echo json_encode(["success" => false, "message" => "Error al insertar la ficha"]);
    exit;
}

// Obtener el ID de la ficha recién insertada
$sqlId = "SELECT LAST_INSERT_ID() as id";
$resultado = $mysql->efectuarConsulta($sqlId);
$fila = $resultado->fetch_assoc();
$id_ficha = $fila['id'];

// Insertar relaciones con cursos (si es q hay)
$erroresCurso = 0;
if (!empty($cursosArray)) {
    foreach ($cursosArray as $id_curso) {
        $id_curso = intval($id_curso);
        
        $sqlRelacion = "
            INSERT INTO fichas_has_cursos (fichas_id_ficha, cursos_id_curso)
            VALUES ($id_ficha, $id_curso)
        ";
        
        if (!$mysql->efectuarConsulta($sqlRelacion)) {
            $erroresCurso++;
        }
    }
}

// Insertar relaciones con aprendices (si es q tambien  hay)
$erroresAprendiz = 0;
if (!empty($aprendicesArray)) {
    foreach ($aprendicesArray as $id_aprendiz) {
        $id_aprendiz = intval($id_aprendiz);
        
        $sqlRelacion = "
            INSERT INTO fichas_has_aprendices (fichas_id_ficha, aprendices_id_aprendiz)
            VALUES ($id_ficha, $id_aprendiz)
        ";
        
        if (!$mysql->efectuarConsulta($sqlRelacion)) {
            $erroresAprendiz++;
        }
    }
}

// Respuesta según los resultados con la DB
if ($erroresCurso > 0 || $erroresAprendiz > 0) {
    $mensaje = "La ficha fue creada pero hubo errores: ";
    if ($erroresCurso > 0) $mensaje .= "$erroresCurso curso(s) no se asociaron. ";
    if ($erroresAprendiz > 0) $mensaje .= "$erroresAprendiz aprendiz(ces) no se asociaron.";
    echo json_encode(["success" => false, "message" => $mensaje]);
} else {
    echo json_encode(["success" => true, "message" => "La ficha fue agregada correctamente"]);
}
?>