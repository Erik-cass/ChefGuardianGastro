<?php

session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];

if( $validar == null || $validar = ''){

    header("Location: ../includes/login.php");
    die();
    
    

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Alumno</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <!-- Agrega los estilos de Bootstrap -->
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <!-- Incluir Font Awesome -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">

      <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
      <style>
        
        .contenido-principal {
          background-color: #f4f4f9;
          border-radius: 20px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          padding: 20px;
          margin-top: 20px;
        }
        .titulo {
          text-align: center; /* Centrar el texto */
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
            <div class="contenido-principal mt-4">
                

    <div class="container mt-7">

        <h4 class="text-center border-bottom"><strong>REGISTRAR ENCARGADOS</strong></h4>
        <br>
        <form action="register.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Escribe el nombre completo" pattern= "[A-Za-z\s]+" title="Solo se permiten letras y espacios" required>
                    </div>
                </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="matricula">Numero de Empleado:</label>
                            <input type="text" class="form-control" id="matricula" name="matricula" placeholder="Escribe numero de empleado" pattern="\d{4,15}" title="La matrícula debe tener entre 4 y 15 números" maxlength="15" required inputmode="numeric">
                        </div>
                    </div>
                    </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control font-weight" id="email" name="email" placeholder="Email del Encargado" required="">

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password"> Contraseña (Predeterminada por sistema):</label>
                        <input type="text" class="form-control font-weight-bold" placeholder="12345678" readonly />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="row">
                    <div class="form-group" style="display: none">
                        <label for="rol">Rol</label>
                        <select name="rol" class="form-control" readonly>
                            <option value="encargado" selected="">Encargado</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="d-grid gap-2 col-3 mx-auto">
              <button  type="submit" class="btn btn-primary">REGISTRAR</button>
            </div>
        </form>
    </div>
</div>
</main>

    <!-- jQuery first, the Popper.js, then Bootstrap JS --> 
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

<?php include '../includes/footer.php'; ?>


</body>
</html>