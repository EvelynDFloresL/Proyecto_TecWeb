<?php 
// Conectar a la base de datos
$servername = "localhost";
$username = "root";
$password = "itzel.123";
$dbname = "catalogovod";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
  die("Conexión fallida: " . $conn->connect_error);
}

?>