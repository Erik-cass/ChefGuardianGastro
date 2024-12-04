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
    <title>Agregar Artículos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
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

<h4 class="text-center border-bottom"><strong>REGISTRAR NUEVOS ARTICULOS</strong></h4>
 <br>
<form action="agregar_articulo.php" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="nombreArticulo">Nombre del Artículo</label>
                <input type="text" class="form-control" id="nombreArticulo" name="nombreArticulo" placeholder="Escribe el nombre del artículo" required>
            </div>
            <div class="form-group">
                <label for="cantidadExistente">Cantidad</label>
                <input type="number" class="form-control" id="cantidadExistente" min="1" name="cantidadExistente" required>
            </div>

            <div class="col-sm">
                <label>Categoria:</label>
                <div class="form-group">
                    <select class="custom-select" name="categoria" id="categoria" required>
                        <option selected disabled value="">Seleccionar Categoría...</option>
                        <option>Madera</option>
                        <option>Metal</option>
                        <option>Plástico</option>
                        <option>Vidrio</option>
                        <option>Cerámica</option>
                        <option>Electrónicos</option>
                    </select>
                </div>
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
                <input type="file" class="form-control-file" id="rutaImagen" name="rutaImagen" accept="image/png, image/jpeg">
            </div>
            <div class="image-preview" id="imagePreview">
                <img src="" alt="Vista Previa de la Imagen" id="previewImg">
                <span id="previewText">Vista Previa de la Imagen</span>
            </div>
        </div>
    </div>
    <!-- Campo oculto para estatus disponible -->
    <input type="hidden" name="estatus" value="disponible">
    <center>
        <button type="submit" class="btn btn-primary">Agregar</button>
    </center>
</form>

<!-- Botón para abrir el modal de artículos donados -->
<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#importModal">Registrar artículos donados</button>

<!-- Modal para registrar artículos donados -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Artículos Donados</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">
                        Agregar Artículos
                    </div>
                    <div class="card-body">
                        <!-- Formulario dentro del modal para registrar artículos donados -->
                        <form action="agregar_articulo.php" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="nombreArticuloDonado">Nombre del Artículo</label>
                                <input type="text" class="form-control" id="nombreArticuloDonado" name="nombreArticulo" placeholder="Escribe el nombre del artículo" required>
                            </div>
                            <div class="form-group">
                                <label for="cantidadExistenteDonado">Cantidad</label>
                                <input type="number" class="form-control" id="cantidadExistenteDonado" min="1" name="cantidadExistente" required>
                            </div>
                            <div class="form-group">
                                <label for="categoriaDonado">Categoría</label>
                                <select class="custom-select" name="categoria" id="categoriaDonado" required>
                                    <option selected disabled value="">Seleccionar Categoría...</option>
                                    <option value="Madera">Madera</option>
                                    <option value="Metal">Metal</option>
                                    <option value="Plástico">Plástico</option>
                                    <option value="Vidrio">Vidrio</option>
                                    <option value="Cerámica">Cerámica</option>
                                    <option value="Electrónicos">Electrónicos</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="descripcionDonado">Descripción</label>
                                <textarea class="form-control" id="descripcionDonado" name="descripcion" rows="4" placeholder="Escribe una descripción" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="rutaImagenDonado">Cargar Imagen</label>
                                <small>(Admite solo .png, .jpeg y .jpg)</small>
                                <input type="file" class="form-control-file" id="rutaImagenDonado" name="rutaImagen" accept="image/png, image/jpeg">
                            </div>
                            <div class="image-preview" id="imagePreviewDonado">
                                <img src="" alt="Vista Previa de la Imagen" id="previewImgDonado">
                                <span id="previewTextDonado">Vista Previa de la Imagen</span>
                            </div>
                            <!-- Campo oculto para estatus donado -->
                            <input type="hidden" name="estatus" value="donado">
                            <center>
                                <input type="submit" class="btn btn-primary" value="Agregar" name="submitDonado">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </center>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>    
</div>

</div>
</main>
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>

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
    <?php include '../includes/footer.php'; ?>
</body>
</html>


