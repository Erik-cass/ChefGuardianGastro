<?php
include_once("../includes/config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM alumnos WHERE id_usuarios = $id";
    if ($conn->query($query) === TRUE) {
        header('Location: general_alumnos.php');
        exit();
    } else {
        echo "Error eliminando registro: " . $conn->error;
    }
} else {
    echo "ID no proporcionado";
    exit();
}
?>