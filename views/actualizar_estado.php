<?php
include '../includes/config.php';

$data = json_decode(file_get_contents('php://input'), true);

// Validar datos recibidos
if (isset($data['id'], $data['cantidad'], $data['status'])) {
    $id_articulo = intval($data['id']);
    $cantidad = intval($data['cantidad']);
    $status = $data['status'];

    // Validar que el estado sea 'perdido' o 'dañado'
    if (!in_array($status, ['perdido', 'dañado'])) {
        echo json_encode(['success' => false, 'message' => 'Estado no válido.']);
        exit;
    }

    $tabla = ($status === 'perdido') ? 'articulos_perdidos' : 'articulos_dañados';

    // Iniciar transacción
    $conn->begin_transaction();
    try {
        // Obtener el artículo
        $select_query = "SELECT * FROM articulos WHERE id_articulo = ?";
        $stmt = $conn->prepare($select_query);
        $stmt->bind_param("i", $id_articulo);
        $stmt->execute();
        $articulo = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$articulo) {
            echo json_encode(['success' => false, 'message' => 'Artículo no encontrado.']);
            $conn->rollback();
            exit;
        }

        // Verificar si ya existe en la tabla correspondiente
        $check_query = "SELECT * FROM $tabla WHERE id_articulo = ?";
        $stmt = $conn->prepare($check_query);
        $stmt->bind_param("i", $id_articulo);
        $stmt->execute();
        $exists = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if ($exists) {
            // Actualizar cantidad si ya existe
            $update_query = "UPDATE $tabla SET cantidad = cantidad + ?, fh = NOW(), estado = ? WHERE id_articulo = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("isi", $cantidad, $status, $id_articulo);
            $stmt->execute();
            $stmt->close();
        } else {
            // Insertar nuevo registro si no existe
            $insert_query = "INSERT INTO $tabla (id_articulo, nombre, categoria, cantidad, descripcion, imagen, estado, fh)
                            VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = $conn->prepare($insert_query);
            $stmt->bind_param(
                "ississs",
                $articulo['id_articulo'],
                $articulo['nombre'],
                $articulo['categoria'],
                $cantidad,
                $articulo['descripcion'],
                $articulo['imagen'],
                $status
            );
            $stmt->execute();
            $stmt->close();
        }

        // Restar cantidad del inventario
        if ($articulo['cantidad'] >= $cantidad) {
            $update_articulos_query = "UPDATE articulos SET cantidad = cantidad - ? WHERE id_articulo = ?";
            $stmt = $conn->prepare($update_articulos_query);
            $stmt->bind_param("ii", $cantidad, $id_articulo);
            $stmt->execute();
            $stmt->close();

            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Estado actualizado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Cantidad insuficiente en inventario.']);
            $conn->rollback();
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Datos incompletos.']);
}
?>
