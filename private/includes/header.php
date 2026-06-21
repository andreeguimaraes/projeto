<?php
require_once __DIR__ . '/../../config/config.php';
?> 

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?></title>
    <!-- Bootstrap CSS & custom CSS -->
    <link rel="stylesheet" href="/MEDINV/assets/bootstrap/bootstrap.min.css"> 
    <!-- favicon  -->
    <link rel="icon" type="image/svg+xml" href="/MEDINV/assets/img/logo_medinv_icon.svg">
    <!-- folha de estilos CSS -->
    <link rel="stylesheet" href="/MEDINV/private/assets/admin1240722.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/MEDINV/assets/fontawesome/all.min.css">
    <!-- Chart.js -->
    <script src="/MEDINV/assets/chartjs/chart.js"></script>
    <!-- jQuery -->
    <script src="/MEDINV/private/assets/jquery/jquery-3.6.0.min.js"></script>
    <!-- DataTables CSS + JS -->
    <link rel="stylesheet" href="/MEDINV/private/assets/datatables/datatables.min.css">
    <script src="/MEDINV/private/assets/datatables/datatables.min.js"></script> 
</head>


<body class="<?= $bodyClass ?? '' ?>">