<?php

session_start();
error_reporting(0);

$validar = $_SESSION['matricula'];

if( $validar == null || $validar = ''){

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
  <title>Encargado</title>
  
  <!-- Agrega los estilos de Bootstrap -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Agrega los estilos de Bootstrap -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
        <?php include "fecha.php"?>

        <main role="main" class="col-md-9 col-lg-10 px-4">
            <div class="contenido-principal mt-4 text-center">
                <h3 class="titulo">Encargado: <?php echo $_SESSION['nombre']; ?></h3>
                <h5><strong>Mexico, <?php echo fecha(); ?> </strong></h3>
                <div class="reloj">
                   <h5><strong>Hora </strong> </h5>
                   <h5><strong id="tiempo"> 00 : 00 : 00</strong></h5>
                </div>
                
            </div>
            <br>

            <div class="container mt-7">

              <h3 class="titulo2 mt-3 border-bottom">Inventario Gastronomía</h3>
        </div>
        <!-- Contenido de la Página -->
        <div class="container mt-5">
          <div class="row">
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card bg-light mb-3 border-primary mb-3">
                <div class="card-img-wrapper">
                  <img src="../uploaded_images/icono.png" class="card-img-top" alt="Imagen de Artículos Perdidos">
                </div>
                <div class="card-body text-center">
                  <h5 class="card-title">Artículos Donados</h5>
                  <a href="articulos_donados_enca.php" class="btn btn-primary">Ver Inventario</a>
                </div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card bg-light mb-3 border-primary mb-3 position-relative">
                <div class="card-img-wrapper">
                  <img src="../uploaded_images/icono.png" class="card-img-top" alt="Imagen de Artículos Perdidos">
                </div>
                <div class="card-body text-center">
                  <h5 class="card-title">Artículos Perdidos</h5>
                  <a href="vista_perdidos_enca.php" class="btn btn-primary">Ver Inventario</a>
                </div>
                <div class="notification"><?php echo $total_perdidos; ?></div>
              </div>
            </div>
            <div class="col-md-6 col-lg-4 mb-4">
              <div class="card bg-light mb-3 border-primary mb-3 position-relative">
                <div class="card-img-wrapper">
                  <img src="../uploaded_images/dañado.png" class="card-img-top" alt="Imagen de Artículos Perdidos">
                </div>
                <div class="card-body text-center">
                  <h5 class="card-title">Artículos Dañados</h5>
                  <a href="vista_dañados_enca.php" class="btn btn-primary">Ver Inventario</a>
                </div>
                <div class="notification"><?php echo $total_dañados; ?></div>
              </div>
            </div>
          </div>
        </div>
      </article>
    </main>
  </div>
</div>

<!-- Scripts de Bootstrap y jQuery (Asegúrate de incluir jQuery antes de Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
<script src="../js/reloj.js"></script>
<?php include '../includes/footer.php'; ?>
</body>
</html>