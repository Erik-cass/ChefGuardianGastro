<?php
session_start();
error_reporting(0);

$validar = $_SESSION['matricula'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

include '../includes/config.php';

$query = "SELECT * FROM articulos_perdidos";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Artículos Perdidos</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-dS7XT0K7bdehd3Hhh1DsV2/zXt7mK3uK7F7cZdYYeBfDrDfw8Xls1Tm0/77vOVLFuMT/NUpg3FAmXWsPJsW9cA==" crossorigin="anonymous" />
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css">
         <!-- Incluir Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <style>
    /* Agrega estilos personalizados si es necesario */
    .navbar-brand {
      padding: 0;
    }
    .navbar-brand img {
      max-height: 45px;
    }
    .contenido-principal {
      background-color: #f5f5f5;
      padding: 20px;
    }
    .titulo {
      text-align: center; /* Centrar el texto */
    }
    </style>    
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="#">
    <img src="../uploaded_images/gastro_logo.jpg" alt="Logo">
  </a>
  <!-- Mostrar solo el primer nombre en dispositivos pequeños -->
  <?php 
    $nombres = explode(" ", $_SESSION['matricula']);
    $primer_nombre = $nombres[0];
  ?>
  <h4 class="text-light d-none d-lg-block">Bienvenido Encargado <?php echo $_SESSION['matricula']; ?></h4>
  <h4 class="text-light d-lg-none"><?php echo $primer_nombre; ?></h4>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link" href="../includes/_sesion/cerrarSesion.php">
        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión <!-- Agregar icono de cerrar sesión -->
      </a>
    </li>
  </ul>
</nav>

<br>
<div class="container-fluid">
    <div class="row">
        <nav class="col-md-2 navbar navbar-expand-md navbar-dark bg-danger fixed-medium" style="height: 50vh; overflow-y: auto;">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="sidebarMenu">
        <div class="sidebar-sticky">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="nav-link active text-light" href="encargado.php">INICIO</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-light" href="registeralumenca.html">Registrar Alumnos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-light" href="form1.html">Registrar Articulos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-light" href="#">Lista de Alumnos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-light" href="rejilla_encargado.php">Lista de Articulos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-light" href="encargado.html">HISTORIAL DE SOLICITUDES</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10">
    <article class="contenido-principal">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Lista de Artículos Perdidos</h1>
                    <a href='imprimir_articulo_perdido.php' class='btn btn-primary' role='button'>Imprimir <i class='fas fa-print'></i></a>
                </div>
                <div class="table-responsive">
                    <table id="tablaArticulosPerdidos" class="table table-striped table-bordered">
                        <thead class="thead-dark">
                            <tr>
                                <th>Imagen</th>
                                <th>Nombre</th>
                                <th>Descripcion</th>
                                <th>Cantidad</th>
                                <th>Fecha</th>
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
                                    echo "<td>" . htmlspecialchars($row['estado']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No se encontraron artículos perdidos.</td></tr>";
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
</body>
</html>
