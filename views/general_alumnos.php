<?php

session_start();
error_reporting(0);

$validar = $_SESSION['matricula'];

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

            <main role="main" class="col-md-9 col-lg-10 px-4">
                <div class="contenido-principal mt-4">
                    

        <div class="container mt-7">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
          <h4 class="text-center"><strong>TOTAL DE ALUMNOS REGISTRADOS</strong></h4>

          

          
            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
              <button class="btn btn-outline-primary" data-toggle="modal" data-target="#updateGroupModal">
                      <i class='fas fa-edit'></i>
                      Actualizar Grupo
                  </button>
                  <form action="../csv/exportar_registro_alumnos.php">
              <button class="btn btn-success" type="submit"><i class='fas fa-file-excel'></i> Exportar</button> 



        </div>
        </div>
          </form> 
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
                                <a href='editar_alumno.php?id={$row['id_usuarios']}' class='btn btn-primary btn-action'>
                                    <i class='fas fa-edit'></i>
                                </a>
                                <a href='eliminar_alumno_admi.php?id={$row['id_usuarios']}' class='btn btn-danger btn-action' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>
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


<!-- Modal de Actualización de Grupo -->
<div class="modal fade" id="updateGroupModal" tabindex="-1" role="dialog" aria-labelledby="updateGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateGroupModalLabel">Actualizar Grupo y Cuatrimestre</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="updateGroupForm">
                    <div class="form-group">
                        <label for="currentGroup">Grupo Actual</label>
                        <input type="text" class="form-control" id="currentGroup" placeholder="Ej: GA1-1" required>
                    </div>
                    <div class="form-group">
                        <label for="newGroup">Nuevo Grupo</label>
                        <input type="text" class="form-control" id="newGroup" placeholder="Ej: GA2-1" required>
                    </div>
                    
                      <label for="newSemester">Nuevo Cuatrimestre</label>
                      <div class="form-group">
                          <select class="custom-select" id="newSemester" name="newSemester" required>
                              <option selected disabled value="">Seleccionar cuatrimestre...</option>
                              <option value="1">1ero</option>
                              <option value="2">2do</option>
                              <option value="3">3ro</option>
                              <option value="4">4to</option>
                              <option value="5">5to</option>
                              <option value="6">6to - Estadías</option>
                              <option value="7">7mo</option>
                              <option value="8">8vo</option>
                              <option value="9">9no</option>
                              <option value="10">10mo</option>
                              <option value="11">11avo - Estadías</option>
                          </select>
                      </div>
               

                </form>
                

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
  <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
  </symbol>
 
</svg>

<div id="updateMessage" class="alert alert-success d-flex align-items-center d-none" role="alert">
  <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
  <div>
    Datos actualizados correctamente.
  </div>
</div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="updateGroupData()">Actualizar</button>
            </div>
        </div>
    </div>

</div>

</div>

</main>
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
    <script>
function updateGroupData() {
    var currentGroup = $('#currentGroup').val();
    var newGroup = $('#newGroup').val();
    var newSemester = $('#newSemester option:selected').text();

    $.ajax({
        url: 'actualizar_grupo.php',
        type: 'POST',
        dataType: 'json',
        data: {
            currentGroup: currentGroup,
            newGroup: newGroup,
            newSemester: newSemester
        },
        success: function(response) {
            var alertClass = response.status === 'success' ? 'alert-success' : 'alert-danger';
            $('#updateMessage').removeClass('d-none alert-success alert-danger').addClass(alertClass);
            $('#updateMessage div').text(response.message);

            if (response.status === 'success') {
                setTimeout(function() {
                    $('#updateMessage').addClass('d-none');
                    $('#updateGroupModal').modal('hide');
                    location.reload(); // Recargar la página para ver los cambios
                }, 2000);
            } else {
                setTimeout(function() {
                    $('#updateMessage').addClass('d-none');
                }, 3000);
            }
        },
        error: function(xhr) {
            $('#updateMessage').removeClass('d-none').addClass('alert-danger');
            $('#updateMessage div').text(`Error del servidor: ${xhr.status} - ${xhr.statusText}`);
        }
    });
}

    </script>


   <!-- Popper.js first, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  
<?php include '../includes/footer.php'; ?>

</body>
</html>