<?php
session_start();
error_reporting(0);

$validar = $_SESSION['matricula'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

include '../includes/config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM articulos WHERE id_articulo = '$id'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "Artículo no encontrado.";
        exit();
    }
} else {
    echo "ID no proporcionado.";
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $categoria = $_POST['categoria'];
    $cantidad = $_POST['cantidad'];
    $imagen = $row['imagen']; // Asumir imagen existente inicialmente

    // Manejo de la imagen
    if (isset($_FILES['rutaImagen']) && $_FILES['rutaImagen']['error'] == UPLOAD_ERR_OK) {
        $imagenTmpName = $_FILES['rutaImagen']['tmp_name'];
        $imagenName = basename($_FILES['rutaImagen']['name']);
        $imagenDir = '../uploaded_images/' . $imagenName;

        // Comprobación adicional del tipo de archivo
        $fileType = mime_content_type($imagenTmpName);
        $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];

        if (in_array($fileType, $allowedTypes)) {
            // Mueve el archivo subido a la carpeta destino
            if (move_uploaded_file($imagenTmpName, $imagenDir)) {
                $imagen = $imagenDir;
            } else {
                echo "Error al mover la imagen a la carpeta destino.";
                error_log("Error al mover el archivo: " . $_FILES['rutaImagen']['error']);
                exit();
            }
        } else {
            echo "Tipo de archivo no permitido. Solo se permiten archivos PNG, JPEG y JPG.";
            exit();
        }
    } elseif ($_FILES['rutaImagen']['error'] != UPLOAD_ERR_NO_FILE) {
        echo "Error al subir la imagen. Código de error: " . $_FILES['rutaImagen']['error'];
        exit();
    }

    $updateQuery = "UPDATE articulos SET nombre='$nombre', cantidad='$cantidad', categoria='$categoria', descripcion='$descripcion', imagen='$imagen' WHERE id_articulo='$id'";

    if ($conn->query($updateQuery) === TRUE) {
        header("Location: rejilla_admi.php");
        exit();
    } else {
        echo "Error actualizando el artículo: " . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Artículo</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .image-preview {
            width: 200px;
            height: 200px;
            border: 2px dashed #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #ccc;
            margin-top: 15px;
            overflow: hidden;
        }
        .image-preview img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .nav-link-red {
            color: red !important;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2>Editar Artículo</h2>
    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($row['nombre']); ?>" required>
        </div>
        <div class="form-group">
            <label for="cantidad">Cantidad</label>
            <input type="number" class="form-control" id="cantidad" name="cantidad" value="<?php echo htmlspecialchars($row['cantidad']); ?>" min="1" required>

        <div class="col-sm">
                <label>Categoria:</label>
                <div class="form-group">
                    <select class="custom-select" name="categoria" required>
                        <option disabled placeholder="Categoria">Seleccionar Categoría...</option>
                        <?php
                        $categoria = [
                            "Madera", "Metal", "Plastico", "Vidrio", "Cerámica",
                            "Electronicos"
                        ];
                        foreach ($categoria as $categoria) {
                            $selected = ($row['categoria'] == $categoria) ? 'selected' : '';
                            echo "<option value=\"$categoria\" $selected>$categoria</option>";
                        }

                        ?>


                    </select>
                </div>
            </div>
        <div class="form-group">
            <label for="descripcion">Descripcion</label>
            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required><?php echo htmlspecialchars($row['descripcion']); ?></textarea>
        </div>

        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="rutaImagen">Cargar Imagen</label>
                <small>(Admite solo .png, .jpeg y .jpg)</small>
                <input type="file" class="form-control-file" id="rutaImagen" name="rutaImagen" accept="image/png, image/jpeg">
            </div>                
            <div class="image-preview" id="imagePreview">
                <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="Vista Previa de la Imagen" id="previewImg">
                <span id="previewText">Vista Previa de la Imagen</span>
            </div>
        </div>
    <center>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="rejilla_admi.php" class="btn btn-secondary">Cancelar</a>
    </center> 

    </form>
</div>

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
            document.getElementById("previewText").style.display = "none";
        };
        reader.readAsDataURL(this.files[0]);
    });

    // Mostrar imagen existente
    document.addEventListener('DOMContentLoaded', function() {
        const previewImg = document.getElementById("previewImg");
        if (previewImg.src && previewImg.src !== '') {
            document.getElementById("previewText").style.display = "none";
        } else {
            previewImg.style.display = "none";
        }
    });
</script>

</body>
</html>

