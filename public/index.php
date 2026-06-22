<?php
require_once __DIR__ . '/../config/config.php';

// ── Carregar conteúdos dinâmicos da BD ──────────────────────────────────────
$conteudos = [
    'titulo_principal' => 'MEDINV',
    'telefone'         => '',
    'email'            => '',
    'morada'           => '',
    'codigo_postal'    => '',
    'localidade'       => '',
    'horario'          => '',
];

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->query("SELECT chave, valor FROM conteudos_site");
    foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $linha) {
        $conteudos[$linha->chave] = $linha->valor;
    }
} catch (PDOException $e) {
    // Falha silenciosa — os valores por defeito ficam activos
}

$ligacao = null;

$morada_completa = htmlspecialchars($conteudos['morada']);
$cp_localidade   = trim(htmlspecialchars($conteudos['codigo_postal']) . ' ' . htmlspecialchars($conteudos['localidade']));
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($conteudos['titulo_principal']) ?></title>

    <!-- favicon -->
    <link rel="icon" type="image/svg+xml" href="../assets/img/logo_medinv_icon.svg">
    <link rel="stylesheet" href="../assets/fontawesome/all.min.css">
    <link rel="stylesheet" href="../assets/css/1240722.css">
</head>
<body>

    <!-- Navegação -->
    <nav class="bng-navbar">
        <div class="navbar-brand">
            <div class="brand-text">
                <img src="../assets/img/logo_medinv.svg" alt="Logo da empresa">
            </div>
        </div>

        <div class="container-navegacao">
            <a href="index.php">INÍCIO</a>
            <a href="views/quem-somos/quem-somos.php">QUEM SOMOS</a>
            <a href="views/servicos/servicos.php">SERVIÇOS</a>
            <a href="views/contactos/contactos.php">CONTACTOS</a>
        </div>

        <div class="nav-cliente">
            <a href="login.php" target="_blank">Área Restrita</a>
        </div>
    </nav>

    <!-- Banner/Hero -->
    <section class="hero-banner">
        <img src="../assets/img/monitor sinais 3.jpg" alt="Banner principal MEDINV" class="hero-image">
        <div class="hero-overlay">
            <h2>Bem-vindo à MEDINV</h2>
            <p>Gestão inteligente de equipamentos médicos</p>
        </div>
    </section>

    <!-- Quem Somos -->
    <section class="container-texto-generico" id="quem-somos">
        <div class="quem-somos-header">
            <h1>O aliado perfeito para gerir o seu parque tecnológico</h1>
            <p>Desenvolvemos soluções web especializadas para o inventário hospitalar, com rastreabilidade total, gestão documental e controlo do ciclo de vida dos dispositivos médicos.</p>
        </div>

        <div class="quem-somos-layout">
            <div class="quem-somos-left">
                <div class="quem-somos-grid">
                    <article class="quem-somos-card">
                        <h2>Quem somos</h2>
                        <p>A MEDINV é uma empresa portuguesa especializada no desenvolvimento de software para a área da saúde. Nascemos da necessidade real que os hospitais enfrentam diariamente: gerir centenas de equipamentos médicos de forma eficaz, segura e centralizada.</p>
                    </article>
                    <article class="quem-somos-card">
                        <h2>O problema</h2>
                        <p>Em muitas instituições de saúde, a gestão do inventário ainda depende de folhas de Excel dispersas, registos em papel e documentação sem estrutura. Esta realidade compromete a rastreabilidade dos equipamentos, dificulta auditorias e fragiliza a tomada de decisão clínica e administrativa.</p>
                    </article>
                    <article class="quem-somos-card">
                        <h2>A resposta</h2>
                        <p>Desenvolvemos soluções web intuitivas pensadas para a realidade hospitalar portuguesa. A nossa plataforma permite organizar, consultar e atualizar toda a informação relativa ao parque tecnológico de uma instituição de saúde — desde a aquisição de um equipamento até ao seu abate.</p>
                    </article>
                </div>
            </div>
            <div class="quem-somos-right">
                <img src="../assets/img/monitor sinais 2.jpg" alt="Banner do Ginásio" class="quem-somos-side-image">
            </div>
        </div>
    </section>

    <!-- Serviços -->
    <section id="servicos">
        <h2 class="section-title">O que fazemos</h2>
        <div class="servicos-container">
            <div class="servico">
                <i class="fa-solid fa-laptop-medical fa-3x"></i>
                <h3>Software especializado</h3>
                <p>Desenvolvemos soluções web para gestão de inventário hospitalar</p>
            </div>
            <div class="servico">
                <i class="fa-solid fa-hospital fa-3x"></i>
                <h3>Foco na saúde</h3>
                <p>Criados para a realidade dos serviços clínicos atuais</p>
            </div>
            <div class="servico">
                <i class="fa-solid fa-magnifying-glass-chart fa-3x"></i>
                <h3>Rastreabilidade total</h3>
                <p>Controlo do ciclo de vida dos dispositivos médicos</p>
            </div>
            <a href="views/servicos/servicos.php" class="button">Saber mais sobre os nossos serviços</a>
        </div>
    </section>

    <!-- Convite -->
    <section id="convite">
        <div>
            <h2>Quer modernizar a gestão do seu hospital?</h2>
            <a href="views/contactos/contactos.php" class="button">Fale connosco →</a>
        </div>
    </section>

    <!-- Rodapé -->
    <footer class="footer-container">
        <div class="footer-section">
            <strong>LOCALIZAÇÃO</strong>
            <p>
                <?= $morada_completa ?><br>
                <?= $cp_localidade ?><br>
                Portugal
            </p>
        </div>
        <div class="footer-section">
            <strong>HORÁRIO</strong>
            <p><?= nl2br(htmlspecialchars($conteudos['horario'])) ?></p>
        </div>
        <div class="footer-section">
            <strong>CONTACTOS</strong>
            <p>
                Email: <?= htmlspecialchars($conteudos['email']) ?><br>
                Tel: <?= htmlspecialchars($conteudos['telefone']) ?>
            </p>
        </div>
    </footer>

</body>
</html>