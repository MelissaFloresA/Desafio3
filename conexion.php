<?php
$servidor = "localhost";
$usuario = "root";
$password = "";
$base_datos = "datos";

$conn = new mysqli($servidor, $usuario, $password, $base_datos);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>