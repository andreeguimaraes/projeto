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
    <link rel="stylesheet" href="/sibdas/1240722/medinv/assets/bootstrap/bootstrap.min.css"> 
    <!-- favicon  -->
    <link rel="icon" type="image/svg+xml" href="/sibdas/1240722/medinv/assets/img/logo_medinv_icon.svg">
    <!-- folha de estilos CSS -->
    <link rel="stylesheet" href="/sibdas/1240722/medinv/private/assets/admin1240722.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/sibdas/1240722/medinv/assets/fontawesome/all.min.css">
    <!-- Chart.js -->
    <script src="/sibdas/1240722/medinv/assets/chartjs/chart.js"></script>
    <!-- jQuery -->
    <script src="/sibdas/1240722/medinv/private/assets/jquery/jquery-3.6.0.min.js"></script>
    <!-- DataTables CSS + JS -->
    <link rel="stylesheet" href="/sibdas/1240722/medinv/private/assets/datatables/datatables.min.css">
    <script src="/sibdas/1240722/medinv/private/assets/datatables/datatables.min.js"></script> 
    <!-- Java Script -->
    <script src="/sibdas/1240722/medinv/assets/js/1240722.js"></script>
</head>


<body class="<?= $bodyClass ?? '' ?>">