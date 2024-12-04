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
  <title>Total de Alumnos</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>

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


  <!-- Agrega los estilos de Bootstrap -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <!-- Incluir Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">

  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <link rel="stylesheet" href="../css/estilo.css">
  
</head>
<div class="container-fluid">
        <div class="row">
            <!-- Incluir el menú lateral -->
            <?php include '../includes/_header.php'; ?>

            <main role="main" class="col-md-9 col-lg-10 px-4">
                <div class="contenido-principal mt-4">
                    

        <div class="container mt-7">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h4 class="text-center"><strong>TOTAL DE ALUMNOS REGISTRADOS</strong></h4>
        <a href='imprimir_alumnos.php' class='btn btn-primary' role='button'>Imprimir <i class='fas fa-print'></i></a>  
        <form action="../csv/exportar_registro_alumnos.php">
          <button class="btn btn-secondary" type="submit">Exportar datos <i class='fas fa-print'></i></button>
        </div>
        <table id="tablaAlumnos" class= "table table-striped table-bordered dt-responsive nowrap" style="width:100%">
            <thead class="bg-dark text-white text-center">
                <tr>
                    <th>No</th>
                    <th>Matrícula</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Grupo</th>
                    <th>Cuatrimestre</th>
                    <th>Acciones</th> 
                </tr>                   
            </thead>
            <tbody class="text-center">
                <?php
                    // Configuracion de la BD
                    include_once("../includes/config.php");
                    // Consulta a la base de datos
                    $query = "SELECT id_usuarios, matricula, nombre, email, grupo, cuatrimestre FROM alumnos WHERE rol= 'alumno'";
                    $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        //Mostrar los datos de cada fila
                      while($row = $result->fetch_assoc()) {
                          echo "<tr>
                                    <td>{$row['id_usuarios']}</td>
                                    <td>{$row['matricula']}</td>
                                    <td>{$row['nombre']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['grupo']}</td>
                                    <td>{$row['cuatrimestre']}</td>
                                    <td>
                                <a href='editar_alumno_enca.php?id={$row['id_usuarios']}' class='btn btn-primary btn-action'>
                                    <i class='fas fa-edit'></i>
                                </a>
                                <a href='eliminar_alumno_enca.php?id={$row['id_usuarios']}' class='btn btn-danger btn-action' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>
                                    <i class='fas fa-trash-alt'></i>
                                </a>
                            </td>
                                </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'> No se encontraron registros</td> </tr>";
                    }

                    // Cerrar la conexion
                    $conn->close();
                    
                ?>
            </tbody>
        </table>
    </div> 
    </form>
    </div>
    </div>
    </div>
  </main> 
    <div class="p-3"></div>

    <!-- El siguiente fragmento utiliza jQuery y el plugin DataTables para dinamizar una tabla HTMl dandole funcionalidades avanzadas. -->
    <script>
        //Espera a que el documento se haya cargado completamente antes de ejecutar el codigo.
        $(document).ready(function(){
            //Inicializa DataTables en la taba con el ID 'tabla Alumnos'.
            $('#tablaAlumnos').DataTable({
                //Habilita el diseño responsivo de DataTables. Esto hace que la tabla sea adaptativa a diferentes tamaños de pantalla.
                responsive: true,
                //Personaliza el idioma de los elementos de DataTables para mejorar la experiencia del usuario en español.
                "language": {
                    //Cambia el texto del elemento busqueda y añade un placeholder al campo de busqueda.
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
   <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<?php include '../includes/footer.php'; ?>

</body>
</html>