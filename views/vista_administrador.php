<?php
session_start();
error_reporting(0);

$validar = $_SESSION['matricula'];
if ($validar == null || $validar == '') {
  header("Location: ../includes/login.php");
  die();
}

include '../includes/config.php';

$sql_perdidos = "SELECT COUNT(*) as total FROM articulos_perdidos";
$result_perdidos = $conn->query($sql_perdidos);
$row_perdidos = $result_perdidos->fetch_assoc();
$total_perdidos = $row_perdidos['total'];

$sql_dañados = "SELECT COUNT(*) as total FROM articulos_dañados";
$result_dañados = $conn->query($sql_dañados);
$row_dañados = $result_dañados->fetch_assoc();
$total_dañados = $row_dañados['total'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Inventario Gastronomía</title>
  <!-- Bootstrap y Font Awesome -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">
  <style>
    .contenido-principal {
      background-color: #f4f4f9;
      border-radius: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-top: 20px;
    }
    .titulo, .titulo2 {
      text-align: center;
      font-weight: bold;
      color: #333;
    }
    .card-img-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      height: 150px;
      overflow: hidden;
      width: 100px;
    }
    .card-img-top {
      height: auto;
      max-height: 100%; /* Para que la imagen no salga del contenedor */
      width: auto;
      max-width: 100%; /* Limita el ancho de la imagen dentro del contenedor */
      object-fit: cover; /* Mantiene la imagen ajustada sin deformación */
    }
    .notification {
      background-color: #28a745;
      color: white;
      padding: 5px 12px;
      border-radius: 20px;
      position: absolute;
      top: 10px;
      right: 10px;
      font-size: 14px;
    }
    .icon-tablet {
            width: 100%;
            height: 120px;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, background-color 0.3s ease;
            text-decoration: none;
            color: #333;
            font-size: 14px;
            font-weight: bold;
        }
  </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Incluir el menú lateral -->
        <?php include '../includes/_header.php'; ?>
        <?php include "fecha.php"?>

        <main role="main" class="col-md-9 col-lg-10 px-4">
            <div class="contenido-principal mt-4 text-center">
                <h3 class="titulo">Administrador: <?php echo $_SESSION['nombre']; ?></h3>
                <h5><strong>Mexico, <?php echo fecha(); ?> </strong></h3>
                <div class="reloj">
                   <h5><strong>Hora </strong> </h5>
                   <h5><strong id="tiempo"> 00 : 00 : 00</strong></h5>
                </div>
                
            </div>
            <br>

            <div class="container mt-7">

              <h3 class="titulo2 mt-3">Inventario Gastronomía</h3>
                <div class="row">
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card bg-light border-primary">
                            <div class="card-img-wrapper">
                                <img src="https://img.icons8.com/?size=100&id=43SyqRnwJnKG&format=png&color=000000" class="card-img-top" alt="Imagen de Artículos Donados">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title">Artículos Donados</h5>
                                <a href="articulos_donados_admi.php" class="btn btn-primary">Ver Inventario</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card bg-light border-primary position-relative">
                            <div class="card-img-wrapper">
                                <img src="https://img.icons8.com/?size=100&id=oK9NQD5C4aIb&format=png&color=000000" class="card-img-top" alt="Imagen de Artículos Perdidos">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title">Artículos Perdidos</h5>
                                <a href="vista_articulos_perdidos.php" class="btn btn-primary">Ver Inventario</a>
                            </div>
                            <div class="notification"><?php echo $total_perdidos; ?></div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card bg-light border-primary position-relative">
                            <div class="card-img-wrapper">
                                <img src="https://img.icons8.com/?size=100&id=34639&format=png&color=000000" class="card-img-top" alt="Imagen de Artículos Dañados">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title">Artículos Dañados</h5>
                                <a href="vista_articulos_dañados.php" class="btn btn-primary">Ver Inventario</a>
                            </div>
                            <div class="notification"><?php echo $total_dañados; ?></div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card bg-light border-primary position-relative">
                            <div class="card-img-wrapper">
                                <img src="https://img.icons8.com/?size=100&id=21063&format=png&color=000000" class="card-img-top" alt="Imagen de Artículos Dañados">
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-title">Reporte Deudores</h5>
                                <a href="reporte_alumnos_deudores.php" class="btn btn-primary">Ver Inventario</a>
                            </div>
                            <div class="notification"><?php echo $total_dañados; ?></div>
                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/footer.php'; ?>


<!-- Scripts de Bootstrap y jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../js/reloj.js"></script>
</body>
</html>


