<?php
session_start();
include '../includes/config.php';

$data = json_decode(file_get_contents("php://input"), true);

$id_usuarios = isset($_SESSION['id_usuarios']) ? $_SESSION['id_usuarios'] : '';
$nombre_alumno = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : '';
$asignatura = isset($data['asignatura']) ? $data['asignatura'] : '';
$profesor = isset($data['profesor']) ? $data['profesor'] : '';

if (!empty($data['cart']) && !empty($id_usuarios) && !empty($nombre_alumno) && !empty($asignatura) && !empty($profesor)) {
    // Iniciar una transacción para garantizar integridad de datos
    $conn->begin_transaction();

    try {
        // Preparar la consulta para insertar en pedidos
        $stmt = $conn->prepare("INSERT INTO pedidos (id_articulo, nombre, cantidad, descripcion, imagen, fh, id_usuarios, nombre_alumno, asignatura, profesor, categoria) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        foreach ($data['cart'] as $item) {
            $id_articulo = isset($item['id_articulo']) ? $item['id_articulo'] : '';
            $nombre = isset($item['nombre']) ? $item['nombre'] : '';
            $categoria = isset($item['categoria']) ? $item['categoria'] : '';
            $cantidad = isset($item['cantidad_en_carrito']) ? $item['cantidad_en_carrito'] : '';
            $descripcion = isset($item['descripcion']) ? $item['descripcion'] : '';
            $imagen = isset($item['imagen']) ? $item['imagen'] : '';
            date_default_timezone_set('America/Mexico_City'); // Establecer zona horaria
            $fh = date('Y-m-d H:i:s');

            // Insertar el pedido en la base de datos
            $stmt->bind_param('isisssissss', $id_articulo, $nombre, $cantidad, $descripcion, $imagen, $fh, $id_usuarios, $nombre_alumno, $asignatura, $profesor, $categoria);

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            // Descontar del inventario en la tabla articulos
            $updateStmt = $conn->prepare("UPDATE articulos SET cantidad = cantidad - ? WHERE id_articulo = ?");
            $updateStmt->bind_param('ii', $cantidad, $id_articulo);

            if (!$updateStmt->execute()) {
                throw new Exception($updateStmt->error);
            }
        }

        // Confirmar la transacción
        $conn->commit();
        echo json_encode(['success' => true]);

    } catch (Exception $e) {
        // Revertir la transacción en caso de error
        $conn->rollback();
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Datos inválidos']);
}
?>
