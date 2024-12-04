<?php
session_start();
include_once('config.php');

if (!isset($_SESSION['id_usuarios'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrador</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.0/css/all.min.css">
    <link href="../uploaded_images/gastro_logo.jpg" rel="icon">
    <style>
        body {
            background-color: #ffffff;
        }

        .navbar {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            padding: 0rem 1rem;
        }

        .navbar-brand img {
            width: 120px;
        }

        .sidebar {
            background-color: #343a40;
            height: 100vh;
            padding: 1rem;
            color: #fff;
        }

        .sidebar a {
            color: #fff;
            text-decoration: none;
            font-weight: bold;
            padding: 10px;
            display: block;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        .sidebar a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .sidebar a.active {
            background-color: rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">
        <img src="../uploaded_images/logo_gastro.png" alt="Logo">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarContent">
        <div class="d-flex align-items-center ms-auto text-light">
        <h5 class="me-3 text-light">Bienvenido: <?php echo $_SESSION['nombre']; ?></h5>
    </div>


    <div class="collapse navbar-collapse" id="navbarContent">
        <h5 class="text-success ms-auto"><strong>Universidad Tecnológica de la Costa Grande de Guerrero
</strong></h5>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item">
                <a class="nav-link" href="../includes/_sesion/cerrarSesion.php">
                    <i class="fas fa-sign-out-alt fa-lg" style="color:#ff0000;" width="6" height="6"></i> <strong class="">Salir</strong>
                </a>

            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 sidebar">
            <ul class="list-unstyled mt-3">
                <?php 
                $current_page = basename($_SERVER['PHP_SELF']);
                if ($_SESSION['rol'] == 'administrador') { ?>
                    <li><a href="vista_administrador.php" class="<?= $current_page == 'vista_administrador.php' ? 'active' : '' ?>"><i class="fas fa-home me-2"></i>Inicio</a></li>
                    <li><a href="registeralumadmi.php" class="<?= $current_page == 'registeralumadmi.php' ? 'active' : '' ?>"><i class="fas fa-user-graduate me-2"></i>Registrar Alumnos</a></li>
                    <li><a href="regis_en.php" class="<?= $current_page == 'regis_en.php' ? 'active' : '' ?>"><i class="fas fa-user-plus me-2"></i>Registrar Encargado</a></li>
                    <li><a href="registrar_articulo_admi.php" class="<?= $current_page == 'registrar_articulo_admi.php' ? 'active' : '' ?>"><i class="fas fa-box-open me-2"></i>Registrar Articulos</a></li>
                    <li><a href="general_alumnos.php" class="<?= $current_page == 'general_alumnos.php' ? 'active' : '' ?>"><i class="fas fa-list-ul me-2"></i>Lista de Alumnos</a></li>
                    <li><a href="lista_de_encargados.php" class="<?= $current_page == 'lista_de_encargados.php' ? 'active' : '' ?>"><i class="fas fa-users-cog me-2"></i>Lista de Encargado</a></li>
                    <li><a href="rejilla_admi.php" class="<?= $current_page == 'rejilla_admi.php' ? 'active' : '' ?>"><i class="fas fa-clipboard-list me-2"></i>Lista de Articulos</a></li>
                    <li><a href="historial_de_solicitudes.php" class="<?= $current_page == 'historial_de_solicitudes.php' ? 'active' : '' ?>"><i class="fas fa-history me-2"></i>Historial de Solicitudes</a></li>

                <?php } elseif ($_SESSION['rol'] == 'encargado') { ?>
                    <li><a href="encargado.php"><i class="fas fa-home me-2"></i>Inicio</a></li>
                    <li><a href="registeralumenca.php"><i class="fas fa-user-graduate me-2"></i>Registrar Alumnos</a></li>
                    <li><a href="form1.php"><i class="fas fa-box-open me-2"></i>Registrar Articulos</a></li>
                    <li><a href="lista_alumno_enca.php"><i class="fas fa-list-ul me-2"></i>Lista de Alumnos</a></li>
                    <li><a href="rejilla_encargado.php"><i class="fas fa-clipboard-list me-2"></i>Lista de Articulos</a></li>
                    <li><a href="historial_pedido_enca.php"><i class="fas fa-history me-2"></i>Historial de Solicitudes</a></li>
                <?php } elseif ($_SESSION['rol'] == 'alumno') { ?>
                    <li><a href="vista_alumno.php" class="<?= $current_page == 'vista_alumno.php' ? 'active' : '' ?>"><i class="fas fa-home me-2"></i>Inicio</a></li>
                    <li><a href="mi_carrito.php" class="<?= $current_page == 'mi_carrito.php' ? 'active' : '' ?>"><i class="fas fa-cart-plus me-2"></i>Solicitar Material</a></li>
                    <li><a href="historial_alumno.php" class="<?= $current_page == 'historial_alumno.php' ? 'active' : '' ?>"><i class="fas fa-history me-2"></i>Historial</a></li>
                <?php } ?>
            </ul>
        </nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

