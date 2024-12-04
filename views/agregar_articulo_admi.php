<?php
session_start();
error_reporting(E_ALL); // Mostrar todos los errores
ini_set('display_errors', 1); // Habilitar la visualización de errores

$validar = $_SESSION['matricula'];

if ($validar == null || $validar === '') {
    header("Location: ../includes/index.php");
    die();
}

include '../includes/config.php';

$message = ''; // Inicializa una variable para almacenar el mensaje
$type = 'error'; // Define el tipo por defecto para SweetAlert (success o error)

// Verificar si todos los campos del formulario están presentes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['rutaImagen']) && isset($_POST['nombreArticulo']) && isset($_POST['cantidadExistente']) && isset($_POST['categoria']) && isset($_POST['descripcion']) && isset($_POST['estatus'])) {
    $error = $_FILES['rutaImagen']['error'];

    // Verificar si el archivo ha sido cargado
    if ($error === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['rutaImagen']['tmp_name'];
        $fileName = $_FILES['rutaImagen']['name'];
        $fileSize = $_FILES['rutaImagen']['size'];
        $fileType = $_FILES['rutaImagen']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Extensiones permitidas y tamaño máximo
        $allowedfileExtensions = ['jpeg', 'jpg', 'png'];
        $maxFileSize = 5 * 1024 * 1024; // 5 MB

        // Validación del archivo
        if (in_array($fileExtension, $allowedfileExtensions) && $fileSize <= $maxFileSize) {
            // Comprobar si el archivo es una imagen real
            $imageInfo = getimagesize($fileTmpPath);
            if ($imageInfo === false) {
                $message = 'El archivo no es una imagen válida.';
            } else {
                // Directorio donde se guardarán las imágenes
                $uploadFileDir = '../uploaded_images/';
                $nombreArticulo = $_POST['nombreArticulo'];
                $cantidadExistente = $_POST['cantidadExistente'];
                $categoria = $_POST['categoria'];
                $descripcion = $_POST['descripcion'];
                $estatus = $_POST['estatus'];
                $randomNumer = rand(1000, 9999); // Número aleatorio
                $nombreArticuloFormatted = preg_replace('/[^A-Za-z0-9\-]/', '_', $nombreArticulo);
                $newFileName = $nombreArticuloFormatted . '_' . $cantidadExistente . '_' . $randomNumer . '.' . $fileExtension;
                $dest_path = $uploadFileDir . $newFileName;

                // Crear directorio si no existe
                if (!file_exists($uploadFileDir)) {
                    mkdir($uploadFileDir, 0755, true);
                }

                // Mover archivo al destino
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $sql = "INSERT INTO articulos (nombre, cantidad, categoria, descripcion, imagen, estatus) VALUES (?, ?, ?, ?, ?, ?)";

                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("sissss", $nombreArticulo, $cantidadExistente, $categoria, $descripcion, $dest_path, $estatus);
                        if ($stmt->execute()) {
                            $message = "¡Nuevo registro creado con éxito!";
                            $type = 'success';
                        } else {
                            $message = "Error al insertar en la base de datos: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                } else {
                    $message = 'Error al mover el archivo cargado.';
                }
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
    // Respuesta de error si falta algún dato
    $message = 'Todos los campos son requeridos.';
}

if ($conn) {
    $conn->close();
}

echo json_encode([
    'mensaje' => $message,
    'tipo_mensaje' => $type
]);
?>
