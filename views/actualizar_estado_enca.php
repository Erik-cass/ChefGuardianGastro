<?php
include '../includes/config.php';

// Obtener los datos enviados en la solicitud
$data = json_decode(file_get_contents('php://input'), true);

// Log de depuración para verificar los datos recibidos
error_log("Datos recibidos: " . print_r($data, true));

if (isset($data['id']) && isset($data['cantidad']) && isset($data['status'])) {
    $id_articulo = $data['id'];
    $cantidad = $data['cantidad'];
    $status = $data['status'];

    // Determinar la tabla en función del estado
    $tabla = ($status === 'perdido') ? 'articulos_perdidos' : 'articulos_dañados';

    try {
        // Obtener los detalles del artículo desde la tabla `articulos`
        $select_query = "SELECT * FROM articulos WHERE id_articulo = ?";
        $stmt = $conn->prepare($select_query);
        $stmt->bind_param("i", $id_articulo);
        $stmt->execute();
        $result = $stmt->get_result();
        $articulo = $result->fetch_assoc();
        $stmt->close();

        // Log de depuración para verificar los datos del artículo
        error_log("Detalles del artículo: " . print_r($articulo, true));

        if ($articulo) {
            // Verificar si el artículo ya existe en la tabla de destino
            $check_query = "SELECT * FROM $tabla WHERE id_articulo = ?";
            $stmt = $conn->prepare($check_query);
            $stmt->bind_param("i", $id_articulo);
            $stmt->execute();
            $result = $stmt->get_result();
            $exists = $result->fetch_assoc();
            $stmt->close();

            if ($exists) {
                // Actualizar la cantidad del artículo en la tabla correspondiente
                $update_query = "UPDATE $tabla SET cantidad = cantidad + ?, fh = NOW(), estado = ? WHERE id_articulo = ?";
                $stmt = $conn->prepare($update_query);
                $stmt->bind_param("isi", $cantidad, $status, $id_articulo);
                $update_success = $stmt->execute();
                error_log("Actualización en $tabla: " . ($update_success ? "éxito" : "fallo") . " - Error: " . $stmt->error);
                $stmt->close();
            } else {
                // Insertar los detalles del artículo en la tabla correspondiente
                $insert_query = "INSERT INTO $tabla (id_articulo, nombre, cantidad, descripcion, imagen, estado, fh) VALUES (?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($insert_query);
                $stmt->bind_param("isisss", $articulo['id_articulo'], $articulo['nombre'], $cantidad, $articulo['descripcion'], $articulo['imagen'], $status);
                $insert_success = $stmt->execute();
                error_log("Inserción en $tabla: " . ($insert_success ? "éxito" : "fallo") . " - Error: " . $stmt->error);
                $stmt->close();
            }

            // Actualizar la cantidad de artículos en la tabla `articulos`
            $update_query = "UPDATE articulos SET cantidad = cantidad - ? WHERE id_articulo = ?";
            $stmt = $conn->prepare($update_query);
            $stmt->bind_param("ii", $cantidad, $id_articulo);
            $update_success = $stmt->execute();
            error_log("Actualización en articulos: " . ($update_success ? "éxito" : "fallo") . " - Error: " . $stmt->error);
            $stmt->close();

            if ($update_success) {
                echo json_encode(['success' => true]);
            } else {
                error_log("Error en la actualización de la tabla de artículos.");
                echo json_encode(['success' => false, 'message' => 'Error al actualizar']);
            }
        } else {
            error_log("Artículo no encontrado");
            echo json_encode(['success' => false, 'message' => 'Artículo no encontrado']);
        }
    } catch (Exception $e) {
        error_log("Excepción: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Excepción: ' . $e->getMessage()]);
    }
} else {
    error_log("Datos incompletos: id, cantidad o status no establecidos");
    echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
}
?>
