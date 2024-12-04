<?php
session_start();
header('Content-Type: application/json');
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $items = $data['items'];

    foreach ($items as $item) {
        $id_articulo = $item['id_articulo'];
        $cantidad_solicitada = $item['cantidad'];

        // Verificar la cantidad disponible en la base de datos
        $stmt = $conn->prepare("SELECT cantidad FROM articulos WHERE id_articulo = ?");
        $stmt->bind_param("i", $id_articulo);
        $stmt->execute();
        $stmt->bind_result($cantidad_disponible);
        $stmt->fetch();
        $stmt->close();

        if ($cantidad_solicitada > $cantidad_disponible) {
            echo json_encode([
                'success' => false,
                'message' => "Cantidad solicitada excede la cantidad disponible para el artículo ID $id_articulo."
            ]);
            exit;
        }
    }

    // Si todas las cantidades son válidas, actualizar la base de datos
    foreach ($items as $item) {
        $id_articulo = $item['id_articulo'];
        $cantidad_solicitada = $item['cantidad'];

        $stmt = $conn->prepare("UPDATE articulos SET cantidad = cantidad - ? WHERE id_articulo = ?");
        $stmt->bind_param("ii", $cantidad_solicitada, $id_articulo);
        $stmt->execute();
        $stmt->close();
    }

    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Método de solicitud no permitido.'
    ]);
}
?>
