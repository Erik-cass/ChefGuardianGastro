<?php 

// Configuración de la BD 
$hostname = "localhost";
$username = "root";
$password = "";
$dbname = "proyectogastro";

// Crear conexión a la BD
$conn = new mysqli($hostname, $username, $password, $dbname);

// Configura la codificación de caracteres a UTF8 
$conn->set_charset("utf8mb4");

// Verifica la conexión
if ($conn->connect_error) { 
  die("Connection failed: " . $conn->connect_error);
}

// Nombre de la tabla en la que se importan los datos crudos
$tableName = "articulos";

$query = "SELECT id_articulo, imagen, nombre, cantidad, categoria, descripcion, DATE_FORMAT(fh, '%d/%m/%Y') as fecha FROM $tableName";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $filename = "articulos.csv";
    $file = fopen($filename, 'w');

    // Escribir encabezados
    fputcsv($file, array('ID', 'Imagen', 'Nombre', 'Cantidad', 'Categoria', 'Descripcion', 'Fecha'));

    while ($row = $result->fetch_assoc()) {
        // Añadir etiqueta de imagen HTML para que se muestren las imágenes en el archivo CSV
        $row['imagen'] = '<img src="'.$row['imagen'].'" alt="imagen" />';
        fputcsv($file, $row);
    }

    fclose($file);

    // Descargar archivo CSV
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    readfile($filename);

    // Eliminar el archivo temporal
    unlink($filename);
} else { 
  echo "No hay datos para exportar.";
}

$conn->close();
?>
