<?php
session_start();
include '../includes/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_articulo = $_POST['id_articulo'];
    $estado = $_POST['estado'];

    $sql = "UPDATE pedidos SET estado = ? WHERE id_articulo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $estado, $id_articulo);
    $stmt->execute();

    header("Location: historial_pedido_enca.php");
    exit();
}
?>