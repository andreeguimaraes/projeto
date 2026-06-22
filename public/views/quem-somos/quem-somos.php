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
    <title>Quem Somos - <?= htmlspecialchars($conteudos['titulo_principal']) ?></title>

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
        <img src="../../../assets/img/hospital_corridor.png" alt="Banner Quem Somos - MEDINV" class="hero-image">
        <div class="hero-overlay">
            <h2>Quem Somos</h2>
            <p>Conheça a nossa história e missão</p>
        </div>
    </section>

    <!-- Sobre a MEDINV -->
    <section class="about-section">
        <h2>Sobre a MEDINV</h2>
        <p>
            A MEDINV foi fundada em 2026 com um propósito claro: transformar a forma como os hospitais e instituições de
            saúde gerem o seu parque tecnológico. Nascemos da constatação de uma realidade preocupante — em muitas unidades
            hospitalares, a gestão de equipamentos médicos continua a ser feita com recurso a folhas de Excel
            desatualizadas, registos manuais em papel e documentação técnica sem qualquer estrutura centralizada.
        </p>
        <p>
            Acreditamos que o inventário hospitalar não deve ser entendido como uma simples lista de equipamentos — deve ser a
            base de uma ferramenta de gestão capaz de acompanhar o ciclo de vida completo de cada dispositivo médico, desde a
            sua aquisição e receção até à sua desativação, abate ou substituição.
        </p>
    </section>

    <!-- Valores -->
    <section class="values-section">
        <h2>Os Nossos Valores</h2>
        <div class="values-container">
            <div class="value-card">
                <i class="fas fa-bullseye fa-2x"></i>
                <h3>Rigor</h3>
                <p>Desenvolvemos software pensado para ambientes críticos, onde a precisão da informação pode fazer a diferença.</p>
            </div>
            <div class="value-card">
                <i class="fas fa-award fa-2x"></i>
                <h3>Inovação</h3>
                <p>Apostamos em soluções modernas, acessíveis e preparadas para crescer com as necessidades de cada instituição.</p>
            </div>
            <div class="value-card">
                <i class="fas fa-users fa-2x"></i>
                <h3>Compromisso</h3>
                <p>Estamos ao lado dos nossos clientes em cada fase da implementação, com suporte técnico dedicado e formação especializada.</p>
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