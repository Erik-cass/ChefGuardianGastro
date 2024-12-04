<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Alumnos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

	<!--jQuery -->
	<script src="https://code.jquery.com/jquery-3.5.1.js"></script>

	<!-- DataTables CSS -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">

	<!--DataTables Responsive CSS-->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.bootstrap4.min.css">

	<!--DataTbles JS-->
	<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>

	<!-- DataTables Responsive JS -->
	<script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.2.7/js/responsive.bootstrap4.min.js"></script>

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>


<body>
 <div class="container mt-5">
    <h2>Lista de Alumnos</h2>
    <table id="tabla-form" class="table table-striped">
        <thead class="bg-dark text-white text-center">
            <tr>
                <th>id_alumno</th>
                <th>Nombre</th>
                <th>Email</th>
                <th>Telefono</th>
                <th>Opciones</th>
            </tr>
        </thead>
        <tbody class= "text-center">
            <?php
                include_once("../config.php");

                $query = "SELECT * FROM alumnoss";
                $result = $conn->query($query);

                    if ($result && $result->num_rows > 0) {
                        //Mostrar los datos de cada fila 
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                            <td>{$row['id_alumno']}</td>
                            <td>{$row['nombre']}</td>
                            <td>{$row['email']}</td>
                            <td>{$row['telefono']}</td>
                            <td>
                              <button class='btn btn-warning' onclick='editRecord(this)'>Editar<i class='fas fa-edit'></i></button>
                              <button class='btn btn-danger' onclick='conf irmDelete()'>Eliminar<i class='fas fa-trash-alt'></i></button>
                              <a href='requisicion.php?id={$row["id_alumno"]}' class='btn btn-primary' role='button'>Imprimir <i class='fas fa-print'></i></a>                     
                            </td>
                            </tr>";
                    }
        } else {
            echo "<tr><td colspan='14'>No se encontraron registros.</td></tr>";
        }
        
        $conn->close();
        ?>

        </tbody>
    </table>
 </div>   
 <script>
    $(document).ready(function(){
        $('#tabla-form').DataTable({
            responsive: true,
            "language": {
                "search": "Buscar:",
                "searchPlaceholder": "Filtrar por culumnas..."
            }
        });
    });
 </script>

     <!-- Popper.js first, then Bootstrap JS -->
     <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>


</body>
</html>