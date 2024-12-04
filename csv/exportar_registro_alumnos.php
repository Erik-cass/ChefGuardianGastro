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

  // Nombre de la tabla en la que se importan los datos crudos
  $tableName = "alumnos";

  $query = "SELECT id_usuarios, matricula, nombre, email, grupo, cuatrimestre, rol FROM $tableName WHERE rol= 'alumno'";
  $result = $conn->query($query);

  if ($result->num_rows > 0) {
      $filename = "alumnos.csv";

      $file = fopen($filename, 'w');

      fputcsv($file, array('ID', 'Matricula','Nombre', 'Email', 'Grupo', 'Cuatrimestre', 'Rol'));
      while ($row = $result->fetch_assoc()) {
        fputcsv($file, $row);
      }

      fclose($file);
      header('Content-Type: aplication/csv');
      header('Content-Disposition: attachment; filename="' . $filename . '"');
      readfile($filename);

      unlink($filename);
  } else { 
    echo "No hay datos para exportar.";
  }

  $conn->close();
  ?>
