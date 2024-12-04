<?php
session_start();
error_reporting(0);

$validar = $_SESSION['matricula'];
if ($validar == null || $validar == '') {
    header("Location: ./index.php");
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
    <title>Panel de Administrador</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para íconos -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            background-color: #ffffff;
        }
        .contenido-principal {
            background-color: #f4f4f9;
            border-radius: 50px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        .titulo {
            text-align: left;
            font-weight: bold;
            color: #333;
        }
        .card-img-top {
          width: 30%;
          height: auto;
          max-height: 140px; /* Ajusta esta altura según sea necesario */
          object-fit: cover; /* Esto asegura que la imagen mantenga sus proporciones */
        }
        .card-img-wrapper {
          display: flex;
          justify-content: center;
          align-items: center;
          height: 150px; /* Ajusta esta altura según sea necesario */
          overflow: hidden;
        }
        .notification {
          background-color: #28a745; /* Color de fondo rojo */
          color: white; /* Color del texto */
          padding: 5px 12px;
          border-radius: 20px;
          position: absolute;
          top: 10px;
          right: 10px;
          font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        <!-- Incluir el menú lateral -->
        <?php include '../includes/_header.php'; ?>
        <?php include "fecha.php"?>
        <!-- Contenedor principal para el contenido -->
        <main role="main" class="col-md-9 col-lg-10 px-4">
            <div class="contenido-principal mt-4">
                <h3 class="titulo">Administrador: <?php echo $_SESSION['nombre']; ?></h3>
                <p><strong> Mexico <?php echo fecha(); ?> </strong> </p>
                          
                        <div class="reloj">
                           <h5>Hora</h5>
                          <h5><span id="tiempo"> 00 : 00 : 00</span></h5>
                        </div>
                    </div>

                    <!-- Aquí puedes agregar tu contenido dinámico -->
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Footer -->
<footer class="footer bg-dark text-white text-center py-3">
    &copy; UniChef 2024 | Jatziry Diaz Gonzalez 21307014, Elelí Rubio Bello 21307025, Said Haziel Camacho Guevara 21307010, Ulises Cortéz Alvarez 21307036.
</footer>

<!-- Scripts de Bootstrap y jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../css/reloj.js"></script>

</body>
</html>
