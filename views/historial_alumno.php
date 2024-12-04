<?php
session_start();
error_reporting(0);

$validar = $_SESSION['matricula'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

include '../includes/config.php';

$query = "SELECT * FROM pedidos";
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
     <!-- DataTables CSS -->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

  <!-- DataTables Responsive CSS-->
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">

  <!-- DataTables JS-->
  <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

  <!-- DataTables Responsive -->
  <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap.min.js"></script>
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

<h4 class="text-center border-bottom"><strong>HISTORIAL DE SOLICITUDES</strong></h4>
                <div class="table-responsive">
                    <table id="tabla" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
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
                                    echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
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
<div class="p-3"></div>
<!-- jQuery, DataTables, and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function(){
        $('#tabla').DataTable({
            responsive: true,
            "language": {
                "search": "Buscar: ",
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
