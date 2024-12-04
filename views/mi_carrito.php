<?php
session_start();
error_reporting(0);

$validar = $_SESSION['matricula'];

if ($validar == null || $validar == '') {
    header("Location: ../includes/login.php");
    die();
}

$matricula = $_SESSION['matricula'];
$nombre = $_SESSION['nombre'];
$grupo = $_SESSION['grupo'];
$id_usuarios = $_SESSION['id_usuarios'];

// Conectar a la base de datos para verificar el estado de bloqueado
include '../includes/config.php';

$query = "SELECT bloqueado FROM alumnos WHERE id_usuarios = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_usuarios);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

$bloqueado = $row['bloqueado'];
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha384-l9w+TN34AZJXFOVM7G2b4zWW51K13S5F0mowfHLHg9FkWEMmw1+8RRxfYsO32CJo" crossorigin="anonymous">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <link rel="stylesheet" href="../css/estilo.css">
        
</head>

<body>
<div class="container-fluid">
        <div class="row">
            <!-- Incluir el menú lateral -->
            <?php include '../includes/_header.php'; ?>

            <main role="main" class="col-md-9 col-lg-10 px-4">
                <div class="contenido-principal mt-4">


                <div class="container py-3">

                <h2 class="text-center border-bottom pb-3 mb-4 text-uppercase"><strong>Requisición</strong></h2>
                <?php if ($bloqueado == 1): ?>
                    <div class="alert alert-danger text-center">
                        <strong>¡Tu cuenta está bloqueada!</strong> No puedes solicitar materiales hasta que devuelvas los materiales pendientes.
                    </div>
                <?php else: ?>
    <form id="checkout-form"> 
        <div class="row g-3">
            <div class="col-md-6">
                    <label  for="asignatura" style="font-size: 1.25rem;"> <strong>Laboratorio</strong></label>
                    <div class="form-group">
                    <select class="custom-select" id="asignatura" name="asignatura" required>
                        <option selected disabled value="">Selecciona Laboratorio...</option>
                        <option>Cocina Caliente</option>
                        <option>Cocina Fría</option>
                        <option>Restaurante</option>
                        <option>Vitivinicultura</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="profesor" style="font-size: 1.25rem;"> <strong>Profesor</strong></label>
                    <input type="text" class="form-control" id="profesor" name="profesor" placeholder="Escribe el nombre del Profesor" required>
                </div>
            </div>
        </div>
    </form>


    

    <div class="mt-3">
        <h4 class="text-center mb-4"><strong>Materiales</strong></h4>

            <div class="table-responsive shadow-sm rounded">
                <table class="table table-bordered">
                    <thead class="table-primary text-center">
                        <tr>
                            <th>Imagen</th>
                            <th>Artículo</th>
                            <th>Categoría</th>
                            <th>Cantidad</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="cart-table-body" class="text-center">
                        <!-- Las filas del carrito se generarán aquí -->
                    </tbody>
                </table>
            </div>
    </div>

    <div class="text-center pb-3 mb-4 text-uppercase mt-4">
        <button class="btn btn-success px-4" onclick="checkout()">Solicitar</button>
    </div>
    <?php endif; ?>
</div>
</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

   function updateCart() {
    const cartTableBody = document.getElementById('cart-table-body');
    cartTableBody.innerHTML = '';
    cart.forEach((item, index) => {
        const cartRow = document.createElement('tr');
        cartRow.innerHTML = `
            <td><img src="${item.imagen}" alt="Imagen" style="width: 50px; height: 50px;"></td>
            <td>${item.nombre}</td>
            <td>${item.categoria}</td>
            <td>
                <input type="number" class="form-control" value="${item.cantidad_en_carrito}" min="1" max="${item.cantidad_disponible}" onchange="updateQuantity(${index}, this.value)">
            </td>
            <td>${item.descripcion}</td>
            <td>${new Date().toLocaleDateString()}</td>
            <td>
                <button class="btn btn-danger btn-sm" onclick="removeFromCart(${index})">Eliminar</button>
            </td>
        `;
        cartTableBody.appendChild(cartRow);
    });
}


    function updateQuantity(index, newQuantity) {
        const availableQuantity = cart[index].cantidad_disponible;
        newQuantity = parseInt(newQuantity);

        if (newQuantity > availableQuantity) {
            alert(`Solo hay ${availableQuantity} unidades disponibles`);
            return;
        }

        if (newQuantity < 1) {
            alert(`La cantidad no puede ser menor que 1`);
            return;
        }

        cart[index].cantidad_en_carrito = newQuantity;
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCart();
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCart();
    }

function checkout() {
if (<?php echo $bloqueado; ?> == 1) {
            Swal.fire({
                icon: 'warning',
                title: 'Cuenta Bloqueada',
                text: 'No puedes realizar solicitudes hasta devolver los materiales pendientes.',
                confirmButtonText: 'Entendido',
            });
            return;
        }

    
    const asignatura = document.getElementById('asignatura').value;
    const profesor = document.getElementById('profesor').value;

    // Validar el campo de asignatura
    if (!asignatura) {
        Swal.fire({
            icon: 'warning',
            title: 'Campo Incompleto',
            text: 'Por Favor, Selecciona un Laboratorio.',
            confirmButtonText: 'Entendido',
            timer: 3000,
            timerProgressBar: true,
        });
        return;
    }

    // Validar el campo de profesor
    if (!profesor) {
        Swal.fire({
            icon: 'warning',
            title: 'Campo Incompleto',
            text: 'Por Favor, Escribe el Nombre del Profesor.',
            confirmButtonText: 'Entendido',
            timer: 3000,
            timerProgressBar: true,
        });
        return;
    }

    // Validar si el carrito está vacío
    if (cart.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Requisición Vacía',
            text: 'Agrega Materiales Antes de Solicitar.',
            confirmButtonText: 'Entendido',
            timer: 3000,
            timerProgressBar: true,
        });
        return;
    }



    // Crear el objeto de datos
    const data = {
        cart: cart,
        asignatura: asignatura,
        profesor: profesor,
        id_usuarios: <?php echo json_encode($id_usuarios); ?>,
        nombre_alumno: <?php echo json_encode($nombre); ?>
    };

    // Enviar los datos al servidor
    fetch('checkout.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    }).then(response => response.json()).then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Pedido Realizado',
                text: 'Tu Solicitud ha Sido Enviada con Éxito.',
                confirmButtonText: 'Aceptar',
            });
            cart = [];
            localStorage.setItem('cart', JSON.stringify(cart));
            updateCart();
            document.getElementById('asignatura').value = '';
            document.getElementById('profesor').value = '';
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: `Hubo un problema al realizar el pedido: ${data.error || 'Desconocido'}`,
                confirmButtonText: 'Reintentar',
            });
        }
    }).catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error Inesperado',
            text: 'Hubo un problema al conectarse al servidor.',
            confirmButtonText: 'Entendido',
        });
    });

}



    document.addEventListener('DOMContentLoaded', updateCart);
</script>

<?php include '../includes/footer.php'; ?>
</body>
</html>
