<?php
require_once 'conexion.php';

class UsuarioModel {
    private $conn;

    public function __construct() {
        global $conn;
        $this->conn = $conn;
    }

    public function crearUsuario($nombres, $apellidos, $correo, $password, $fecha_nacimiento) {
        $hashedPassword = hash('sha256', $password);//convierte a hash - 256
        $stmt = $this->conn->prepare("INSERT INTO usuarios (nombres, apellidos, correo, contraseña, fecha_nacimiento) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nombres, $apellidos, $correo, $hashedPassword, $fecha_nacimiento);
        return $stmt->execute();
    }

    //visualizacion dinamica de los usuarios
    public function obtenerUsuarios() {
        $result = $this->conn->query("SELECT * FROM usuarios ORDER BY idusuarios DESC");
        return $result->fetch_all(MYSQLI_ASSOC);//devuelve un array asociativo
    }

    public function obtenerUsuarioPorId($id) {
        $stmt = $this->conn->prepare("SELECT * FROM usuarios WHERE idusuarios = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function actualizarUsuario($id, $nombres, $apellidos, $correo, $password, $fecha_nacimiento) {
        $hashedPassword = hash('sha256', $password); // Aplicar SHA-256
        $stmt = $this->conn->prepare("UPDATE usuarios SET nombres = ?, apellidos = ?, correo = ?, contraseña = ?, fecha_nacimiento = ? WHERE idusuarios = ?");
        $stmt->bind_param("sssssi", $nombres, $apellidos, $correo, $hashedPassword, $fecha_nacimiento, $id);
        return $stmt->execute(); //bind paran strins(s) y los ? es que se reemplanzan en el otrden indicado
    }

    public function eliminarUsuario($id) {
        $stmt = $this->conn->prepare("DELETE FROM usuarios WHERE idusuarios = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>