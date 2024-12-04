<?php
session_start();

// Quita esta línea temporalmente para ver los errores en pantalla
// error_reporting(0);

$validar = $_SESSION['matricula'];

if (empty($validar)) {
    header("Location: ../includes/login.php");
    die();
}

include '../includes/config.php';

// Verificar si el parámetro 'id' está presente y es numérico para evitar inyecciones SQL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta preparada para mayor seguridad
    $deleteQuery = $conn->prepare("DELETE FROM articulos WHERE id_articulo = ?");
    $deleteQuery->bind_param("i", $id);

    if ($deleteQuery->execute()) {
        header("Location: rejilla_encargado.php?message=deleted");
        exit();
    } else {
        echo "Error eliminando el artículo: " . $conn->error;
    }

    $deleteQuery->close();
} else {
    echo "ID no proporcionado o inválido.";
    exit();
}

$conn->close();
?>
