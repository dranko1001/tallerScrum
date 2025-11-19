<?php
class EditarUsuario {
    private $conn;
    private $id;
    private $tabla;

    public function __construct($conexion, $tabla){
        $this->conn = $conexion;
        $this->tabla = $tabla; 
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getUsuario(){
        $stmt = $this->conn->prepare("SELECT * FROM {$this->tabla} WHERE id = ?");
        $stmt->bind_param("i", $this->id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function actualizarUsuario($nombre, $email, $rol){
        $stmt = $this->conn->prepare("UPDATE {$this->tabla} SET nombre = ?, email = ?, rol = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nombre, $email, $rol, $this->id);
        return $stmt->execute();
    }
}
?>
