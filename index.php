<?php
session_start();
require_once('./includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['matricula']) && isset($_POST['password'])) {
    $errorMsg = "";
    $matricula = trim($_POST['matricula']);
    $password = trim($_POST['password']);

    if (!empty($matricula) && !empty($password)) {
        // Usar sentencias preparadas para evitar inyección SQL
        $query = "SELECT * FROM alumnos WHERE matricula = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $matricula);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            // Verificar la contraseña (asegúrate de que las contraseñas estén encriptadas)
            if (password_verify($password, $row['password'])) {
                $_SESSION['id_usuarios'] = $row['id_usuarios'];
                $_SESSION['rol'] = $row['rol'];
                $_SESSION['matricula'] = $row['matricula'];
                $_SESSION['nombre'] = $row['nombre'];
                $_SESSION['grupo'] = $row['grupo'];

                // Redirección según el rol
                if ($_SESSION['rol'] == 'administrador') {
                    header('Location: ./views/vista_administrador.php');
                    exit();
                } elseif ($_SESSION['rol'] == 'alumno') {
                    header('Location: ./views/vista_alumno.php');
                    exit();
                } elseif ($_SESSION['rol'] == 'encargado') {
                    header('Location: ./views/encargado.php');
                    exit();
                } else {
                    header('Location: login.php');
                    session_destroy();
                    exit();
                }
            } else {
                $errorMsg = "Usuario y/o contraseña incorrectos.";
            }
        } else {
            $errorMsg = "La matricula no se encuentra en nuestro sistema.";
        }
    } else {
        $errorMsg = "El nombre de usuario y la contraseña son obligatorios.";
    }
}
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <title>Inicio de Sesión</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous"/>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="./css/styles.css">

</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <img src="./uploaded_images/foto_login.png" alt="Login Image" class="login-image">
            <h2 class="login-head">INICIAR SESIÓN</h2>
            <form method="post" autocomplete="off" class="logInForm">
                <div class="input-group label-floating">
                    <label for="matricula">Usuario</label>
                    <input type="text" name="matricula" id="matricula" placeholder="Ingrese su matrícula" required>
                </div>
                <div class="input-group label-floating">
                    <label for="password">Contraseña</label>
                    <input type="password" name="password" id="password" placeholder="Ingrese su contraseña" required>
                </div>
                <br>
                <button type="submit" class="btn-submit">INICIAR SESIÓN</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        <?php if (!empty($errorMsg)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?php echo $errorMsg; ?>',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar'
            });
        <?php endif; ?>
    </script>
</body>
</html>
