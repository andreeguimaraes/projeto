<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
require_once __DIR__ . '/../../includes/validacoes.php';

// Desencriptar e validar o ID
$idEncriptado = $_GET['id_localizacao'] ?? null;
$id = aes_decrypt($idEncriptado);

if (!$id || !is_numeric($id)) {
    header('Location: localizacoes.php');
    exit;
}

$localizacao = null;
$equipamentos_bd = [];
$erros = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar localização + serviço
    $stmt = $ligacao->prepare("
        SELECT 
            l.*,
            s.nome AS servico_nome
        FROM localizacoes l
        JOIN servicos s ON s.id = l.servico_id
        WHERE l.id = :id
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $localizacao = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$localizacao) {
        header('Location: localizacoes.php');
        exit;
    }

    // Buscar equipamentos associados a esta localização
    $stmtEq = $ligacao->prepare("
        SELECT id, codigo, designacao, marca, estado
        FROM equipamentos
        WHERE localizacao_id = :localizacao_id
        ORDER BY codigo
    ");
    $stmtEq->bindParam(':localizacao_id', $id, PDO::PARAM_INT);
    $stmtEq->execute();
    $equipamentos_bd = $stmtEq->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $err) {
    $erros[] = "Erro na ligação à base de dados.";
    $localizacao = null;
}

$ligacao = null;

// Mapeamento de estado (enum da BD) -> texto amigável
$estados_label = [
    'ativo' => 'Operacional',
    'em_manutencao' => 'Em manutenção',
    'inativo' => 'Inativo',
    'em_calibracao' => 'Em calibração',
    'em_quarentena' => 'Em quarentena',
    'abatido' => 'Abatido',
];
?>
<?php
require_once '../../includes/header.php'; ?>

<!-- Navbar -->
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">

    <div class="row">

        <!-- Sidebar -->
        <?php include '../../includes/sidebar.php'; ?>

        <!-- Conteúdo principal -->
        <main class="col-12 p-4">

            <?php if (!empty($erros)): ?>
                <div class="alert alert-danger mb-3">
                    <?php foreach ($erros as $erro): ?>
                        <div><?= htmlspecialchars($erro) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if ($localizacao): ?>

                <!-- Cabeçalho -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h2 class="mb-0">
                            Detalhes da localização
                        </h2>

                        <p class="text-muted mb-0">
                            <?= htmlspecialchars($localizacao->servico_nome ?? '') ?> — Sala <?= htmlspecialchars($localizacao->sala ?? '') ?>
                        </p>

                    </div>

                    <a href="localizacoes.php"
                        class="btn btn-outline-secondary">

                        <i class="fas fa-arrow-left me-2"></i>
                        Voltar

                    </a>

                </div>

                <!-- Card principal -->
                <div class="card shadow rounded">

                    <div class="card-body">

                        <!-- Cabeçalho -->
                        <h4 class="mb-2 d-flex align-items-center gap-2">
                            <?= htmlspecialchars($localizacao->servico_nome ?? '') ?>
                            <?php if ($localizacao->ativo == 1): ?>
                                <span class="badge bg-success">Ativa</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inativa</span>
                            <?php endif; ?>
                        </h4>

                        <hr>

                        <!-- Dados da localização -->
                        <h5 class="text-muted mb-3">

                            <i class="fas fa-building me-2"></i>
                            Informações da localização

                        </h5>

                        <div class="row mb-4">

                            <div class="col-md-3">

                                <label class="form-label fw-bold">
                                    Código
                                </label>

                                <p class="form-control-plaintext">
                                    <?= htmlspecialchars($localizacao->codigo ?? '') ?>
                                </p>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label fw-bold">
                                    Edifício
                                </label>

                                <p class="form-control-plaintext">
                                    <?= htmlspecialchars($localizacao->edificio ?? '') ?>
                                </p>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label fw-bold">
                                    Piso
                                </label>

                                <p class="form-control-plaintext">
                                    Piso <?= htmlspecialchars($localizacao->piso ?? '') ?>
                                </p>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label fw-bold">
                                    Sala
                                </label>

                                <p class="form-control-plaintext">
                                    Sala <?= htmlspecialchars($localizacao->sala ?? '') ?>
                                </p>

                            </div>
                        </div>

                        <hr>

                        <!-- Serviço -->
                        <h5 class="text-muted mb-3">

                            <i class="fas fa-hospital me-2"></i>
                            Serviço / Departamento

                        </h5>

                        <div class="row mb-4">

                            <div class="col-md-6">

                                <label class="form-label fw-bold">
                                    Serviço
                                </label>

                                <p class="form-control-plaintext">
                                    <?= htmlspecialchars($localizacao->servico_nome ?? '') ?>
                                </p>

                            </div>
                        </div>

                        <hr>

                        <!-- Equipamentos associados -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-stethoscope me-2"></i>
                            Equipamentos associados (<?= count($equipamentos_bd) ?>)
                        </h5>

                        <?php if (empty($equipamentos_bd)): ?>

                            <p class="text-muted mb-4">
                                Sem equipamentos associados a esta localização.
                            </p>

                        <?php else: ?>

                            <div class="table-responsive mb-4">

                                <table class="table table-hover align-middle">

                                    <thead class="table-light">

                                        <tr>

                                            <th>Código</th>
                                            <th>Equipamento</th>
                                            <th>Marca</th>
                                            <th>Estado</th>
                                            <th>Ações</th>

                                        </tr>

                                    </thead>

                                    <tbody>

                                        <?php foreach ($equipamentos_bd as $eq): ?>

                                            <tr>

                                                <td><?= htmlspecialchars($eq->codigo) ?></td>

                                                <td><?= htmlspecialchars($eq->designacao) ?></td>

                                                <td><?= htmlspecialchars($eq->marca) ?></td>

                                                <td>
                                                    <span>
                                                        <?= htmlspecialchars($estados_label[$eq->estado] ?? $eq->estado) ?>
                                                    </span>
                                                </td>

                                                <td>
                                                    <a href="../equipamentos/detalhes-equipamentos.php?id_equipamento=<?= htmlspecialchars(aes_encrypt($eq->id)) ?>"
                                                        class="btn btn-sm btn-outline-primary"
                                                        title="Ver detalhes do equipamento">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>

                                            </tr>

                                        <?php endforeach; ?>

                                    </tbody>

                                </table>

                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endif; ?>

        </main>

    </div>

</div>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>