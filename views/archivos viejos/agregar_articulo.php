<?php

session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];

if( $validar == null || $validar = ''){

  header("Location: ../includes/login.php");
  die();
  
}


?>


<?php

include './includes/config.php';

$message = ''; // Inicializa una variable para almacenar el mensaje de alerta de JavaScript

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['rutaImagen'])) {
  $error = $_FILES['rutaImagen']['error'];
  // Verificar si el archivo ha sido cargado
  if ($error === UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['rutaImagen']['tmp_name'];
    $fileName = $_FILES['rutaImagen']['name'];
    $fileSize = $_FILES['rutaImagen']['size'];
    $fileType = $_FILES['rutaImagen']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Extensiones permitidas
    $allowedfileExtensions = ['jpeg', 'jpg', 'png'];
    // Se define el tamaño máximo permitido para los archivos que se cargan en el servidor, en este caso, se estableció en 5 megabytes (MB). Aquí, 1024 * 1024 convierte un megabyte a bytes (pues 1KB = 1024 bytes y 1MB = 1024KB), y luego se multiplica por 5 para obtener el total de bytes equivalentes a 5MB.
    $maxFileSize = 5 * 1024 * 1024; // 5 MB, se ajusta este valor según a las necesidades

    if (in_array($fileExtension, $allowedfileExtensions) && $fileSize <= $maxFileSize) {
      // Directorio donde se guardarán las imágenes
      $uploadFileDir = './uploaded_images/';
      $nombreArticulo = $_POST['nombreArticulo'];
      $cantidadExistente = $_POST['cantidadExistente'];
      $randomNumer = rand(1000, 9999); // Número aleatorio
      $nombreArticuloFormatted = preg_replace('/[^A-Za-z0-9\-]/', '_', $nombreArticulo);
      $newFileName = $nombreArticuloFormatted . '_' . $cantidadExistente . '_' . $randomNumer . '.' . $fileExtension;
      $dest_path = $uploadFileDir . $newFileName;

      if (!file_exists($uploadFileDir)) {
        mkdir($uploadFileDir, 0755, true);
      }

      if (move_uploaded_file($fileTmpPath, $dest_path)) {
        $sql = "INSERT INTO articulos (nombre, cantidad, imagen) VALUES (?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
          $stmt->bind_param("sis", $nombreArticulo, $cantidadExistente, $dest_path);
          if ($stmt->execute()) {
            $message = "¡Nuevo registro creado con éxito!";
          } else {
            $message = "Error al insertar en la base de datos: " . $stmt->error;
          }
          $stmt->close();
        }
      } else {
        $message = 'Error al mover el archivo cargado.';
      }
    } else {
      if ($fileSize > $maxFileSize) {
        $message = 'El archivo excede el tamaño máximo permitido.';
      } else {
        $message = 'El tipo de archivo cargado no está permitido. Solo se admiten archivos .jpeg, .jpg, .png';
      }
    }
  } else {
    $message = 'Error al cargar el archivo. Código de error: ' . $error;
  }
} else {
  $message = 'Por favor, seleccione un archivo para cargar.';
}

$conn->close();

// Usamos $message para imprimir el archivo en el alert de JavaScript que mostrará la alerta y redireccionará
echo "<script type='text/javascript'>alert('$message'); window.location.href = 'form.html';</script>";

?>
