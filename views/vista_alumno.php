<?php
session_start();
error_reporting(0);

$validar = $_SESSION['matricula'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

include_once '../includes/config.php'; // Asegúrate de que este archivo contiene la conexión a la base de datos

$matricula = $_SESSION['matricula'];

// Consulta a la base de datos para obtener los datos del alumno
$query = "SELECT matricula, nombre, email, grupo, cuatrimestre FROM alumnos WHERE matricula = '$matricula'";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $alumno = mysqli_fetch_assoc($result);
} else {
    echo "No se encontró información del alumno.";
    die();
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Alumno</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
    .contenido-principal {
      background-color: #f4f4f9;
      border-radius: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 20px;
      margin-top: 20px;
  </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Incluir el menú lateral -->
        <?php include '../includes/_header.php'; ?>
        <?php include "fecha.php"?>

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
            <div class="contenido-principal mt-4 text-center"> 
              <img class="perfil-img" src="../uploaded_images/foto_login.png" alt="Foto de perfil" >
                <h3 class="titulo">Alumno: <?php echo $_SESSION['nombre']; ?> (<?php echo $alumno['matricula']; ?>)</h3>
                <h5><strong>Mexico, <?php echo fecha(); ?> </strong></h5>
                <div class="reloj">
                   <h5><strong>Hora </strong> </h5>
                   <h5><strong id="tiempo"> 00 : 00 : 00</strong></h5>
                   <h5 class="titulo">
                    <strong>Email:</strong> <span style="margin-right: 30px;"><?php echo $alumno['email']; ?></span>
                    <strong>Grupo:</strong> <span style="margin-right: 30px;"><?php echo $alumno['grupo']; ?></span>
                    <strong>Cuatrimestre:</strong> <span style="margin-right: 30px;"><?php echo $alumno['cuatrimestre']; ?></span>
                </h5>
                </div>
                </div>
            <br>
        <div class="contenido-principal mt-4 text-center">
            <h4 class="text-center border-bottom"><strong>LISTA DE ARTICULOS</strong></h4>
                        <div class="row">
                            <?php
                            include '../includes/config.php';

                            $query = "SELECT id_articulo, nombre, cantidad, categoria, descripcion, imagen FROM articulos";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<div class='col-md-3 mb-4'>";
                                    echo "<div class='card'>";
                                    echo "<img src='" . htmlspecialchars($row['imagen']) . "' class='perfil-img' alt='Imagen' >";
                                    echo "<div class='card-body'>";
                                    echo "<h5 class='card-title'>" . htmlspecialchars($row['nombre']) . "</h5>";
                                    echo "<p class='card-text'>Cantidad Disponible: <span id='cantidad-{$row['id_articulo']}'>" . htmlspecialchars($row['cantidad']) . "</span></p>";
                                    echo "<p class='card-text'>Categoría: " . htmlspecialchars($row['categoria']) . "</p>";
                                    $disabled = $row['cantidad'] <= 0 ? 'disabled' : '';
                                    echo "<button class='btn btn-primary' onclick='addToCart(" . json_encode($row) . ")' $disabled>Agregar al carrito</button>";
                                    echo "</div>";
                                    echo "</div>";
                                    echo "</div>";
                                }
                            } else {
                                echo "<div class='col-12'>No se encontraron artículos.</div>";
                            }
                            $conn->close();
                            ?>
                        </div>
                    </div>

                    <div id="add-to-cart-message">
    Producto agregado al carrito
</div>


                    <script>
let cart = JSON.parse(localStorage.getItem('cart')) || [];

// Agregar al carrito
function addToCart(product) {
    const productInCart = cart.find(item => item.id_articulo === product.id_articulo);

    if (productInCart) {
        productInCart.cantidad_en_carrito += 1; // Incrementar cantidad en el carrito
    } else {
        cart.push({
            id_articulo: product.id_articulo,
            nombre: product.nombre,
            cantidad_en_carrito: 1, // Inicializamos con 1
            descripcion: product.descripcion,
            imagen: product.imagen,
            categoria: product.categoria,
            cantidad_disponible: product.cantidad, // Cantidad real disponible
        });
    }

    localStorage.setItem('cart', JSON.stringify(cart));
    showMessage("Producto agregado al carrito");
}

// Mostrar mensaje de éxito
function showMessage(message) {
    const messageBox = document.getElementById('add-to-cart-message');
    messageBox.innerText = message;
    messageBox.style.display = 'block';
    setTimeout(() => {
        messageBox.style.display = 'none';
    }, 1000);
}

// Mostrar el contenido del carrito para depuración
function debugCart() {
    console.log('Cart:', cart);
}
                    </script>
            </main>
        </div>
    </div>



<!-- Scripts de Bootstrap y jQuery (Asegúrate de incluir jQuery antes de Bootstrap) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="../js/reloj.js"></script>


<?php include '../includes/footer.php'; ?>

</body>
</html>