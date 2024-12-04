<?php
session_start();
error_reporting(0);

// Verificar si la sesión está activa
$validar = $_SESSION['nombre'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

include '../includes/config.php';

// Obtener los parámetros de la URL
$id_usuarios = isset($_GET['id_usuarios']) ? intval($_GET['id_usuarios']) : 0;
$fecha_hora = isset($_GET['fh']) ? urldecode($_GET['fh']) : '';

// Validar que los parámetros no estén vacíos
if ($id_usuarios <= 0 || empty($fecha_hora)) {
    echo "Parámetros inválidos.";
    exit;
}

// Consulta para obtener la información del pedido específico
$query = "
    SELECT 
        pedidos.id_pedido,
        pedidos.nombre_alumno,
        pedidos.fh,
        pedidos.fecha_entrega,
        pedidos.fecha_limite,
        pedidos.nombre,
        pedidos.descripcion,
        pedidos.cantidad,
        pedidos.imagen,
        pedidos.estado,
        pedidos.observaciones
    FROM pedidos
    WHERE pedidos.id_usuarios = ? AND pedidos.fh = ?
";

// Preparar la consulta SQL
$stmt = $conn->prepare($query);
$stmt->bind_param("is", $id_usuarios, $fecha_hora); // 'i' para el id_usuarios (entero), 's' para fecha_hora (cadena)

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "No se encontró el pedido.";
    exit;
}

// Si se encontró el pedido, obtén los datos
$pedido = $result->fetch_assoc();
$nombre_alumno = $pedido['nombre_alumno']; // Aquí definimos correctamente la variable

// Cerrar la consulta y la conexión
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de requisiciones</title>
    <!-- Agrega los estilos de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css">
    <style>
        /* Estilos adicionales */
        .contenido-principal {
            background-color: #f4f4f9;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        .titulo {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Incluir el menú lateral -->
        <?php include '../includes/_header.php'; ?>

        <main role="main" class="col-md-9 col-lg-10 px-4">
            <div class="contenido-principal mt-4">                 
                <div class="container mt-7">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        <h3><strong>Materiales solicitados por:</strong> <?php echo htmlspecialchars($nombre_alumno); ?></h3>
                        <a href='imprimir_requisicion.php?id=<?php echo htmlspecialchars($id_usuarios); ?>' class='btn btn-primary' role='button'>Imprimir <i class='fas fa-print'></i></a>       
                    </div>

                    <div class="table-responsive">
                        <table id="tablaArticulos" class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Imagen</th>
                                    <th>Articulos</th>
                                    <th>Descripcion</th>
                                    <th>Cantidad</th>
                                    <th>Fecha</th>
                                    <th>Nombre</th>
                                    <th>Observaciones</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $result->data_seek(0); // Reiniciar el puntero
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td><img src='" . htmlspecialchars($row['imagen']) . "' alt='Imagen' class='img-thumbnail' style='width: 90px; height: 90px;'></td>";
                                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['cantidad']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['fh']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['nombre_alumno']) . "</td>";
                                            
                                            echo "<td>";
                                            echo "<form action='actualizar_estado_pedido.php' method='POST'>";
                                            echo "<input type='text' name='observaciones' class='form-control' value='" . htmlspecialchars($row['observaciones']) . "'>";
                                            echo "<input type='hidden' name='id_pedido' value='" . htmlspecialchars($row['id_pedido']) . "'>";
                                            echo "<select name='estado' class='form-control'>";
                                            echo "<option value='pendiente por entregar'" . ($row['estado'] == 'pendiente por entregar' ? ' selected' : '') . ">Pendiente por entregar</option>";
                                            echo "<option value='pendiente por devolver'" . ($row['estado'] == 'pendiente por devolver' ? ' selected' : '') . ">Pendiente por devolver</option>";
                                            echo "<option value='devuelto'" . ($row['estado'] == 'devuelto' ? ' selected' : '') . ">Devuelto</option>";
                                            echo "</select>";
                                            echo "<button type='submit' class='btn btn-primary mt-2'>Actualizar</button>";
                                            echo "</form>";
                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8'>No se encontraron artículos.</td></tr>";
                                    }
                                    $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function(){
        $('#tablaArticulos').DataTable({
            responsive: true,
            "language": {
                "search": "Buscar: ",
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron registros",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "paginate": {
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            }
        });
    });
</script>

<?php include '../includes/footer.php'; ?>

</body>
</html>
