<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Sistema ANA'; ?> - <?php echo APP_NAME; ?></title>
    <link rel="stylesheet" href="<?php echo APP_URL; ?>/public/css/style.css">
</head>
<body>
    <header class="header">
        <div class="container">
            <h1><?php echo APP_NAME; ?></h1>
            <p>Autoridad Nacional del Agua (ANA) - Perú</p>
        </div>
    </header>

    <nav class="navbar">
        <div class="container">
            <ul class="nav-menu">
                <li><a href="<?php echo APP_URL; ?>">Inicio</a></li>
                <li><a href="<?php echo APP_URL; ?>/infraestructura">Inventario</a></li>
                <li><a href="<?php echo APP_URL; ?>/infraestructura/crear">Nuevo Registro</a></li>
                <li><a href="<?php echo APP_URL; ?>/infraestructura/estadisticas">Estadísticas</a></li>
                <li><a href="<?php echo APP_URL; ?>/api/infraestructuras" target="_blank">API</a></li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
