<?php
include '../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar si los datos están presentes
    if (!isset($_POST['id_pedido'], $_POST['estado'])) {
        echo "Faltan datos necesarios para actualizar.";
        exit;
    }

    $id_pedido = intval($_POST['id_pedido']);
    $estado = $_POST['estado'];
    $observaciones = isset($_POST['observaciones']) ? $_POST['observaciones'] : null;

    // Inicializar fechas
    $fecha_entrega = null;
    $fecha_limite = null;
    $fecha_devolucion = null;

    // Calcular las fechas según el estado
    date_default_timezone_set('America/Mexico_City');
    if ($estado === 'pendiente por devolver') {
        $fecha_entrega = date('Y-m-d H:i:s'); // Fecha actual como fecha de entrega
        $fecha_limite = date('Y-m-d H:i:s', strtotime('+5 days')); // Fecha límite = Fecha de entrega + 5 días
    } elseif ($estado === 'devuelto') {
        $fecha_devolucion = date('Y-m-d H:i:s'); // Fecha actual como fecha de devolución
    }

    // Actualizar la tabla `pedidos`
    $query = "
        UPDATE pedidos 
        SET estado = ?, 
            observaciones = ?, 
            fecha_entrega = ?, 
            fecha_limite = ?, 
            fecha_devolucion = ? 
        WHERE id_pedido = ?
    ";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssssi", $estado, $observaciones, $fecha_entrega, $fecha_limite, $fecha_devolucion, $id_pedido);

    if ($stmt->execute()) {
        // Bloquear al alumno si no devuelve materiales
        if ($estado === 'pendiente por devolver') {
            $query_block = "
                UPDATE alumnos 
                SET bloqueado = 1 
                WHERE id_usuarios = (
                    SELECT id_usuarios 
                    FROM pedidos 
                    WHERE id_pedido = ?
                )
            ";
            $stmt_block = $conn->prepare($query_block);
            $stmt_block->bind_param("i", $id_pedido);
            $stmt_block->execute();
            $stmt_block->close();
        }

        // Desbloquear al alumno si devuelve los materiales
        if ($estado === 'devuelto') {
            $query_unblock = "
                UPDATE alumnos 
                SET bloqueado = 0 
                WHERE id_usuarios = (
                    SELECT id_usuarios 
                    FROM pedidos 
                    WHERE id_pedido = ?
                )
            ";
            $stmt_unblock = $conn->prepare($query_unblock);
            $stmt_unblock->bind_param("i", $id_pedido);
            $stmt_unblock->execute();
            $stmt_unblock->close();
        }

        echo "Actualización exitosa";
    } else {
        echo "Error al actualizar: " . $conn->error;
    }
    $stmt->close();

    // Obtener el id_usuarios y fecha (fh) del pedido para redirigir correctamente
    $query_get_usuario = "SELECT id_usuarios, fh FROM pedidos WHERE id_pedido = ?";
    $stmt_get_usuario = $conn->prepare($query_get_usuario);
    $stmt_get_usuario->bind_param("i", $id_pedido);
    $stmt_get_usuario->execute();
    $stmt_get_usuario->bind_result($id_usuarios, $fh);
    $stmt_get_usuario->fetch();
    $stmt_get_usuario->close();

    // Redirigir de vuelta a la página de requisiciones con los parámetros correctos
    header("Location: requisicion.php?id_usuarios=$id_usuarios&fh=" . urlencode($fh));
    exit();
}
?>
