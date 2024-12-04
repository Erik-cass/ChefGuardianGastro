<?php

session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];

if( $validar == null || $validar = ''){

    header("Location: ../includes/login.php");
    die();
    
    

}

$nombres = explode(" ", $_SESSION['nombre']);
$primer_nombre = $nombres[0];


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
      <link rel="stylesheet" href="../css/estilo.css">
      
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
        <h4 class="text-center border-bottom"><strong>REGISTRAR ALUMNOS</strong></h4>
        <form action="../register.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombre">Nombre del Alumno:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Escribe el nombre completo" pattern="[A-Za-z\s]+" title="Solo se permiten letras y espacios" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="matricula">Matricula:</label>
                        <input type="text" class="form-control" id="matricula" name="matricula" placeholder="Escribe la matricula" pattern="\d{8}" title="La matrícula debe tener exactamente 8 números" maxlength="8" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control font-weight-bold" id="email" name="email" placeholder="Email del alumno" readonly>

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
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="grupo">Grupo:</label>
                        <input type="text" class="form-control" id="grupo" name="grupo" placeholder="Escribe el grupo" required>
                    </div>
                </div>
                <div class="col-sm">
                    <label>Cuatrimestre:</label>
                    <div class="form-group">
                        <select  class="custom-select" name="cuatrimestre" required>
                            <option selected disabled value="">Seleccionar cuatrimestre...</option>
                            <option>1ero</option>
                            <option>2do</option>
                            <option>3ro</option>
                            <option>4to</option>
                            <option>5to</option>
                            <option>6to - Estadías</option>
                            <option>7mo</option>
                            <option>8vo</option>
                            <option>9no</option>
                            <option>10mo</option>
                            <option>11avo - Estadías</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group" style="display: none">
                        <label for="rol">Rol</label>
                        <select name="rol" class="form-control" readonly>
                            <option value="alumno" selected="">Alumno</option>
                        </select>
                    </div>
                </div>
            </div>
              <center>
                <button  type="submit" class="btn btn-primary">REGISTRAR</button>
              </center>
        </form>
            <br>
            <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#importModal">IMPORTAR DATOS CSV</button>
            <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg"> <!-- Ajuste para hacer el modal más grande -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="importModalLabel">IMPORTAR DATOS</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card">
                                <div class="card-header">
                                    IMPORTAR
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">Importar datos desde un archivo CSV hasta la BD</h5>
                                    <form action="../csv/importar_registro_enca.php" method="post" enctype="multipart/form-data">
                                        Seleccione el archivo Exel:
                                        <input type="file" name="file" accept=".xlsx">
                                        <input type="submit" class="btn btn-primary" value="Subir archivo" name="submit">
                                    </form>



                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


    <!-- jQuery first, the Popper.js, then Bootstrap JS --> 
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

    <script>
// Espera a que el contenido del documento se haya cargado antes de añadir los escuchadores de eventos 
document.addEventListener('DOMContentLoaded', function(){
    //Obtiene el elemento de entrada de la matricula por su ID de acuerdo al input
    var inputMatricula = document.getElementById('matricula');

    // Añade un escuchador de eventos que relaciona cada vez que el valor del campo de matricula cambia
    inputMatricula.addEventListener('input', function(){
        //Obtiene el valor actual del campo de matricula
        var matriculaValue = this.value;

        // Construye el email concatenando el calor de la matricula y el dominio web fijo 
        var emailValue = matriculaValue + '@utcgg.edu.mx';

        // Obtiene el elemento de entrada del email por su ID y actualiza su valor 
        document.getElementById('email').value = emailValue; 
    });
});

    </script>
<?php include '../includes/footer.php'; ?>

</body>
</html>