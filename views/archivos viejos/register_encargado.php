<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Encargado</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <!-- Agrega los estilos de Bootstrap -->
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            background-color: #dcdcdc;
            padding: 20px;
            min-height: calc(100vh - 50px - 70px); /* 50px del navbar y 70px del footer */
            margin-top: 18px; /* Agrega un margen superior de 20px */
            margin-bottom: 20px; /* Agrega un margen inferior de 20px */
        }
        .titulo {
          text-align: center; /* Centrar el texto */
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
    <?php include '../includes/_header.php'; ?>

<main role="main" class="col-md ml-sm-auto col-lg">
<article class="contenido-principal rounded-lg">
    <div class="container mt-3">
        <h2>Registrar Encargado</h2>
        <form action="../register_encargado.php" method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombre">Nombre del Alumno:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Escribe el nombre completo" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="matricula">Matricula:</label>
                        <input type="number" class="form-control" id="matricula" name="matricula" placeholder="Escribe la matricula" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control font-weight-bold" id="email" name="email" placeholder="Email del Encargado" readonly>

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
                    <div class="form-group" style="display: none">
                        <label for="rol">Rol</label>
                        <select name="rol" class="form-control" readonly>
                            <option value="encargado" selected="">Alumno</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Registrar</button>
        </form>
    </div>
</article>
</main>
</div>
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


</body>
</html>