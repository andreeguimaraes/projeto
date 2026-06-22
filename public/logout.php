<?php
require_once __DIR__ . '/../private/includes/funcoes.php';
require_once __DIR__ . '/../config/config.php';
session_start();

// --------------------------------------------------------------------
// REGISTO DE LOG (antes de destruir a sessão)
// --------------------------------------------------------------------
try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    registar_log(
        $ligacao,
        'logout',
        'autenticacao',
        'Sessão terminada: ' . ($_SESSION['utilizador_email'] ?? 'desconhecido')
    );
} catch (PDOException $e) {
    // Falha silenciosa — o logout continua mesmo que o log falhe
}

$ligacao = null;

// --------------------------------------------------------------------
// TERMINAR A SESSÃO
// --------------------------------------------------------------------
session_unset();
session_destroy();

// --------------------------------------------------------------------
// REDIRECIONAMENTO PARA O LOGIN
// --------------------------------------------------------------------
header('Location: ../public/login.php');
return;