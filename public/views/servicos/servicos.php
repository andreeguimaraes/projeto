<?php
require_once __DIR__ . '/../../../config/config.php';

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
    <title><?= htmlspecialchars($conteudos['titulo_principal']) ?> - Serviços</title>

    <!-- favicon -->
    <link rel="icon" type="image/svg+xml" href="../../../assets/img/logo_medinv_icon.svg">
    <link rel="stylesheet" href="../../../assets/fontawesome/all.min.css">
    <link rel="stylesheet" href="../../../assets/css/1240722.css">
</head>
<body>

    <!-- Navegação -->
    <nav class="bng-navbar">
        <div class="navbar-brand">
            <div class="brand-text">
                <img src="../../../assets/img/logo_medinv.svg" alt="Logo da empresa">
            </div>
        </div>

        <div class="container-navegacao">
            <a href="../../index.php">INÍCIO</a>
            <a href="../quem-somos/quem-somos.php">QUEM SOMOS</a>
            <a href="../servicos/servicos.php">SERVIÇOS</a>
            <a href="../contactos/contactos.php">CONTACTOS</a>
        </div>

        <div class="nav-cliente">
            <a href="../../login.php" target="_blank">Área Restrita</a>
        </div>
    </nav>

    <!-- Banner/Hero -->
    <section class="hero-banner">
        <img src="../../../assets/img/equipamentos.oticos.jpg" alt="Banner Serviços - MEDINV" class="hero-image">
        <div class="hero-overlay">
            <h2>Os Nossos Serviços</h2>
            <p>Gestão inteligente do inventário hospitalar</p>
        </div>
    </section>

    <!-- Intro -->
    <section id="servicos-intro" class="section-block section-intro">
        <div class="quem-somos-header">
            <h1 class="section-title">A nossa plataforma</h1>
            <p>A MEDINV desenvolveu uma solução web completa para a gestão do inventário hospitalar, acessível via
                navegador, sem necessidade de instalação. Desenhada de raiz para a realidade dos serviços clínicos, a
                plataforma centraliza toda a informação relativa ao parque tecnológico de uma instituição de saúde —
                eliminando folhas de cálculo dispersas, registos em papel e documentação sem estrutura.</p>
        </div>
    </section>

    <!-- O que fazemos -->
    <section id="servicos-overview" class="section-block section-overview">
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
        </div>
    </section>

    <!-- Cards de serviços -->
    <section id="servicos-cards" class="servicos-cards-section section-block">
        <h2 class="section-title">Os nossos serviços</h2>
        <div class="servicos-container-2">
            <div class="servico-2">
                <i class="fas fa-clipboard-list fa-3x"></i>
                <h3>Inventário de equipamentos</h3>
                <p>Registe e consulte todos os equipamentos médicos num único lugar. Cada dispositivo tem uma ficha completa
                    com código interno, marca, modelo, número de série, ano de fabrico, estado atual e nível de criticidade
                    clínica.</p>
            </div>
            <div class="servico-2">
                <i class="fas fa-location-dot fa-3x"></i>
                <h3>Gestão de Localizações</h3>
                <p>Saiba sempre onde cada equipamento se encontra. A plataforma organiza o parque tecnológico por edifício,
                    piso, serviço e sala, permitindo localizar qualquer dispositivo em segundos.</p>
            </div>
            <div class="servico-2">
                <i class="fas fa-folder-open fa-3x"></i>
                <h3>Gestão de documentos</h3>
                <p>Associe documentação técnica e administrativa a cada equipamento — manuais de utilizador, certificados de
                    calibração, contratos de manutenção, faturas e declarações de conformidade — tudo acessível e
                    organizado.</p>
            </div>
            <div class="servico-2">
                <i class="fas fa-truck-medical fa-3x"></i>
                <h3>Gestão de Fornecedores</h3>
                <p>Mantenha um registo centralizado de fabricantes, distribuidores e empresas de assistência técnica. Cada
                    fornecedor é associado diretamente aos equipamentos que fornece ou mantém, com contactos e histórico
                    sempre disponíveis.</p>
            </div>
            <div class="servico-2">
                <i class="fas fa-file-contract fa-3x"></i>
                <h3>Garantias e contratos</h3>
                <p>Consulte datas de início e fim de garantia, tipo de contrato de manutenção e entidade responsável. Visualize
                    de forma destacada as garantias e contratos já expirados, e evite lapsos contratuais que possam comprometer a operação.</p>
            </div>
            <div class="servico-2">
                <i class="fas fa-chart-line fa-3x"></i>
                <h3>Dashboard e Indicadores</h3>
                <p>Aceda a uma visão global do estado do parque tecnológico, com indicadores como equipamentos ativos, em
                    manutenção ou sem documentação associada — apoiando a tomada de decisão.</p>
            </div>
        </div>
    </section>

    <!-- Convite -->
    <section id="convite">
        <div>
            <h2>Quer modernizar a gestão do seu hospital?</h2>
            <a href="../contactos/contactos.php" class="button">Fale connosco →</a>
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