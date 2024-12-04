<?php
session_start();
error_reporting(0);

$validar = $_SESSION['matricula'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vista de Artículos</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>

    <style>
        #add-to-cart-message {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background-color: #08D85A;
            color: white;
            text-align: center;
            padding: 10px;
            z-index: 1000;
        }
        .navbar-brand {
            padding: 0;
        }
        .navbar-brand img {
            max-height: 45px;
        }
        .contenido-principal {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .titulo {
            text-align: center;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="../uploaded_images/gastro_logo.jpg" alt="Logo">
        </a>
        <?php
        $nombres = explode(" ", $_SESSION['matricula']);
        $primer_nombre = $nombres[0];
        ?>
        <h4 class="text-light d-none d-lg-block">Bienvenido <?php echo $_SESSION['matricula']; ?></h4>
        <h4 class="text-light d-lg-none"><?php echo $primer_nombre; ?></h4>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="mi_carrito.php">
                    <i class="far fa-cart-plus"></i>
                </a>
            </li>
            <a class="nav-link" href="../includes/_sesion/cerrarSesion.php">
                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
            </a>
        </ul>
    </nav>
    <br>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 navbar navbar-expand-md navbar-dark bg-danger fixed-medium" style="height: 30vh; overflow-y: auto;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="sidebarMenu">
                    <div class="sidebar-sticky">
                        <ul class="nav flex-column">
                            <br>
                            <li class="nav-item">
                                <a class="nav-link active text-light" href="vista_alumno.php">
                                    INICIO
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="rejilla.php">
                                    Lista de Articulos
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-light" href="historial_alumno.php">
                                    HISTORIAL DE SOLICITUDES
                                </a>
                            </li>
                            <!-- Agrega más opciones según sea necesario -->
                        </ul>
                    </div>
                </div>
            </nav>
                        <main role="main" class="col-md-9 ml-sm-auto col-lg-10">
                <article class="contenido-principal">
                    <div class="col-xs-12">
                        <br>
                        <h2>Lista de Artículos</h2>
                        <div class="row">
                            <?php
                            include '../includes/config.php';

                            $query = "SELECT id_articulo, nombre, cantidad, categoria, descripcion, imagen FROM articulos";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo "<div class='col-md-3 mb-4'>";
                                    echo "<div class='card'>";
                                    echo "<img src='" . htmlspecialchars($row['imagen']) . "' class='card-img-top' alt='Imagen' style='width: 90px; height: 90px; margin: auto;'>";
                                    echo "<div class='card-body'>";
                                    echo "<h5 class='card-title'>" . htmlspecialchars($row['nombre']) . "</h5>";
                                    echo "<p class='card-text'>Cantidad: <span id='cantidad-{$row['id_articulo']}'>" . htmlspecialchars($row['cantidad']) . "</span></p>";
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

                    <div id="add-to-cart-message">Producto agregado al carrito</div>

                    <script>
                        let cart = JSON.parse(localStorage.getItem('cart')) || [];

                        function addToCart(product) {
                            const productInCart = cart.find(item => item.id_articulo === product.id_articulo);
                            if (product.cantidad > 0) {
                                if (productInCart) {
                                    if (productInCart.cantidad_en_carrito < product.cantidad) {
                                        productInCart.cantidad_en_carrito += 1;
                                    } else {
                                        alert('No puedes agregar más de este producto.');
                                        return;
                                    }
                                } else {
                                    cart.push({ 
                                        id_articulo: product.id_articulo, 
                                        nombre: product.nombre, 
                                        cantidad_en_carrito: 1, 
                                        cantidad_disponible: product.cantidad,
                                        descripcion: product.descripcion,
                                        imagen: product.imagen,
                                        categoria: product.categoria
                                    });
                                }
                                localStorage.setItem('cart', JSON.stringify(cart));
                                product.cantidad -= 1; // Decrementar la cantidad del producto
                                updateProductQuantity(product.id_articulo, product.cantidad); // Actualizar en la interfaz
                                showMessage();
                            } else {
                                alert('Este producto ya no está disponible.');
                            }
                        }

                        function updateProductQuantity(productId, newQuantity) {
                            const cantidadElement = document.getElementById(`cantidad-${productId}`);
                            cantidadElement.innerText = newQuantity;
                            if (newQuantity <= 0) {
                                const button = cantidadElement.closest('.card-body').querySelector('button');
                                button.disabled = true;
                            }

                            // Enviar la actualización al servidor
                            fetch('update_quantity.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    id_articulo: productId,
                                    new_quantity: newQuantity
                                })
                            }).then(response => response.json()).then(data => {
                                console.log('Server response:', data);
                                if (!data.success) {
                                    alert('Error al actualizar la cantidad en el servidor.');
                                }
                            }).catch(error => {
                                console.error('Error:', error);
                            });
                        }

                        function showMessage() {
                            const messageBox = document.getElementById('add-to-cart-message');
                            messageBox.style.display = 'block';
                            setTimeout(() => {
                                messageBox.style.display = 'none';
                            }, 1000);
                        }

                        // Agregar una función para depuración
                        function debugCart() {
                            console.log('Cart:', cart);
                        }
                    </script>
                </article>
            </main>

        </div>
    </div>
</body>
</html>
