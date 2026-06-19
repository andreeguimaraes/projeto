
<?php
/*
require_once 'includes/funcoes.php';
redirect_if_not_logged();

$total_equipamentos = 0;
$total_ativos = 0;
$total_manutencao = 0;
$total_garantia_expirada = 0;
$total_sem_documentacao = 0;
$erros = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $total_equipamentos = (int) $ligacao->query("SELECT COUNT(*) FROM equipamentos")->fetchColumn();

    $total_ativos = (int) $ligacao->query("SELECT COUNT(*) FROM equipamentos WHERE estado = 'ativo'")->fetchColumn();

    $total_manutencao = (int) $ligacao->query("SELECT COUNT(*) FROM equipamentos WHERE estado = 'em_manutencao'")->fetchColumn();

    $total_garantia_expirada = (int) $ligacao->query("
        SELECT COUNT(*)
        FROM garantias
        WHERE data_fim < CURDATE()
    ")->fetchColumn();

    $total_sem_documentacao = (int) $ligacao->query("
        SELECT COUNT(*)
        FROM equipamentos e
        WHERE NOT EXISTS (
            SELECT 1 FROM documentos d WHERE d.equipamento_id = e.id
        )
    ")->fetchColumn();

} catch (PDOException $e) {
    $erros[] = "Erro na ligação à base de dados.";
}

$ligacao = null;

$nome_utilizador = $_SESSION['utilizador'] ?? 'Utilizador';
$email_utilizador = $_SESSION['utilizador_email'] ?? '';
?>
<!-- cabeçalho -->
<?php include 'includes/header.php'; ?>
    <!-- navbar -->
    <?php include 'includes/nav.php'; ?>

    <div class="container-fluid">
        <div class="row">

            <!-- sidebar -->
            <?php include 'includes/sidebar.php'; ?>


            <!-- Conteúdo Principal -->
            <main class="col-12 p-4">

                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger mb-3">
                        <?php foreach ($erros as $erro): ?>
                            <div><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Boas vindas -->
                <div class="boas-vindas-card mb-4">
                    <div class="d-flex align-items-center gap-4">
                        <div>
                            <h4 class="mb-1">Bem-vindo, <?= htmlspecialchars($nome_utilizador) ?></h4>
                            <p class="mb-1 text-muted">
                                <i class="fas fa-envelope me-1"></i><?= htmlspecialchars($email_utilizador) ?>
                            </p>
                        </div>
                        <div class="ms-auto text-end">
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>Online
                            </span>
                            <p class="text-muted mt-2 mb-0" style="font-size: 0.85rem;">
                                <?= date('d \d\e F \d\e Y') ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Indicadores -->
                <h5 class="mb-3 text-muted">
                    <i class="fas fa-chart-bar me-2"></i>Resumo do inventário
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-hospital-user fa-2x mb-2 text-primary"></i>
                                <h3 class="mb-0"><?= $total_equipamentos ?></h3>
                                <p class="text-muted mb-0">Total de equipamentos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-circle-check fa-2x mb-2 text-success"></i>
                                <h3 class="mb-0"><?= $total_ativos ?></h3>
                                <p class="text-muted mb-0">Ativos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-wrench fa-2x mb-2 text-warning"></i>
                                <h3 class="mb-0"><?= $total_manutencao ?></h3>
                                <p class="text-muted mb-0">Em manutenção</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-triangle-exclamation fa-2x mb-2 text-danger"></i>
                                <h3 class="mb-0"><?= $total_garantia_expirada ?></h3>
                                <p class="text-muted mb-0">Garantias expiradas</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alertas -->
                <h5 class="mb-3 text-muted">
                    <i class="fas fa-bell me-2"></i>Alertas
                </h5>

                <?php if ($total_garantia_expirada > 0): ?>
                    <div class="alert alert-danger d-flex align-items-center gap-2">
                        <i class="fas fa-triangle-exclamation"></i>
                        <span><?= $total_garantia_expirada ?> equipamento<?= $total_garantia_expirada > 1 ? 's' : '' ?> com garantia expirada —
                            <a href="views/equipamentos/equipamentos.php" class="alert-link">ver lista</a>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if ($total_sem_documentacao > 0): ?>
                    <div class="alert alert-info d-flex align-items-center gap-2">
                        <i class="fas fa-file-circle-exclamation"></i>
                        <span><?= $total_sem_documentacao ?> equipamento<?= $total_sem_documentacao > 1 ? 's' : '' ?> sem documentação associada —
                            <a href="views/equipamentos/equipamentos.php" class="alert-link">ver lista</a>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if ($total_garantia_expirada == 0 && $total_sem_documentacao == 0): ?>
                    <div class="alert alert-success d-flex align-items-center gap-2">
                        <i class="fas fa-circle-check"></i>
                        <span>Sem alertas pendentes.</span>
                    </div>
                <?php endif; ?>

            </main>
        </div>
    </div>
<!-- rodapé -->
<?php include 'includes/footer.php'; ?>*/