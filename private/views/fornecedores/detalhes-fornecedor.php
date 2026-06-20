<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
require_once __DIR__ . '/../../includes/validacoes.php';

// Desencriptar e validar o ID
$idEncriptado = $_GET['id_fornecedor'] ?? null;
$id = aes_decrypt($idEncriptado);

if (!$id || !is_numeric($id)) {
    header('Location: fornecedor.php');
    exit;
}

$fornecedor = null;
$equipamentos_bd = [];
$erros = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar fornecedor + tipo
    $stmt = $ligacao->prepare("
        SELECT 
            f.*,
            tf.nome AS tipo_nome
        FROM fornecedores f
        JOIN tipos_fornecedor tf ON tf.id = f.tipo_id
        WHERE f.id = :id
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $fornecedor = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$fornecedor) {
        header('Location: fornecedor.php');
        exit;
    }

    // Buscar equipamentos associados a este fornecedor
    $stmtEq = $ligacao->prepare("
        SELECT e.id, e.codigo, e.designacao, e.estado
        FROM equipamentos e
        JOIN equipamento_fornecedor ef ON ef.equipamento_id = e.id
        WHERE ef.fornecedor_id = :fornecedor_id
        ORDER BY e.codigo
    ");
    $stmtEq->bindParam(':fornecedor_id', $id, PDO::PARAM_INT);
    $stmtEq->execute();
    $equipamentos_bd = $stmtEq->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $err) {
    $erros[] = "Erro na ligação à base de dados.";
    $fornecedor = null;
}

$ligacao = null;

// Mapeamento de estado (enum da BD) -> texto + classe de badge
$estados_label = [
    'ativo' => ['Ativo', 'bg-success'],
    'em_manutencao' => ['Em manutenção', 'bg-warning text-dark'],
    'inativo' => ['Inativo', 'bg-secondary'],
    'em_calibracao' => ['Em calibração', 'bg-info text-dark'],
    'em_quarentena' => ['Em quarentena', 'bg-danger'],
    'abatido' => ['Abatido', 'bg-dark'],
];
?>
<?php include '../../includes/header.php'; ?>

<!-- Navbar -->
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <!-- Offcanvas Sidebar -->
        <?php include '../../includes/sidebar.php'; ?>

        <!-- Conteúdo Principal -->
        <main class="col-12 p-4">

            <?php if (!empty($erros)): ?>
                <div class="alert alert-danger mb-3">
                    <?php foreach ($erros as $erro): ?>
                        <div><?= htmlspecialchars($erro) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Cabeçalho -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Ficha do Fornecedor</h2>
                </div>
                <a href="fornecedor.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <div class="card shadow rounded">
                <div class="card-body">

                    <h4 class="mb-3 d-flex align-items-center gap-2">
                        <?= htmlspecialchars($fornecedor->nome ?? '') ?>
                        <?php if (($fornecedor->ativo ?? 0) == 1): ?>
                            <span class="badge bg-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inativo</span>
                        <?php endif; ?>
                    </h4>
                    <hr>

                    <!-- INFORMAÇÃO GERAL -->
                    <h5 class="text-muted mb-3">
                        <i class="fas fa-info-circle me-2"></i>Informação geral
                    </h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Código</label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($fornecedor->codigo ?? '') ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Nome da empresa</label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($fornecedor->nome ?? '') ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">NIF</label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($fornecedor->nif ?? '') ?></p>
                        </div>

                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Tipo de fornecedor</label>
                            <p class="form-control-plaintext">
                                <span class="badge bg-primary"><?= htmlspecialchars($fornecedor->tipo_nome ?? '') ?></span>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Morada</label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($fornecedor->morada ?? '') ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Website</label>
                            <p class="form-control-plaintext">
                                <?php if (!empty($fornecedor->website)): ?>
                                    <a href="<?= htmlspecialchars($fornecedor->website) ?>" target="_blank">
                                        <?= htmlspecialchars($fornecedor->website) ?>
                                        <i class="fas fa-external-link-alt ms-1 small"></i>
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <hr>

                    <!-- CONTACTOS -->
                    <h5 class="text-muted mb-3">
                        <i class="fas fa-address-book me-2"></i>Contactos
                    </h5>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Telefone</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-phone me-1 text-muted"></i><?= htmlspecialchars($fornecedor->telefone ?? '') ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Email</label>
                            <p class="form-control-plaintext">
                                <i class="fas fa-envelope me-1 text-muted"></i><?= htmlspecialchars($fornecedor->email ?? '') ?>
                            </p>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Pessoa de contacto</label>
                            <p class="form-control-plaintext"><?= htmlspecialchars($fornecedor->pessoa_contacto ?? '—') ?></p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Telefone direto</label>
                            <p class="form-control-plaintext">
                                <?php if (!empty($fornecedor->telefone_contacto)): ?>
                                    <i class="fas fa-phone me-1 text-muted"></i><?= htmlspecialchars($fornecedor->telefone_contacto) ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Email direto</label>
                            <p class="form-control-plaintext">
                                <?php if (!empty($fornecedor->email_contacto)): ?>
                                    <i class="fas fa-envelope me-1 text-muted"></i><?= htmlspecialchars($fornecedor->email_contacto) ?>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                    <hr>

                    <!-- EQUIPAMENTOS ASSOCIADOS -->
                    <h5 class="text-muted mb-3">
                        <i class="fas fa-stethoscope me-2"></i>
                        Equipamentos associados (<?= count($equipamentos_bd) ?>)
                    </h5>

                    <?php if (empty($equipamentos_bd)): ?>

                        <p class="text-muted mb-4">
                            Sem equipamentos associados a este fornecedor.
                        </p>

                    <?php else: ?>

                        <div class="mb-4">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Designação</th>
                                        <th>Tipo de relação</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($equipamentos_bd as $eq): ?>
                                        <?php
                                        $estadoInfo = $estados_label[$eq->estado] ?? [$eq->estado, 'bg-secondary'];
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($eq->codigo) ?></td>
                                            <td><?= htmlspecialchars($eq->designacao) ?></td>
                                            <td><span class="badge bg-primary"><?= htmlspecialchars($fornecedor->tipo_nome ?? '') ?></span></td>
                                            <td><span class="badge <?= $estadoInfo[1] ?>"><?= htmlspecialchars($estadoInfo[0]) ?></span></td>
                                            <td>
                                                <a href="../equipamentos/detalhes-equipamentos.php?id_equipamento=<?= htmlspecialchars(aes_encrypt($eq->id)) ?>" class="btn btn-sm btn-outline-primary">
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

        </main>
    </div>
</div>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>