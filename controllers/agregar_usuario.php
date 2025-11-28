<?php
//conexion
require_once '../models/MySQL.php'; 
session_start();

//para que solo el administrador pueda ingresar usuarios
if (!isset($_SESSION['rol_usuario']) || $_SESSION['rol_usuario'] !== 'admin') {
    die("Acceso denegado. Solo el administrador puede agregar usuarios.");
}

$correo = $_POST['correo_usuario'] ?? '';
$password = $_POST['password_usuario'] ?? '';
$rol = $_POST['rol_usuario'] ?? '';

//validacion de que no quede ningun campo sin llenar
if (empty($correo) || empty($password) || empty($rol)) {
    die("Error: Correo, contraseña y rol son campos obligatorios.");
}

if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    die("Error: El formato del correo electrónico no es válido.");
}
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$mysql = new MySQL();
$mysql->conectar();


//miren como hay diferentes tablas administrador , intructor y aprendiz esto es para que cuando se eliga el rol en el formulario este de aca tome los valores de esa tabla y pueda introducirlos , hay maneras mas faciles si , mas sencillas tambien pero asi le entendi yo asi que pailas

$tabla = '';//ta blanco porque cuando se eliga va a tomar el nombre de la tabla
$columnas = '';
$valores = '';

switch ($rol) {
    case 'admin':
        $tabla = 'administrador';//aca ya tomo el nombre
        $columnas = "rol_usuario, correo_admin, password_admin";//aca las columnas o campos
        $valores = "'admin', '{$correo}', '{$password_hash}'"; // y aqui ya lo que ingrese en el formulario
        break;

    case 'instructor':
        $tabla = 'instructor';
        $columnas = "rol_usuario, correo_instructor, password_instructor";
        $valores = "'instructor', '{$correo}', '{$password_hash}'";
        break;

    case 'aprendiz':
        $tabla = 'aprendices';
        $columnas = "rol_usuario, correo_aprendiz, password_aprendiz";
        $valores = "'aprendiz', '{$correo}', '{$password_hash}'";
        break;

    default:
        $mysql->desconectar();
        die("Error: Rol de usuario no reconocido.");
}
//este ya es el insertar
$sql = "INSERT INTO {$tabla} ({$columnas}) VALUES ({$valores})";

$resultado = $mysql->efectuarConsulta($sql); 

//y esto es lo que le va a mandar al ajax para que de su respuesta
if ($resultado) { 
    echo "OK"; 
} else { 
   //y pues aqui el error 
    $error_db = mysqli_error($mysql->obtenerConexion()); 
    echo "Error de Inserción: " . $error_db; 
}

$mysql->desconectar();
?>