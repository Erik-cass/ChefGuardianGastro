<?php
include_once("../includes/config.php");

header('Content-Type: application/json'); // Asegura que la respuesta sea JSON
$response = ['status' => 'error', 'message' => 'Ocurrió un error inesperado.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar que los datos necesarios están presentes
    if (!isset($_POST['currentGroup'], $_POST['newGroup'], $_POST['newSemester'])) {
        $response['message'] = 'Faltan datos requeridos.';
        echo json_encode($response);
        exit;
    }

    $currentGroup = trim($_POST['currentGroup']);
    $newGroup = trim($_POST['newGroup']);
    $newSemester = trim($_POST['newSemester']);

    // Validar que los campos no están vacíos
    if (empty($currentGroup) || empty($newGroup) || empty($newSemester)) {
        $response['message'] = 'Todos los campos son obligatorios.';
        echo json_encode($response);
        exit;
    }

    // Verificar si el grupo actual existe
    $checkQuery = "SELECT COUNT(*) FROM alumnos WHERE grupo = ?";
    $stmt = $conn->prepare($checkQuery);
    if ($stmt) {
        $stmt->bind_param("s", $currentGroup);
        $stmt->execute();
        $stmt->bind_result($exists);
        $stmt->fetch();
        $stmt->close();

        if ($exists > 0) {
            // Actualizar el grupo y cuatrimestre
            $updateQuery = "UPDATE alumnos SET grupo = ?, cuatrimestre = ? WHERE grupo = ?";
            $stmt = $conn->prepare($updateQuery);

            if ($stmt) {
                $stmt->bind_param("sss", $newGroup, $newSemester, $currentGroup);

                if ($stmt->execute()) {
                    $response['status'] = 'success';
                    $response['message'] = 'Datos actualizados correctamente.';
                } else {
                    $response['message'] = 'Error al actualizar los datos.';
                }

                $stmt->close();
            } else {
                $response['message'] = 'Error al preparar la consulta de actualización.';
            }
        } else {
            $response['message'] = 'El grupo que desea actualizar no existe.';
        }
    } else {
        $response['message'] = 'Error al preparar la consulta de verificación.';
    }
} else {
    $response['message'] = 'Método no permitido.';
}

$conn->close();
echo json_encode($response); // Enviar la respuesta en formato JSON
