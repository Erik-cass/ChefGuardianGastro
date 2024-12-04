<?php 

// Configuracion de la BD 
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "proyectogastro";

// Crea conexion a la BD
$conn = new mysqli($hostname, $username, $password, $dbname);

// Configura la codificacion de caracteres a UTF8 
// $conn-> set_charset("utf8mb4");

// Verifica la conexion $hostname, $username, $password, $dbname
if ($conn->connect_error) { 
	die("connection failed: " . $conn->connect_error);
}

?>