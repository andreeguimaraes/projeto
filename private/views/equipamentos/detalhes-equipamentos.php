<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// --------------------------------------------------------------------
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

// Desencriptar e validar o ID
$idEncriptado = $_GET['id_equipamento'] ?? null;
$id = aes_decrypt($idEncriptado);

if (!$id || !is_numeric($id)) {
    header('Location: equipamentos.php');
    exit;
}

$eq = null;
$localizacao = null;
$fornecedores_associados = [];
$garantia = null;
$contrato = null;
$documentos_bd = [];
$erros = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Equipamento + categoria
    $stmt = $ligacao->prepare("
        SELECT e.*, c.nome AS categoria_nome
        FROM equipamentos e
        JOIN categorias_equipamento c ON c.id = e.categoria_id
        WHERE e.id = :id
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $eq = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$eq) {
        header('Location: equipamentos.php');
        exit;
    }

    // Localização + serviço
    $stmtLoc = $ligacao->prepare("
        SELECT l.*, s.nome AS servico_nome
        FROM localizacoes l
        JOIN servicos s ON s.id = l.servico_id
        WHERE l.id = :localizacao_id
    ");
    $stmtLoc->bindParam(':localizacao_id', $eq->localizacao_id, PDO::PARAM_INT);
    $stmtLoc->execute();
    $localizacao = $stmtLoc->fetch(PDO::FETCH_OBJ);

    // Fornecedores associados (múltiplos, com tipo de relação)
    $stmtForn = $ligacao->prepare("
        SELECT f.*, tf.nome AS tipo_nome
        FROM equipamento_fornecedor ef
        JOIN fornecedores f ON f.id = ef.fornecedor_id
        JOIN tipos_fornecedor tf ON tf.id = f.tipo_id
        WHERE ef.equipamento_id = :equipamento_id
        ORDER BY ef.id
    ");
    $stmtForn->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
    $stmtForn->execute();
    $fornecedores_associados = $stmtForn->fetchAll(PDO::FETCH_OBJ);

    // Garantia
    $stmtGarantia = $ligacao->prepare("
        SELECT g.*, tg.nome AS tipo_nome
        FROM garantias g
        JOIN tipos_garantia tg ON tg.id = g.tipo_id
        WHERE g.equipamento_id = :equipamento_id
        LIMIT 1
    ");
    $stmtGarantia->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
    $stmtGarantia->execute();
    $garantia = $stmtGarantia->fetch(PDO::FETCH_OBJ);

    // Contrato
    $stmtContrato = $ligacao->prepare("
        SELECT c.*, tc.nome AS tipo_nome
        FROM contratos c
        JOIN tipos_contrato tc ON tc.id = c.tipo_id
        WHERE c.equipamento_id = :equipamento_id
        LIMIT 1
    ");
    $stmtContrato->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
    $stmtContrato->execute();
    $contrato = $stmtContrato->fetch(PDO::FETCH_OBJ);

    // Documentos
    $stmtDocs = $ligacao->prepare("
        SELECT d.*, td.nome AS tipo_nome
        FROM documentos d
        JOIN tipos_documento td ON td.id = d.tipo_id
        WHERE d.equipamento_id = :equipamento_id
        ORDER BY d.id
    ");
    $stmtDocs->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
    $stmtDocs->execute();
    $documentos_bd = $stmtDocs->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $err) {
    $erros[] = "Erro na ligação à base de dados.";
    $eq = null;
}

$ligacao = null;



$criticidade_label = [
    'baixa' => ['Baixa', 'bg-secondary'],
    'media' => ['Média', 'bg-info text-dark'],
    'alta' => ['Alta', 'bg-warning text-dark'],
    'suporte_de_vida' => ['Suporte de vida', 'bg-danger'],
];

$tipo_entrada_label = [
    'compra' => 'Compra',
    'doacao' => 'Doação',
    'aluguer' => 'Aluguer',
    'emprestimo' => 'Empréstimo',
];

$estado_garantia_label = [
    'ativa' => 'bg-success',
    'expirada' => 'bg-secondary',
    'cancelada' => 'bg-danger',
];

$estado_contrato_label = [
    'ativo' => 'bg-success',
    'expirado' => 'bg-secondary',
    'cancelado' => 'bg-danger',
];

function formatar_data($data)
{
    if (empty($data)) return '—';
    return date('d/m/Y', strtotime($data));
}

function formatar_euros($valor)
{
    if ($valor === null || $valor === '') return '—';
    return number_format((float)$valor, 2, ',', '.') . ' €';
}

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
                    <h2 class="mb-0">Ficha do equipamento</h2>
                    <p class="text-muted mb-0"><?= htmlspecialchars($eq->codigo ?? '') ?> — <?= htmlspecialchars($eq->designacao ?? '') ?></p>
                </div>
                <a href="equipamentos.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <div class="card shadow rounded mt-4">
                <div class="card-body">

                    <!-- Badges de estado -->
                    <div class="d-flex gap-2 mb-3">
                        <?php if ($eq->estado === 'ativo'): ?>
                            <span class="badge bg-success fs-6">Ativo</span>
                        <?php elseif ($eq->estado === 'em_manutencao'): ?>
                            <span class="badge bg-warning text-dark fs-6">Em manutenção</span>
                        <?php elseif ($eq->estado === 'inativo'): ?>
                            <span class="badge bg-secondary fs-6">Inativo</span>
                        <?php elseif ($eq->estado === 'em_calibracao'): ?>
                            <span class="badge bg-info text-dark fs-6">Em calibração</span>
                        <?php elseif ($eq->estado === 'em_quarentena'): ?>
                            <span class="badge bg-danger fs-6">Em quarentena</span>
                        <?php elseif ($eq->estado === 'abatido'): ?>
                            <span class="badge bg-dark fs-6">Abatido</span>
                        <?php endif; ?>
                    </div>

                    <!-- TABS -->
                    <ul class="nav nav-underline border-bottom mb-4">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" aria-current="page" href="#tab-geral">
                                <i class="fas fa-info-circle me-1"></i>Informação geral
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-localizacao">
                                <i class="fas fa-location-dot me-1"></i>Localização
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-fornecedor">
                                <i class="fas fa-truck-medical me-1"></i>Fornecedor
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-garantia">
                                <i class="fas fa-file-contract me-1"></i>Garantia
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-contrato">
                                <i class="fas fa-file-signature me-1"></i>Contrato
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#tab-documentacao">
                                <i class="fas fa-folder-open me-1"></i>Documentação
                            </a>
                        </li>
                    </ul>

                    <!-- CONTEÚDO DAS TABS -->
                    <div class="tab-content">

                        <!-- TAB 1 — Informação geral -->
                        <div class="tab-pane fade show active" id="tab-geral">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Código</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($eq->codigo ?? '') ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Designação</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($eq->designacao ?? '') ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Categoria</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($eq->categoria_nome ?? '') ?></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Marca</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($eq->marca ?? '') ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Modelo</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($eq->modelo ?? '') ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Número de série</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($eq->numero_serie ?? '') ?></p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Fabricante</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($eq->fabricante ?? '—') ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Ano de fabrico</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($eq->ano_fabrico ?? '—') ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Criticidade</label>
                                    <p class="form-control-plaintext">
                                        <?php if ($eq->criticidade === 'baixa'): ?>
                                            <span class="badge bg-secondary">Baixa</span>
                                        <?php elseif ($eq->criticidade === 'media'): ?>
                                            <span class="badge bg-info text-dark">Média</span>
                                        <?php elseif ($eq->criticidade === 'alta'): ?>
                                            <span class="badge bg-warning text-dark">Alta</span>
                                        <?php elseif ($eq->criticidade === 'suporte_de_vida'): ?>
                                            <span class="badge bg-danger">Suporte de vida</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-shopping-cart me-2"></i>Aquisição
                            </h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Data de aquisição</label>
                                    <p class="form-control-plaintext"><?= formatar_data($eq->data_aquisicao ?? null) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Custo de aquisição</label>
                                    <p class="form-control-plaintext"><?= formatar_euros($eq->custo_aquisicao ?? null) ?></p>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tipo de entrada</label>
                                    <p class="form-control-plaintext"><?= htmlspecialchars($tipo_entrada_label[$eq->tipo_entrada ?? ''] ?? '—') ?></p>
                                </div>
                            </div>
                            <hr>
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-note-sticky me-2"></i>Observações
                            </h5>
                            <p class="form-control-plaintext">
                                <?= htmlspecialchars($eq->observacoes ?? 'Sem observações.') ?>
                            </p>
                        </div>

                        <!-- TAB 2 — Localização -->
                        <div class="tab-pane fade" id="tab-localizacao">
                            <?php if (!$localizacao): ?>
                                <p class="text-muted">Sem localização associada.</p>
                            <?php else: ?>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Código</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($localizacao->codigo ?? '') ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Edifício</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($localizacao->edificio ?? '') ?></p>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Piso</label>
                                        <p class="form-control-plaintext">Piso <?= htmlspecialchars($localizacao->piso ?? '') ?></p>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">Sala</label>
                                        <p class="form-control-plaintext">Sala <?= htmlspecialchars($localizacao->sala ?? '') ?></p>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="text-muted mb-3">
                                    <i class="fas fa-hospital me-2"></i>
                                    Serviço / Departamento
                                </h5>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Serviço</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($localizacao->servico_nome ?? '') ?></p>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="../localizacoes/detalhes-localizacoes.php?id_localizacao=<?= htmlspecialchars(aes_encrypt($localizacao->id)) ?>" class="btn btn-primary">
                                        Ver localização
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- TAB 3 — Fornecedor (múltiplos) -->
                        <div class="tab-pane fade" id="tab-fornecedor">
                            <?php if (empty($fornecedores_associados)): ?>
                                <p class="text-muted">Sem fornecedores associados.</p>
                            <?php else: ?>
                                <?php foreach ($fornecedores_associados as $index => $fornecedor): ?>

                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <h6 class="text-muted mb-0">
                                            <i class="fas fa-building me-2"></i><?= htmlspecialchars($fornecedor->nome) ?>
                                        </h6>
                                        <span class="badge bg-primary"><?= htmlspecialchars($fornecedor->tipo_nome ?? '') ?></span>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Nome da empresa</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($fornecedor->nome ?? '') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">NIF</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($fornecedor->nif ?? '') ?></p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Tipo de fornecedor</label>
                                            <p class="form-control-plaintext">
                                                <span class="badge bg-primary"><?= htmlspecialchars($fornecedor->tipo_nome ?? '') ?></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
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
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Morada</label>
                                            <p class="form-control-plaintext"><?= htmlspecialchars($fornecedor->morada ?? '—') ?></p>
                                        </div>
                                    </div>
                                    <h6 class="text-muted mb-3">
                                        <i class="fas fa-address-book me-2"></i>Contactos
                                    </h6>
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
                                                <?= !empty($fornecedor->telefone_contacto) ? htmlspecialchars($fornecedor->telefone_contacto) : '<span class="text-muted">—</span>' ?>
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Email direto</label>
                                            <p class="form-control-plaintext">
                                                <?= !empty($fornecedor->email_contacto) ? htmlspecialchars($fornecedor->email_contacto) : '<span class="text-muted">—</span>' ?>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end gap-2 mb-4">
                                        <a href="../fornecedores/detalhes-fornecedor.php?id_fornecedor=<?= htmlspecialchars(aes_encrypt($fornecedor->id)) ?>" class="btn btn-outline-primary btn-sm">
                                            Ver Fornecedor
                                        </a>
                                    </div>

                                    <?php if ($index < count($fornecedores_associados) - 1): ?>
                                        <hr class="mb-4">
                                    <?php endif; ?>

                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <!-- TAB 4 — Garantia -->
                        <div class="tab-pane fade" id="tab-garantia">
                            <?php if (!$garantia): ?>
                                <p class="text-muted">Sem garantia registada.</p>
                            <?php else: ?>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Código</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($garantia->codigo ?? '') ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Tipo</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($garantia->tipo_nome ?? '') ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de início</label>
                                        <p class="form-control-plaintext"><?= formatar_data($garantia->data_inicio ?? null) ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de fim</label>
                                        <p class="form-control-plaintext"><?= formatar_data($garantia->data_fim ?? null) ?></p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Entidade responsável</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($garantia->entidade_responsavel ?? '—') ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge <?= $estado_garantia_label[$garantia->estado ?? ''] ?? 'bg-secondary' ?>">
                                                <?= htmlspecialchars(ucfirst($garantia->estado ?? '')) ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Ficheiro</label>
                                        <p class="form-control-plaintext">
                                            <?php if (!empty($garantia->ficheiro_path)): ?>
                                                <a href="../../../<?= htmlspecialchars($garantia->ficheiro_path) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file-pdf me-1 text-danger"></i>Ver documento
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Sem ficheiro</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- TAB 5 — Contrato -->
                        <div class="tab-pane fade" id="tab-contrato">
                            <?php if (!$contrato): ?>
                                <p class="text-muted">Sem contrato registado.</p>
                            <?php else: ?>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Código</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($contrato->codigo ?? '') ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Tipo de contrato</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($contrato->tipo_nome ?? '') ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Entidade responsável</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($contrato->entidade_responsavel ?? '—') ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de início</label>
                                        <p class="form-control-plaintext"><?= formatar_data($contrato->data_inicio ?? null) ?></p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de fim</label>
                                        <p class="form-control-plaintext"><?= formatar_data($contrato->data_fim ?? null) ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Periodicidade</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars(ucfirst($contrato->periodicidade ?? '—')) ?></p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge <?= $estado_contrato_label[$contrato->estado ?? ''] ?? 'bg-secondary' ?>">
                                                <?= htmlspecialchars(ucfirst($contrato->estado ?? '')) ?>
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Ficheiro</label>
                                        <p class="form-control-plaintext">
                                            <?php if (!empty($contrato->ficheiro_path)): ?>
                                                <a href="../../../<?= htmlspecialchars($contrato->ficheiro_path) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file-pdf me-1 text-danger"></i>Ver documento
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">Sem ficheiro</span>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Observações</label>
                                        <p class="form-control-plaintext"><?= htmlspecialchars($contrato->observacoes ?? '—') ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- TAB 6 — Documentação -->
                        <div class="tab-pane fade" id="tab-documentacao">
                            <?php if (empty($documentos_bd)): ?>
                                <p class="text-muted">Sem documentos associados.</p>
                            <?php else: ?>
                                <ul class="list-group">
                                    <?php foreach ($documentos_bd as $doc): ?>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class="fas fa-file-pdf text-danger me-2"></i>
                                                <strong><?= htmlspecialchars($doc->nome) ?></strong>
                                                <small class="text-muted ms-2"><?= htmlspecialchars($doc->tipo_nome) ?> — <?= formatar_data($doc->data_documento) ?></small>
                                            </div>
                                            <?php if (!empty($doc->ficheiro_path)): ?>
                                                <a href="../../../<?= htmlspecialchars($doc->ficheiro_path) ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-file-pdf me-1"></i>Ver
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">Sem ficheiro</span>
                                            <?php endif; ?>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                        <!-- fim tab-content -->
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>