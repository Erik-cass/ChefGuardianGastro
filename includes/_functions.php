<?php
require_once("config.php");

if (isset($_POST['accion'])) {
    switch ($_POST['accion']) {
        //casos de registros
        case 'editar_registro':
            editar_registro();
            break;

        case 'eliminar_registro':
            eliminar_registro();
            break;

        case 'acceso_alumnos':
            acceso_alumnos();
            break;
    }
}

function editar_registro() {
    global $conexion; // Importar la variable $conexion globalmente
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $matricula = $_POST['matricula'];
    $password = $_POST['password'];
    $rol = $_POST['rol'];
    $grupo = $_POST['grupo'];
    $cuatrimestre = $_POST['cuatrimestre'];
    $id = $_POST['id'];

    $consulta = "UPDATE alumnos SET nombre = '$nombre', email = '$email', matricula = '$matricula', password ='$password', rol = '$rol', grupo = '$grupo', cuatrimestre = '$cuatrimestre' WHERE id = '$id' ";
    mysqli_query($conexion, $consulta);

    header('Location: ../views/user.php');
    exit();
}

function eliminar_registro() {
    global $conexion; // Importar la variable $conexion globalmente
    $id = $_POST['id'];
    $consulta = "DELETE FROM alumnos WHERE id= $id";
    mysqli_query($conexion, $consulta);

    header('Location: ../views/user.php');
    exit();
}

function acceso_alumnos() {
    global $conexion; // Importar la variable $conexion globalmente
    $nombre = $_POST['nombre'];
    $password = $_POST['password'];

    session_start();
    $_SESSION['nombre'] = $nombre;

    $consulta = "SELECT * FROM alumnos WHERE nombre='$nombre' AND password='$password'";
    $resultado = mysqli_query($conexion, $consulta);
    $filas = mysqli_fetch_array($resultado);

    if ($filas['rol'] == 'administrador') {
        header('Location: ../views/user.php');
        exit();
    } else if ($filas['rol'] == 'alumno') {
        header('Location: ../views/lector.php');
        exit();
    } else if ($filas['rol'] == 'encargado') {
        header('Location: ../views/encargado.php');
        exit();
    } else {
        header('Location: login.php');
        session_destroy();
        exit();
    }
}
?>
