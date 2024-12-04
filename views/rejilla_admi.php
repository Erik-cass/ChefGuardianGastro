<?php
session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

include '../includes/config.php';


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Artículos</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS -->
    

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css">
    <!-- Custom CSS -->
    <style>
        #add-to-cart-message, #delete-message {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            color: white;
            text-align: center;
            padding: 10px;
            z-index: 1000;
        }
        #add-to-cart-message {
            background-color: #28a745;
        }
        #delete-message {
            background-color: #dc3545;
        }
        .navbar-brand {
            padding: 0;
        }
        .navbar-brand img {
            max-height: 45px;
        }
        .contenido-principal {
          background-color: #f4f4f9;
          border-radius: 20px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          padding: 20px;
          margin-top: 20px;
        }
        .titulo {
            text-align: center;
        }
        .table-responsive {
            margin-top: 20px;
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

          <h4 class="text-center"><strong>LISTA DE ARTICULOS DISPONIBLES</strong></h4>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end"><a href='imprimir_articulos_admi.php' class='btn btn-danger' role='button'><i class='fas fa-file-pdf'></i> PDF</a></div>    
        </div>
        <div class="table-responsive">
            <table id="tablaArticulos" class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Categoria</th>
                        <th>Descripcion</th>
                        <th>Cantidad</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include '../includes/config.php';

                    $query = "SELECT id_articulo, nombre, categoria, descripcion, cantidad, imagen FROM articulos";
                    $result = $conn->query($query);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td><img src='" . htmlspecialchars($row['imagen']) . "' alt='Imagen' class='img-thumbnail' style='width: 90px; height: 90px;'></td>";
                            echo "<td>" . htmlspecialchars($row['nombre']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['categoria']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['descripcion']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['cantidad']) . "</td>";
                            echo "<td class='d-flex'>
                                <a href='editar_articulo_admi.php?id={$row['id_articulo']}' class='btn btn-primary btn-action mr-2'>
                                    <i class='fas fa-edit'></i>
                                </a>
                                <a href='eliminar_articulo_admi.php?id={$row['id_articulo']}' class='btn btn-danger btn-action mr-2' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\");'>
                                    <i class='fas fa-trash-alt'></i>
                                </a>
                                <div class='dropdown'>
                                    <button class='btn btn-success dropdown-toggle' type='button' id='estadoDropdown{$row['id_articulo']}' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                        Disponibles
                                    </button>
                                    <div class='dropdown-menu' aria-labelledby='estadoDropdown{$row['id_articulo']}'>
                                        <button class='dropdown-item btn-status' data-id='{$row['id_articulo']}' data-status='perdido'>Perdido</button>
                                        <button class='dropdown-item btn-status' data-id='{$row['id_articulo']}' data-status='dañado'>Dañado</button>
                                    </div>

                                </div>
                            </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No se encontraron artículos.</td></tr>";
                    }
                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
</div>
</div>

<!-- jQuery, DataTables, and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
        //Espera a que el documento se haya cargado completamente antes de ejecutar el codigo.
        $(document).ready(function(){
            //Inicializa DataTables en la taba con el ID 'tabla Alumnos'.
            $('#tablaArticulos').DataTable({
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
    // Seleccionar todos los botones con la clase 'btn-status'
    document.querySelectorAll('.btn-status').forEach(function(button) {
        button.addEventListener('click', function() {
            // Obtener el ID del artículo y el estado deseado
            var articuloId = this.getAttribute('data-id');
            var status = this.getAttribute('data-status');
            
            // Pedir al usuario la cantidad a actualizar
            var cantidad = prompt('Ingrese la cantidad de artículos perdidos o dañados:');
            if (cantidad !== null && !isNaN(cantidad) && cantidad > 0) {
                // Confirmar la acción con el usuario
                if (confirm(`¿Estás seguro de que deseas marcar este artículo como ${status}?`)) {
                    // Crear el objeto de datos a enviar
                    var data = {
                        id: articuloId,
                        cantidad: parseInt(cantidad, 10),
                        status: status
                    };

                    // Depuración: Mostrar datos en la consola
                    console.log('Datos que se enviarán:', data);

                    // Enviar los datos al servidor con fetch
                    fetch('actualizar_estado.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json()) // Convertir la respuesta a JSON
                    .then(data => {
                        // Mostrar notificación dependiendo del resultado
                        if (data.success) {
                            Swal.fire({
                                title: '¡Éxito!',
                                text: 'Estado actualizado correctamente.',
                                icon: 'success',
                                confirmButtonText: 'Aceptar'
                            }).then(() => location.reload()); // Recargar la página
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: data.message || 'Hubo un error al actualizar el estado.',
                                icon: 'error',
                                confirmButtonText: 'Aceptar'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error',
                            text: 'Ocurrió un problema al conectar con el servidor.',
                            icon: 'error',
                            confirmButtonText: 'Aceptar'
                        });
                    });
                }
            } else {
                // Mostrar alerta si la cantidad ingresada es inválida
                Swal.fire({
                    title: 'Cantidad inválida',
                    text: 'Por favor, ingrese un número válido mayor a 0.',
                    icon: 'warning',
                    confirmButtonText: 'Aceptar'
                });
            }
        });
    });
</script>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<?php include '../includes/footer.php'; ?>
</body>


</html>

