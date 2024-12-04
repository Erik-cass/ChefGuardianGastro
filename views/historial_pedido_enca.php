<?php
session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

include '../includes/config.php';

// Consulta actualizada con DISTINCT y ORDER BY
$query = "
    SELECT DISTINCT 
        pedidos.id_usuarios,
        alumnos.matricula,
        alumnos.grupo,
        pedidos.nombre_alumno,
        pedidos.fh
    FROM 
        pedidos
    JOIN 
        alumnos ON pedidos.id_usuarios = alumnos.id_usuarios
    ORDER BY 
        pedidos.fh DESC
";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Requisiciones</title>
    <!-- Agrega los estilos de Bootstrap -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Incluir Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">
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

          <h4 class="text-center"><strong>HISTORIAL DE SOLICITUDES PENDIENTES</strong></h4>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Matrícula</th>
                                <th>Nombre</th>
                                <th>Grupo</th>
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
                                    echo "<td><a href='requisicion_enca.php?id_usuarios=" . htmlspecialchars($row['id_usuarios']) . "' class='btn btn-info'>Ver Requisición</a></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No se encontraron solicitudes pendientes.</td></tr>";
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
