<?php
session_start();
error_reporting(0);

$validar = $_SESSION['matricula'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

include '../includes/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $deleteQuery = "DELETE FROM articulos WHERE id_articulo='$id'";

    if ($conn->query($deleteQuery) === TRUE) {
        header("Location: rejilla_admi.php?message=deleted");
        exit();
    } else {
        echo "Error eliminando el artículo: " . $conn->error;
    }
} else {
    echo "ID no proporcionado.";
    exit();
}

$conn->close();
?>
