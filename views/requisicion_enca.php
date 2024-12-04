<?php
session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

include '../includes/config.php';

// Obtener el nombre del alumno y el id_usuarios desde la tabla 'pedidos'
$nombre_alumno_query = "SELECT nombre_alumno, id_usuarios FROM pedidos LIMIT 1"; // Ajusta la consulta según tu necesidad
$nombre_alumno_result = $conn->query($nombre_alumno_query);

if ($nombre_alumno_result->num_rows > 0) {
    $nombre_alumno_row = $nombre_alumno_result->fetch_assoc();
    $nombre_alumno = $nombre_alumno_row['nombre_alumno'];
    $id_usuarios = $nombre_alumno_row['id_usuarios']; // Guarda el id_usuarios
} else {
    $nombre_alumno = 'Nombre no definido';
    $id_usuarios = null; // Asigna un valor por defecto si no se encuentra
}

$query = "SELECT * FROM pedidos";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de requisiciones</title>
    <!-- Agrega los estilos de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
     <link rel="stylesheet" href="../css/estilo.css">    
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

            <!-- Usa id_usuarios en el enlace de impresión -->
            <a href='imprimir_requisicion_enca.php?id=<?php echo htmlspecialchars($id_usuarios); ?>' class='btn btn-primary' role='button'>Imprimir <i class='fas fa-print'></i></a>       
        </div>
        <div class="table-responsive">
            <table id="tablaArticulosPerdidos" class="table table-striped table-bordered">
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
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><img src='" . htmlspecialchars($row['imagen']) . "' alt='Imagen' class='img-thumbnail' style='width: 90px; height: 90px;'></td>";
                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cantidad']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['fh']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['nombre_alumno']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['observaciones']) . "</td>";
                            echo "<td>";
                            echo "<form action='actualizar_estado_pedido.php' method='POST'>";
                            echo "<input type='hidden' name='id_articulo' value='" . $row['id_articulo'] . "'>";
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
                        echo "<tr><td colspan='8'>No se encontraron artículos perdidos.</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </article>
</main>
    </div>
</div>

<!-- jQuery, DataTables, and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function(){
        $('#tablaArticulosPerdidos').DataTable({
            responsive: true,
            "language": {
                "search": "Buscar:",
                "searchPlaceholder": "Filtrar por columna...",
                "lengthMenu": "Mostrar _MENU_ registros por página",
                "zeroRecords": "No se encontraron registros",
                "info": "Mostrando página _PAGE_ de _PAGES_",
                "infoEmpty": "No hay registros disponibles",
                "infoFiltered": "(filtrados de un total de _MAX_ registros)",
                "paginate": {
                    "first": "Primero",
                    "last": "Último",
                    "next": "Siguiente",
                    "previous": "Anterior"
                }
            },
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Todos"]]
        });
    });
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
