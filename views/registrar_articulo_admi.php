<?php

session_start();
error_reporting(0);

$validar = $_SESSION['nombre'];

if( $validar == null || $validar = ''){

    header("Location: ../includes/login.php");
    die();  
}

$archivo = 'categorias.txt'; 
$mensaje = ""; // Mensaje de notificación
$tipo_mensaje = ""; // Tipo de notificación: success, warning, error

// Manejar acciones de agregar y eliminar categoría
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $nueva_categoria = trim($_POST['nueva_categoria'] ?? '');

    if ($accion === 'agregar' && !empty($nueva_categoria)) {
        if (file_exists($archivo)) {
            $categorias = file($archivo, FILE_IGNORE_NEW_LINES);
        } else {
            $categorias = [];
        }
        // Evitar duplicados
        if (!in_array($nueva_categoria, $categorias)) {
            $categorias[] = $nueva_categoria;
            file_put_contents($archivo, implode(PHP_EOL, $categorias) . PHP_EOL);
            $mensaje = "Categoría agregada exitosamente.";
            $tipo_mensaje = "success";
        } else {
            $mensaje = "La categoría ya existe.";
            $tipo_mensaje = "warning";
        }
    } elseif ($accion === 'eliminar' && !empty($nueva_categoria)) {
        if (file_exists($archivo)) {
            $categorias = file($archivo, FILE_IGNORE_NEW_LINES);
            if (in_array($nueva_categoria, $categorias)) {
                $categorias = array_filter($categorias, fn($categoria) => $categoria !== $nueva_categoria);
                file_put_contents($archivo, implode(PHP_EOL, $categorias) . PHP_EOL);
                $mensaje = "Categoría eliminada exitosamente.";
                $tipo_mensaje = "success";
            } else {
                $mensaje = "La categoría no existe.";
                $tipo_mensaje = "warning";
            }
        }
    } else {
        $mensaje = "El campo de la categoría no puede estar vacío.";
        $tipo_mensaje = "error";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Artículos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
      <!-- Agrega los estilos de Bootstrap -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Incluir Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.css">

 <link rel="stylesheet" href="../css/estilo.css">


   <style>

        #notification {
            top: 20px;
            right: 20px;
            z-index: 1050;
            display: none; /* Ocultar inicialmente */
            background-color: #64b7f6; 
            color: #272727;
            text-align: center;
            font-weight: bold;
        }
    </style>
    <style>
        .image-preview {
            width: 200px;
            height: 200px;
            border: 2px dashed #c0c0c0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #ccc;
            margin-top: 15px;
            overflow: hidden;
        }
        .image-preview img {
            display: none;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .nav-link-red {
            color: red !important;
        }
    </style>
    <style>
        
        .contenido-principal {
          background-color: #f4f4f9;
          border-radius: 20px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
          padding: 20px;
          margin-top: 20px;
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
                
<!-- Mensaje de Éxito/Error -->
                    <?php if (!empty($mensaje)): ?>
                                <div id="notification" class="alert alert-<?php echo htmlspecialchars($tipo_mensaje); ?>" role="alert">
                                    <?php echo htmlspecialchars($mensaje); ?>
                                </div>
                            <?php endif; ?>

    <div class="container mt-7">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h4 class="text-center"><strong>REGISTRAR NUEVOS ARTICULOS</strong></h4>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#modalCategorias">
                              <i class='fas fa-edit'></i>
                              Agregar categoria
                </button>


            </div>
        </div>

<form id="formArticulo" method="post" enctype="multipart/form-data" action="agregar_articulo_admi.php">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="nombreArticulo">Nombre del Artículo</label>
                <input type="text" class="form-control" id="nombreArticulo" name="nombreArticulo" placeholder="Escribe el nombre del artículo" required>
            </div>
            <div class="form-group">
                <label for="cantidadExistente">Cantidad</label>
                <input type="number" class="form-control" id="cantidadExistente" min="1" name="cantidadExistente" placeholder="Escriba la cantidad" required>
            </div>

            <div class="form-group">
                <label for="categoria">Categoría</label>
                <select class="form-select" name="categoria" id="categoria" required>
                <option selected disabled value="">Seleccionar Categoría...</option>
                    <?php
                    if (file_exists($archivo)) {
                        $categorias = file($archivo, FILE_IGNORE_NEW_LINES);
                        foreach ($categorias as $categoria) {
                            echo "<option value='$categoria'>$categoria</option>";
                        }
                    }
                    ?>

                </select>
            </div>

            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" rows="4" placeholder="Escribe una descripción" required></textarea>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label for="rutaImagen">Cargar Imagen</label>
                <small>(Admite solo .png, .jpeg y .jpg)</small>
                <input type="file" class="form-control-file" id="rutaImagen" name="rutaImagen" accept="image/png, image/jpeg" required>
            </div>
            <div class="image-preview" id="imagePreview">
                <img src="" alt="Vista Previa de la Imagen" id="previewImg">
                <span id="previewText">Vista Previa de la Imagen</span>
            </div>
   <br>

            <div class="form-group">
                <label for="estatus">Estatus</label>
                <select class="form-select form-select" class="form-control" id="estatus" name="estatus" required>
                    <option value="comprado" selected>Comprado</option>
                    <option value="donado">Donado</option>
                </select>
            </div>
        </div>
    <center>
        <div class="d-grid gap-2 col-3 mx-auto">
            <button type="submit" class="btn btn-primary">REGISTRAR</button>
        </div>
    </center>
</div>
</form>
</div>
<br>
                <!-- Modal para Agregar/Eliminar Categorías -->
                <div class="modal fade" id="modalCategorias" tabindex="-1" aria-labelledby="modalCategoriasLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCategoriasLabel">Administrar Categorías</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="" method="POST">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="nueva_categoria">Nombre de la Categoría</label>
                                        <input type="text" class="form-control" id="nueva_categoria" name="nueva_categoria" placeholder="Escribe el nombre de la categoría" required>
                                    </div>
                                    <button type="submit" name="accion" value="agregar" class="btn btn-primary">Agregar</button>
                                    <button type="submit" name="accion" value="eliminar" class="btn btn-danger">Eliminar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

   <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.0/dist/sweetalert2.min.js"></script>

<script>
    document.getElementById("rutaImagen").addEventListener("change", function(){
        const reader = new FileReader();
        reader.onload = function(e) {
            const uploaded_image = e.target.result;
            document.getElementById("previewImg").src = uploaded_image;
            document.getElementById("previewImg").style.display = "block";
            document.getElementById("previewText").style.display = "none";
        };
        reader.readAsDataURL(this.files[0]);
    });
</script>
<script>
    document.getElementById("formArticulo").addEventListener("submit", function (event) {
    event.preventDefault();

    const form = this;
    const formData = new FormData(form);

    fetch("agregar_articulo_admi.php", {
        method: "POST",
        body: formData,
    })
    .then(response => response.json()) // Asegúrate de interpretar la respuesta como JSON
    .then(data => {
        Swal.fire({
            title: data.tipo_mensaje === "success" ? "¡Éxito!" : "Error",
            text: data.mensaje,
            icon: data.tipo_mensaje,
        });

        if (data.tipo_mensaje === "success") {
            form.reset(); // Limpia todos los campos
            // Limpia manualmente el campo de imagen y la vista previa
            document.getElementById("rutaImagen").value = ""; // Limpia el input de archivo
            const previewImg = document.getElementById("previewImg");
            previewImg.src = ""; // Remueve la imagen previa
            previewImg.style.display = "none"; // Oculta la imagen
            document.getElementById("previewText").style.display = "block"; // Muestra el texto predeterminado
        }
    })
    .catch(error => {
        console.error("Error:", error);
        Swal.fire({
            title: "Error",
            text: "Hubo un error al procesar la solicitud.",
            icon: "error",
        });
    });
});

</script>
<script>
     <?php if (!empty($mensaje)): ?>
        Swal.fire({
            title: '¡Mensaje!',
            text: '<?php echo $mensaje; ?>',
            icon: '<?php echo $tipo_mensaje; ?>', // success, warning, error
            confirmButtonText: 'Aceptar'
        });
    <?php endif; ?>
</script>
<?php include '../includes/footer.php'; ?>

</body>
</html>      
