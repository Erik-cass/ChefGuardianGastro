<?php
session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

include '../includes/config.php';

// Consulta para obtener todas las solicitudes únicas, ordenadas por fecha
$query = "
    SELECT DISTINCT 
        pedidos.fh AS fecha_hora,
        pedidos.id_usuarios,
        alumnos.matricula,
        alumnos.grupo,
        pedidos.nombre_alumno
    FROM 
        pedidos
    JOIN 
        alumnos ON pedidos.id_usuarios = alumnos.id_usuarios
    ORDER BY 
        pedidos.fh ASC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Requisiciones</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css">

    <style>
        .contenido-principal {
          background-color: #f4f4f9;
          border-radius: 20px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          padding: 20px;
          margin-top: 20px;
        }
    </style>    
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include '../includes/_header.php'; ?>
        <main role="main" class="col-md-9 col-lg-10 px-4">
            <div class="contenido-principal mt-4">
                <h4 class="text-center border-bottom"><strong>HISTORIAL DE SOLICITUDES PENDIENTES</strong></h4>
                <div class="table-responsive">
                    <table id="tablaArticulos" class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Matrícula</th>
                                <th>Nombre</th>
                                <th>Grupo</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['matricula']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['nombre_alumno']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['grupo']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['fecha_hora']) . "</td>";
                                    echo "<td><a href='requisicion.php?id_usuarios=" . htmlspecialchars($row['id_usuarios']) . "&fh=" . urlencode($row['fecha_hora']) . "' class='btn btn-info'>Ver Requisición</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No se encontraron solicitudes pendientes.</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
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
