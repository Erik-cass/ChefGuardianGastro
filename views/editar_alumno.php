<?php

include_once("../includes/config.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM alumnos WHERE id_usuarios = $id";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Registro no encontrado";
        exit();
    }
} else {
    echo "ID no proporcionado";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $matricula = $_POST['matricula'];
    $grupo = $_POST['grupo'];
    $cuatrimestre = $_POST['cuatrimestre'];

    // Verificar si la matrícula ya existe en la base de datos
    $check_query = "SELECT COUNT(*) as count FROM alumnos WHERE matricula = '$matricula' AND id_usuarios != $id";
    $check_result = $conn->query($check_query);
    $check_row = $check_result->fetch_assoc();
    $count = $check_row['count'];

    if ($count > 0) {
        echo "<script>
            alert('Esa matrícula ya está en uso.');
            window.location.href = './editar_alumno.php?id=$id'; 
        </script>";
        exit();
    }

    // Si la matrícula no está duplicada, procede con la actualización
    $query = "UPDATE alumnos SET nombre = '$nombre', email = '$email', matricula = '$matricula', grupo = '$grupo', cuatrimestre = '$cuatrimestre' WHERE id_usuarios = $id";
    if ($conn->query($query) === TRUE) {
        header('Location: general_alumnos.php');
        exit();
    } else {
        echo "Error actualizando registro: " . $conn->error;
    }
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editar Registro</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
 <div class="container mt-5">
        <h2 class="text-center">Editar Registro</h2>
        <div class="form-container">
        <form action=" " method="POST">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $row['nombre']; ?>" pattern="[A-Za-z\s]+" title="Solo permite letras y espacios"  required>

                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="matricula">Matricula:</label>
                        <input type="text" class="form-control" id="matricula" name="matricula" value="<?php echo $row['matricula']; ?>" pattern="[0-9]{8}" title="La matrícula debe tener exactamente 8 numeros" maxlength="8" required>
                    </div>

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control font-weight-bold" id="email" name="email" placeholder="Email del alumno" value="<?php echo $row['email']; ?>" readonly>

                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="grupo">Grupo:</label>
                        <input type="text" class="form-control" id="grupo" name="grupo" placeholder="grupo" value="<?php echo $row['grupo']; ?>" required>
                    </div>
                </div>

            <div class="col-sm">
                <label>Cuatrimestre:</label>
                    <div class="form-group">
                    <select class="custom-select" name="cuatrimestre" required>
                    <option disabled placeholder="Cuatrimestre">Seleccionar cuatrimestre...</option>
                    <?php
            $cuatrimestres = [
                "1ero", "2do", "3ro", "4to", "5to",
                "6to - Estadías", "7mo", "8vo", "9no",
                "10mo", "11avo - Estadías"
            ];
            foreach ($cuatrimestres as $cuatrimestre) {
                $selected = ($row['cuatrimestre'] == $cuatrimestre) ? 'selected' : '';
                echo "<option value=\"$cuatrimestre\" $selected>$cuatrimestre</option>";
            }
            ?>
        </select>
    </div>
</div>
            <div class="row">
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
            <button type="submit" class="btn btn-success" >¡Guardar!</button>
           <!-- <button onclick="history.back()" class="btn btn-danger" >¡Regresar!</button> !-->
           <button onclick="window.location.href = 'general_alumnos.php'" class="btn btn-danger">¡Regresar!</button>
        </center>
    </form>
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
   </script> 
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>   

</body>
</html>