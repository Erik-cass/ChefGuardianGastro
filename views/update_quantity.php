<?php
include '../includes/config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id_articulo']) && isset($data['new_quantity'])) {
    $id_articulo = $data['id_articulo'];
    $new_quantity = $data['new_quantity'];

    // Agregar registro de depuración
    error_log("Actualizando id_articulo: $id_articulo a new_quantity: $new_quantity");

    $stmt = $conn->prepare("UPDATE articulos SET cantidad = ? WHERE id_articulo = ?");
    $stmt->bind_param('ii', $new_quantity, $id_articulo);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        // Agregar registro de error
        error_log("Error al ejecutar la actualización: " . $stmt->error);
        echo json_encode(['success' => false]);
    }

    $stmt->close();
    $conn->close();
} else {
    // Agregar registro de depuración
    error_log("Datos no válidos recibidos: " . json_encode($data));
    echo json_encode(['success' => false]);
}
?>

