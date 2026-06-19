<?php
require_once __DIR__ . '/../includes/funcoes.php';
redirect_if_not_logged();

$erros = [];

$total_equipamentos = 0;
$total_ativos = 0;
$total_manutencao = 0;
$total_inativos = 0;
$total_garantia_expirada = 0;
$total_sem_documentacao = 0;
$total_criticidade_elevada = 0;
$equipamentos_por_servico = [];
$equipamentos_por_categoria = [];
$equipamentos_por_localizacao = [];
$suporte_vida_por_servico = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Total de equipamentos
    $total_equipamentos = (int) $ligacao->query("SELECT COUNT(*) FROM equipamentos")->fetchColumn();

    // Ativos
    $total_ativos = (int) $ligacao->query("SELECT COUNT(*) FROM equipamentos WHERE estado = 'ativo'")->fetchColumn();

    // Em manutenção
    $total_manutencao = (int) $ligacao->query("SELECT COUNT(*) FROM equipamentos WHERE estado = 'em_manutencao'")->fetchColumn();

    // Inativos
    $total_inativos = (int) $ligacao->query("SELECT COUNT(*) FROM equipamentos WHERE estado = 'inativo'")->fetchColumn();

    // Garantia expirada (data_fim já passou)
    $total_garantia_expirada = (int) $ligacao->query("
        SELECT COUNT(*)
        FROM garantias
        WHERE data_fim < CURDATE()
    ")->fetchColumn();

    // Sem documentação (equipamentos sem nenhum registo em documentos)
    $total_sem_documentacao = (int) $ligacao->query("
        SELECT COUNT(*)
        FROM equipamentos e
        WHERE NOT EXISTS (
            SELECT 1 FROM documentos d WHERE d.equipamento_id = e.id
        )
    ")->fetchColumn();

    // Equipamentos de criticidade elevada (suporte de vida)
    $total_criticidade_elevada = (int) $ligacao->query("
        SELECT COUNT(*)
        FROM equipamentos
        WHERE criticidade = 'suporte_de_vida'
    ")->fetchColumn();

    // Equipamentos por serviço
    $stmtServico = $ligacao->query("
        SELECT s.nome AS servico, COUNT(e.id) AS total
        FROM servicos s
        LEFT JOIN localizacoes l ON l.servico_id = s.id
        LEFT JOIN equipamentos e ON e.localizacao_id = l.id
        GROUP BY s.id, s.nome
        ORDER BY s.nome
    ");
    $equipamentos_por_servico = $stmtServico->fetchAll(PDO::FETCH_OBJ);

    // Distribuição por categoria
    $stmtCategorias = $ligacao->query("
        SELECT c.nome AS categoria, COUNT(e.id) AS total
        FROM categorias_equipamento c
        LEFT JOIN equipamentos e ON e.categoria_id = c.id
        GROUP BY c.id, c.nome
        ORDER BY total DESC
    ");
    $equipamentos_por_categoria = $stmtCategorias->fetchAll(PDO::FETCH_OBJ);

    // Distribuição por localização (edifício)
    $stmtLocalizacao = $ligacao->query("
        SELECT l.edificio, COUNT(e.id) AS total
        FROM localizacoes l
        LEFT JOIN equipamentos e ON e.localizacao_id = l.id
        GROUP BY l.edificio
        ORDER BY l.edificio
    ");
    $equipamentos_por_localizacao = $stmtLocalizacao->fetchAll(PDO::FETCH_OBJ);

    // Suporte de vida por serviço
    $stmtSuporteVida = $ligacao->query("
        SELECT s.nome AS servico, COUNT(e.id) AS total
        FROM servicos s
        LEFT JOIN localizacoes l ON l.servico_id = s.id
        LEFT JOIN equipamentos e ON e.localizacao_id = l.id AND e.criticidade = 'suporte_de_vida'
        GROUP BY s.id, s.nome
        ORDER BY s.nome
    ");
    $suporte_vida_por_servico = $stmtSuporteVida->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    $erros[] = "Erro na ligação à base de dados.";
}

$ligacao = null;
?>
<?php
require_once '../includes/header.php'; ?>
    <!-- Navbar -->
    <?php include '../includes/nav.php'; ?>

    <div class="container-fluid">
        <div class="row">

            <!-- Offcanvas Sidebar -->
            <?php include '../includes/sidebar.php'; ?>

            <!-- Conteúdo Principal -->
            <main class="col-12 p-4">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">Dashboard</h2>
                        <p class="text-muted mb-0">Visão geral do parque tecnológico hospitalar</p>
                    </div>
                </div>

                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger mb-3">
                        <?php foreach ($erros as $erro): ?>
                            <div><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- INDICADORES MÍNIMOS OBRIGATÓRIOS -->
                <h5 class="mb-3 text-muted">
                    <i class="fas fa-chart-bar me-2"></i>Indicadores gerais
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-hospital-user fa-2x mb-2 text-primary"></i>
                                <h3 class="mb-0"><?= $total_equipamentos ?></h3>
                                <p class="text-muted mb-0 small">Total</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-circle-check fa-2x mb-2 text-success"></i>
                                <h3 class="mb-0"><?= $total_ativos ?></h3>
                                <p class="text-muted mb-0 small">Ativos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-wrench fa-2x mb-2 text-warning"></i>
                                <h3 class="mb-0"><?= $total_manutencao ?></h3>
                                <p class="text-muted mb-0 small">Em manutenção</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-circle-xmark fa-2x mb-2 text-secondary"></i>
                                <h3 class="mb-0"><?= $total_inativos ?></h3>
                                <p class="text-muted mb-0 small">Inativos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-file-circle-xmark fa-2x mb-2 text-danger"></i>
                                <h3 class="mb-0"><?= $total_garantia_expirada ?></h3>
                                <p class="text-muted mb-0 small">Garantia expirada</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-folder-open fa-2x mb-2 text-info"></i>
                                <h3 class="mb-0"><?= $total_sem_documentacao ?></h3>
                                <p class="text-muted mb-0 small">Sem documentação</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-heart-pulse fa-2x mb-2 text-danger"></i>
                                <h3 class="mb-0"><?= $total_criticidade_elevada ?></h3>
                                <p class="text-muted mb-0 small">Suporte de vida</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Distribuição por categoria (gráfico) e por edifício (tabela) -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fas fa-tags me-2"></i>Distribuição por categoria
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <canvas id="graficoCategorias" style="max-height: 280px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fas fa-building me-2"></i>Distribuição por edifício
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Edifício</th>
                                            <th class="text-center">Equipamentos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($equipamentos_por_localizacao)): ?>
                                            <tr>
                                                <td colspan="2" class="text-muted text-center">Sem dados.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($equipamentos_por_localizacao as $linha): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($linha->edificio) ?></td>
                                                    <td class="text-center"><?= (int) $linha->total ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Suporte de vida por serviço + Equipamentos por serviço -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fas fa-heart-pulse me-2"></i>Equipamentos de suporte de vida por serviço
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Serviço</th>
                                            <th class="text-center">Equipamentos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($suporte_vida_por_servico)): ?>
                                            <tr>
                                                <td colspan="2" class="text-muted text-center">Sem dados.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($suporte_vida_por_servico as $linha): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($linha->servico) ?></td>
                                                    <td class="text-center"><?= (int) $linha->total ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fas fa-location-dot me-2"></i>Equipamentos por serviço
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Serviço</th>
                                            <th class="text-center">Equipamentos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($equipamentos_por_servico)): ?>
                                            <tr>
                                                <td colspan="2" class="text-muted text-center">Sem dados.</td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($equipamentos_por_servico as $linha): ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($linha->servico) ?></td>
                                                    <td class="text-center"><?= (int) $linha->total ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- GRÁFICO CHART.JS -->
    <script>
        new Chart(document.getElementById('graficoCategorias'), {
            type: 'doughnut',
            data: {
                labels: <?= json_encode(array_column($equipamentos_por_categoria, 'categoria')) ?>,
                datasets: [{
                    data: <?= json_encode(array_map('intval', array_column($equipamentos_por_categoria, 'total'))) ?>,
                    backgroundColor: ['#1d5c7f', '#d9534f', '#f0ad4e', '#5cb85c', '#9b59b6', '#17a2b8', '#6c757d']
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'right' }
                }
            }
        });
    </script>

<!-- rodapé -->
<?php include '../includes/footer.php'; ?>