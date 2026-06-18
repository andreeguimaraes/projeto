<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
require_once __DIR__ . '/../../includes/validacoes.php';


// Só aceita GET e POST
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/MEDINV/public/login.php');
    exit;
}

// Desencriptar e validar o ID
$idEncriptado = $_GET['id_equipamento'] ?? null;
$id = aes_decrypt($idEncriptado);

if (!$id || !is_numeric($id)) {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/equipamentos.php');
    exit;
}

$eq = null;
$categorias = [];
$erros = [];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {



    // recolher os dados do formulário
    //$codigo = trim($_POST['codigo'] ?? '');
    $designacao    = trim($_POST['designacao']    ?? '');
    $categoria_id  = trim($_POST['categoria_id']  ?? '');
    $marca         = trim($_POST['marca']         ?? '');
    $modelo        = trim($_POST['modelo']        ?? '');
    $numero_serie  = trim($_POST['numero_serie']  ?? '');
    $fabricante    = trim($_POST['fabricante']    ?? '');
    $ano_fabrico   = trim($_POST['ano_fabrico']   ?? '');
    $criticidade   = trim($_POST['criticidade']   ?? '');
    $estado        = trim($_POST['estado']        ?? '');
    $data_aquisicao   = trim($_POST['data_aquisicao']   ?? '');
    $custo_aquisicao  = trim($_POST['custo_aquisicao']  ?? '');
    $tipo_entrada     = trim($_POST['tipo_entrada']     ?? '');
    $observacoes      = trim($_POST['observacoes']      ?? '');
    $localizacao_id      = trim($_POST['localizacao_id']      ?? '');
    $fornecedor_id       = trim($_POST['fornecedor_id']       ?? '');
    $tipo_garantia       = trim($_POST['tipo_garantia']       ?? '');
    $data_inicio_garantia = trim($_POST['data_inicio_garantia'] ?? '');
    $data_fim_garantia   = trim($_POST['data_fim_garantia']   ?? '');
    $tipo_contrato       = trim($_POST['tipo_contrato']       ?? '');
    $entidade_contrato   = trim($_POST['entidade_contrato']   ?? '');
    $data_inicio_contrato = trim($_POST['data_inicio_contrato'] ?? '');
    $data_fim_contrato   = trim($_POST['data_fim_contrato']   ?? '');

    // ----------------------------------------------------------------
    // 3. VALIDAR
    // ----------------------------------------------------------------
    $erros = array_merge(
        //validar_codigo($codigo),
        validar_designacao($designacao),
        validar_categoria($categoria_id),
        validar_marca($marca),
        validar_modelo($modelo),
        validar_numero_serie($numero_serie),
        validar_fabricante($fabricante),
        validar_ano_fabrico($ano_fabrico),
        validar_criticidade($criticidade),
        validar_estado($estado),
        validar_data_aquisicao($data_aquisicao),
        validar_custo_aquisicao($custo_aquisicao),
        validar_tipo_entrada($tipo_entrada),
        validar_localizacao($localizacao_id),
        validar_fornecedor($fornecedor_id),
        validar_garantia($tipo_garantia, $data_inicio_garantia, $data_fim_garantia),
        validar_contrato($tipo_contrato, $data_inicio_contrato, $data_fim_contrato, $entidade_contrato)
    );


    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $ligacao->prepare("
                UPDATE equipamentos
                SET designacao      = :designacao,
                    categoria_id    = :categoria_id,
                    marca           = :marca,
                    modelo          = :modelo,
                    numero_serie    = :numero_serie,
                    fabricante      = :fabricante,
                    ano_fabrico     = :ano_fabrico,
                    criticidade     = :criticidade,
                    estado          = :estado,
                    data_aquisicao  = :data_aquisicao,
                    custo_aquisicao = :custo_aquisicao,
                    tipo_entrada    = :tipo_entrada,
                    localizacao_id  = :localizacao_id,
                    observacoes     = :observacoes
                WHERE id = :id
            ");

            $stmt->bindParam(':localizacao_id', $localizacao_id, PDO::PARAM_INT);

            $stmt->bindParam(':designacao',    $designacao,    PDO::PARAM_STR);
            $stmt->bindParam(':categoria_id',  $categoria_id,  PDO::PARAM_INT);
            $stmt->bindParam(':marca',         $marca,         PDO::PARAM_STR);
            $stmt->bindParam(':modelo',        $modelo,        PDO::PARAM_STR);
            $stmt->bindParam(':numero_serie',  $numero_serie,  PDO::PARAM_STR);
            $stmt->bindParam(':fabricante',    $fabricante,    PDO::PARAM_STR);
            $stmt->bindParam(':ano_fabrico',   $ano_fabrico,   PDO::PARAM_STR);
            $stmt->bindParam(':criticidade',   $criticidade,   PDO::PARAM_STR);
            $stmt->bindParam(':estado',        $estado,        PDO::PARAM_STR);
            $stmt->bindParam(':data_aquisicao',  $data_aquisicao,  PDO::PARAM_STR);
            $stmt->bindParam(':custo_aquisicao', $custo_aquisicao, PDO::PARAM_STR);
            $stmt->bindParam(':tipo_entrada',    $tipo_entrada,    PDO::PARAM_STR);
            $stmt->bindParam(':observacoes',     $observacoes,     PDO::PARAM_STR);
            $stmt->bindParam(':id',              $id,              PDO::PARAM_INT);

            $stmt->execute();

            header('Location: equipamentos.php');
            exit;
        } catch (PDOException $err) {
            $erros[] = "Erro ao atualizar: " . $err->getMessage();
        }
    }
}
try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->prepare("SELECT * FROM equipamentos WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $eq = $stmt->fetch(PDO::FETCH_OBJ);


    if (!$eq) {
        header('Location: equipamentos.php');
        exit;
    }
    $stmtCat = $ligacao->prepare("SELECT * FROM categorias_equipamento ORDER BY nome");
    $stmtCat->execute();
    $categorias = $stmtCat->fetchAll(PDO::FETCH_OBJ);


    // Adicionar ao SELECT inicial (junto ao $categorias)
    $stmtLoc = $ligacao->prepare("
    SELECT l.id, l.edificio, l.piso, l.sala, s.nome AS servico
    FROM localizacoes l
    JOIN servicos s ON s.id = l.servico_id
    ORDER BY s.nome, l.sala
");
    $stmtLoc->execute();
    $localizacoes_bd = $stmtLoc->fetchAll(PDO::FETCH_OBJ);

    $stmtForn = $ligacao->prepare("
    SELECT f.id, f.nome, f.nif, f.morada, f.website,
        f.telefone, f.email, f.pessoa_contacto, f.telefone_contacto, f.email_contacto,
        tf.nome AS tipo
    FROM fornecedores f
    JOIN tipos_fornecedor tf ON tf.id = f.tipo_id
    ORDER BY f.nome
");
    $stmtForn->execute();
    $fornecedores_bd = $stmtForn->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $err) {
    $erros[] = "Erro na ligação à base de dados.";
    $eq = null;
}
$ligacao = null;
?>
<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<!-- Navbar -->
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>

        <main class="col-12 p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Editar equipamento</h2>
                    <p class="text-muted mb-0"><?= htmlspecialchars($eq->codigo ?? '') ?> — <?= htmlspecialchars($eq->designacao ?? '') ?></p>
                </div>
                <a href="equipamentos.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <form action="editar-equipamentos.php?id_equipamento=<?= $idEncriptado ?>" method="post">
                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger mb-3">
                        <?php foreach ($erros as $erro): ?>
                            <div><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="card shadow rounded mt-4">
                    <div class="card-body">

                        <ul class="nav nav-underline border-bottom mb-4" id="equipTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-geral" data-bs-toggle="tab" href="#geral" role="tab">
                                    <i class="fas fa-info-circle me-1"></i>Informação geral
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-localizacao" data-bs-toggle="tab" href="#localizacao" role="tab">
                                    <i class="fas fa-location-dot me-1"></i>Localização
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-fornecedor" data-bs-toggle="tab" href="#fornecedor" role="tab">
                                    <i class="fas fa-truck-medical me-1"></i>Fornecedor
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-garantia" data-bs-toggle="tab" href="#garantia" role="tab">
                                    <i class="fas fa-shield-halved me-1"></i>Garantia
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-contrato" data-bs-toggle="tab" href="#contrato" role="tab">
                                    <i class="fas fa-file-signature me-1"></i>Contrato
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="tab-docs" data-bs-toggle="tab" href="#documentacao" role="tab">
                                    <i class="fas fa-folder-open me-1"></i>Documentação
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content">

                            <!-- TAB: INFORMAÇÃO GERAL -->
                            <div class="tab-pane fade show active" id="geral" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Código interno <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="codigo" value="<?= htmlspecialchars($eq->codigo ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Designação <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="designacao"
                                            value="<?= htmlspecialchars($eq->designacao ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Categoria <span class="text-danger">*</span></label>
                                        <select class="form-select" name="categoria_id" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach ($categorias as $cat): ?>
                                                <option value="<?= $cat->id ?>"
                                                    <?= $eq->categoria_id == $cat->id ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cat->nome) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Marca <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="marca"
                                            value="<?= htmlspecialchars($eq->marca  ?? '') ?>" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Modelo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="modelo"
                                            value="<?= htmlspecialchars($eq->modelo  ?? '') ?>">

                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Número de série <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="numero_serie"
                                            value="<?= htmlspecialchars($eq->numero_serie ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Fabricante</label>
                                        <input type="text" class="form-control" name="fabricante" value="<?= htmlspecialchars($eq->fabricante ?? '') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Ano de fabrico</label>
                                        <input type="number" class="form-control" name="ano_fabrico" value="<?= $eq->ano_fabrico ?? '' ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Criticidade <span class="text-danger">*</span></label>
                                        <select class="form-select" name="criticidade">
                                            <option value="baixa" <?= $eq->criticidade == 'baixa' ? 'selected' : '' ?>>Baixa</option>
                                            <option value="media" <?= $eq->criticidade == 'media' ? 'selected' : '' ?>>Média</option>
                                            <option value="alta" <?= $eq->criticidade == 'alta' ? 'selected' : '' ?>>Alta</option>
                                            <option value="suporte_de_vida" <?= $eq->criticidade == 'suporte_de_vida' ? 'selected' : '' ?>>Suporte de vida</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Estado atual <span class="text-danger">*</span></label>
                                        <select class="form-select" name="estado" required>
                                            <option value="ativo" <?= $eq->estado == 'ativo'          ? 'selected' : '' ?>>Ativo</option>
                                            <option value="em_manutencao" <?= $eq->estado == 'em_manutencao'  ? 'selected' : '' ?>>Em manutenção</option>
                                            <option value="inativo" <?= $eq->estado == 'inativo'        ? 'selected' : '' ?>>Inativo</option>
                                            <option value="em_calibracao" <?= $eq->estado == 'em_calibracao'  ? 'selected' : '' ?>>Em calibração</option>
                                            <option value="em_quarentena" <?= $eq->estado == 'em_quarentena'  ? 'selected' : '' ?>>Em quarentena</option>
                                            <option value="abatido" <?= $eq->estado == 'abatido'        ? 'selected' : '' ?>>Abatido</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="text-muted mb-3"><i class="fas fa-shopping-cart me-2"></i>Aquisição</h5>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Data de aquisição</label>
                                        <input type="date" class="form-control" name="data_aquisicao" value="<?= $eq->data_aquisicao ?? '' ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Custo de aquisição (€)</label>
                                        <input type="number" class="form-control" name="custo_aquisicao" step="0.01" min="0" value="<?= $eq->custo_aquisicao ?? '' ?>">

                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Tipo de entrada</label>
                                        <select class="form-select" name="tipo_entrada">
                                            <option value="">Selecione...</option>
                                            <option value="compra" <?= $eq->tipo_entrada == 'compra'      ? 'selected' : '' ?>>Compra</option>
                                            <option value="doacao" <?= $eq->tipo_entrada == 'doacao'      ? 'selected' : '' ?>>Doação</option>
                                            <option value="aluguer" <?= $eq->tipo_entrada == 'aluguer'     ? 'selected' : '' ?>>Aluguer</option>
                                            <option value="emprestimo" <?= $eq->tipo_entrada == 'emprestimo'  ? 'selected' : '' ?>>Empréstimo</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="text-muted mb-3"><i class="fas fa-note-sticky me-2"></i>Observações</h5>
                                <div class="mb-4">
                                    <textarea class="form-control" name="observacoes" rows="4"><?= htmlspecialchars($eq->observacoes ?? '') ?></textarea>
                                </div>
                                <p class="text-muted small"><span class="text-danger">*</span> Campos obrigatórios</p>
                                <div class="d-flex justify-content-between">
                                    <div></div> <!-- espaço vazio para alinhar à direita -->
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary"
                                            onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-localizacao')).show()">
                                            Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB: LOCALIZAÇÃO -->
                            <div class="tab-pane fade" id="localizacao" role="tabpanel">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Selecionar localização</label>
                                        <select class="form-select" name="localizacao_id" id="selectLocalizacao" onchange="preencherLocalizacao()">
                                            <option value="">Selecione...</option>
                                            <?php foreach ($localizacoes_bd as $loc): ?>
                                                <option value="<?= $loc->id ?>"
                                                    <?= $eq->localizacao_id == $loc->id ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($loc->servico) ?> — Sala <?= htmlspecialchars($loc->sala) ?> — Piso <?= htmlspecialchars($loc->piso) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="infoLocalizacao">
                                    <hr>
                                    <h6 class="text-muted mb-3"><i class="fas fa-location-dot me-2"></i>Informação da localização</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Edifício</label>
                                            <p class="form-control-plaintext" id="l-edificio">Principal</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Piso</label>
                                            <p class="form-control-plaintext" id="l-piso">2</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Serviço</label>
                                            <p class="form-control-plaintext" id="l-servico">UCI</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Sala</label>
                                            <p class="form-control-plaintext" id="l-sala">201</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-geral')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary"
                                            onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-fornecedor')).show()">
                                            Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB: FORNECEDOR -->
                            <div class="tab-pane fade" id="fornecedor" role="tabpanel">
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Selecionar fornecedor</label>
                                        <select class="form-select" name="fornecedor_id" id="selectFornecedor"
                                            onchange="preencherFornecedor()">
                                            <option value="">Selecione...</option>
                                            <option value="1" selected>Philips Healthcare Portugal</option>
                                            <option value="2">Dräger Portugal</option>
                                            <option value="3">B. Braun Portugal</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="infoFornecedor">
                                    <hr>
                                    <h6 class="text-muted mb-3"><i class="fas fa-building me-2"></i>Informação do fornecedor</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Nome da empresa</label>
                                            <p class="form-control-plaintext" id="f-nome">Philips Healthcare Portugal</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">NIF</label>
                                            <p class="form-control-plaintext" id="f-nif">500 123 456</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Tipo de fornecedor</label>
                                            <p class="form-control-plaintext" id="f-tipo">Fabricante</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Morada</label>
                                            <p class="form-control-plaintext" id="f-morada">Av. da Liberdade, 110, Lisboa</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Website</label>
                                            <p class="form-control-plaintext" id="f-website">www.philips.pt</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6 class="text-muted mb-3"><i class="fas fa-address-book me-2"></i>Contactos</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Telefone</label>
                                            <p class="form-control-plaintext" id="f-telefone">+351 210 000 000</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Email</label>
                                            <p class="form-control-plaintext" id="f-email">geral@philips.pt</p>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Pessoa de contacto</label>
                                            <p class="form-control-plaintext" id="f-contacto">João Ferreira</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Telefone direto</label>
                                            <p class="form-control-plaintext" id="f-tel-direto">+351 962 000 000</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Email direto</label>
                                            <p class="form-control-plaintext" id="f-email-direto">joao.ferreira@philips.pt</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-geral')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary"
                                            onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-garantia')).show()">
                                            Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB: GARANTIA -->
                            <div class="tab-pane fade" id="garantia" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Código</label>
                                        <input type="text" class="form-control" name="codigo_garantia" value="GAR001" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Tipo</label>
                                        <select class="form-select" name="tipo_garantia">
                                            <option value="">Selecione...</option>
                                            <option selected>Garantia do fabricante</option>
                                            <option>Garantia estendida</option>
                                            <option>Sem garantia</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de início</label>
                                        <input type="date" class="form-control" name="data_inicio_garantia" value="2022-03-15">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de fim</label>
                                        <input type="date" class="form-control" name="data_fim_garantia" value="2027-03-15">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Entidade responsável</label>
                                        <select class="form-select" name="entidade_garantia">
                                            <option value="">Selecione...</option>
                                            <option selected>Philips Healthcare Portugal</option>
                                            <option>Dräger Portugal</option>
                                            <option>B. Braun</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <select class="form-select" name="estado_garantia">
                                            <option value="">Selecione...</option>
                                            <option selected>Ativa</option>
                                            <option>Expirada</option>
                                            <option>Cancelada</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Ficheiro</label>
                                        <input type="file" class="form-control" name="ficheiro_garantia" accept=".pdf,.doc,.docx">
                                        <small class="text-muted">Ficheiro atual: garantia_eq001.pdf</small>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-geral')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary"
                                            onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-contrato')).show()">
                                            Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB: CONTRATO -->
                            <div class="tab-pane fade" id="contrato" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Código</label>
                                        <input type="text" class="form-control" name="codigo_contrato" value="CON001" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Tipo de contrato</label>
                                        <select class="form-select" name="tipo_contrato">
                                            <option value="">Selecione...</option>
                                            <option selected>Manutenção preventiva</option>
                                            <option>Manutenção corretiva</option>
                                            <option>Manutenção total</option>
                                            <option>Sem contrato</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Entidade responsável</label>
                                        <select class="form-select" name="entidade_contrato">
                                            <option value="">Selecione...</option>
                                            <option selected>Philips Healthcare Portugal</option>
                                            <option>Dräger Portugal</option>
                                            <option>B. Braun</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de início</label>
                                        <input type="date" class="form-control" name="data_inicio_contrato" value="2022-03-15">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de fim</label>
                                        <input type="date" class="form-control" name="data_fim_contrato" value="2027-03-15">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Periodicidade</label>
                                        <select class="form-select" name="periodicidade_contrato">
                                            <option value="">Selecione...</option>
                                            <option selected>Anual</option>
                                            <option>Semestral</option>
                                            <option>Trimestral</option>
                                            <option>Mensal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <select class="form-select" name="estado_contrato">
                                            <option value="">Selecione...</option>
                                            <option selected>Ativo</option>
                                            <option>Expirado</option>
                                            <option>Cancelado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Ficheiro</label>
                                        <input type="file" class="form-control" name="ficheiro_contrato" accept=".pdf,.doc,.docx">
                                        <small class="text-muted">Ficheiro atual: contrato_eq001.pdf</small>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Observações</label>
                                        <input type="text" class="form-control" name="obs_contrato"
                                            value="Contrato de manutenção preventiva associado ao equipamento.">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-geral')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-primary"
                                            onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-docs')).show()">
                                            Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- TAB: DOCUMENTAÇÃO -->
                            <div class="tab-pane fade" id="documentacao" role="tabpanel">
                                <div class="table-responsive mb-3">
                                    <table class="table align-middle" id="tabelaDocs">
                                        <thead>
                                            <tr>
                                                <th>Tipo de documento</th>
                                                <th>Nome do documento</th>
                                                <th>Data</th>
                                                <th>Validade</th>
                                                <th>Ficheiro</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-select" name="tipo_documento_1">
                                                        <option value="">Selecione...</option>
                                                        <option selected>Manual de utilizador</option>
                                                        <option>Manual de serviço</option>
                                                        <option>Certificado de calibração</option>
                                                        <option>Contrato de manutenção</option>
                                                        <option>Fatura de aquisição</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="nome_documento_1" value="Manual MP5"></td>
                                                <td><input type="date" class="form-control" name="data_documento_1" value="2022-03-15"></td>
                                                <td><input type="date" class="form-control" name="validade_documento_1"></td>
                                                <td>
                                                    <input type="file" class="form-control form-control-sm" name="ficheiro_documento_1" accept=".pdf,.doc,.docx">
                                                    <small class="text-muted">Atual: manual_mp5.pdf</small>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <select class="form-select" name="tipo_documento_2">
                                                        <option value="">Selecione...</option>
                                                        <option>Manual de utilizador</option>
                                                        <option>Manual de serviço</option>
                                                        <option selected>Certificado de calibração</option>
                                                        <option>Contrato de manutenção</option>
                                                        <option>Fatura de aquisição</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="nome_documento_2" value="Certificado de calibração"></td>
                                                <td><input type="date" class="form-control" name="data_documento_2" value="2024-01-10"></td>
                                                <td><input type="date" class="form-control" name="validade_documento_2" value="2025-01-10"></td>
                                                <td>
                                                    <input type="file" class="form-control form-control-sm" name="ficheiro_documento_2" accept=".pdf,.doc,.docx">
                                                    <small class="text-muted">Atual: certificado.pdf</small>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <select class="form-select" name="tipo_documento_3">
                                                        <option value="">Selecione...</option>
                                                        <option>Manual de utilizador</option>
                                                        <option>Manual de serviço</option>
                                                        <option>Certificado de calibração</option>
                                                        <option selected>Contrato de manutenção</option>
                                                        <option>Fatura de aquisição</option>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="nome_documento_3" value="Contrato de manutenção"></td>
                                                <td><input type="date" class="form-control" name="data_documento_3" value="2022-03-15"></td>
                                                <td><input type="date" class="form-control" name="validade_documento_3" value="2027-03-15"></td>
                                                <td>
                                                    <input type="file" class="form-control form-control-sm" name="ficheiro_documento_3" accept=".pdf,.doc,.docx">
                                                    <small class="text-muted">Atual: contrato.pdf</small>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" class="btn btn-outline-secondary btn-sm mb-4" id="btnAddLinha">
                                    <i class="fas fa-plus me-1"></i> Adicionar linha
                                </button>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-geral')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-floppy-disk me-1"></i>Guardar alterações
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- fim tab-content -->
                    </div>
                </div>
            </form>

        </main>
    </div>
</div>

<script>
    let numLinhas = 3;
    document.getElementById('btnAddLinha').addEventListener('click', function() {
        numLinhas++;
        const n = numLinhas;
        const opcoes = `
            <option value="">Selecione...</option>
            <option>Manual de utilizador</option>
            <option>Manual de serviço</option>
            <option>Certificado de calibração</option>
            <option>Contrato de manutenção</option>
            <option>Fatura de aquisição</option>`;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><select class="form-select" name="tipo_documento_${n}">${opcoes}</select></td>
            <td><input type="text" class="form-control" name="nome_documento_${n}"></td>
            <td><input type="date" class="form-control" name="data_documento_${n}"></td>
            <td><input type="date" class="form-control" name="validade_documento_${n}"></td>
            <td><input type="file" class="form-control form-control-sm" name="ficheiro_documento_${n}" accept=".pdf,.doc,.docx"></td>
            <td>
                <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">
                    <i class="fas fa-trash"></i>
                </button>
            </td>`;
        document.querySelector('#tabelaDocs tbody').appendChild(tr);
    });



    function preencherFornecedor() {
        const id = document.getElementById('selectFornecedor').value;
        const painel = document.getElementById('infoFornecedor');
        if (!id) {
            painel.classList.add('d-none');
            return;
        }
        const f = fornecedores[id];
        document.getElementById('f-nome').textContent = f.nome;
        document.getElementById('f-nif').textContent = f.nif;
        document.getElementById('f-tipo').textContent = f.tipo;
        document.getElementById('f-morada').textContent = f.morada;
        document.getElementById('f-website').textContent = f.website;
        document.getElementById('f-telefone').textContent = f.telefone;
        document.getElementById('f-email').textContent = f.email;
        document.getElementById('f-contacto').textContent = f.contacto;
        document.getElementById('f-tel-direto').textContent = f.telDireto;
        document.getElementById('f-email-direto').textContent = f.emailDireto;
        painel.classList.remove('d-none');
    }

    
    function preencherLocalizacao() {
        const id = document.getElementById('selectLocalizacao').value;
        const painel = document.getElementById('infoLocalizacao');
        if (!id) {
            painel.classList.add('d-none');
            return;
        }
        const l = localizacoes[id];
        document.getElementById('l-edificio').textContent = l.edificio;
        document.getElementById('l-piso').textContent = l.piso;
        document.getElementById('l-servico').textContent = l.servico;
        document.getElementById('l-sala').textContent = l.sala;
        painel.classList.remove('d-none');
    }
    const fornecedores = {
        <?php foreach ($fornecedores_bd as $f): ?>
            <?= $f->id ?>: {
                nome: <?= json_encode($f->nome) ?>,
                nif: <?= json_encode($f->nif) ?>,
                tipo: <?= json_encode($f->tipo) ?>,
                morada: <?= json_encode($f->morada) ?>,
                website: <?= json_encode($f->website ?? '—') ?>,
                telefone: <?= json_encode($f->telefone) ?>,
                email: <?= json_encode($f->email) ?>,
                contacto: <?= json_encode($f->pessoa_contacto) ?>,
                telDireto: <?= json_encode($f->telefone_contacto) ?>,
                emailDireto: <?= json_encode($f->email_contacto ?? '—') ?>
            },
        <?php endforeach; ?>
    };

    const localizacoes = {
        <?php foreach ($localizacoes_bd as $l): ?>
            <?= $l->id ?>: {
                edificio: <?= json_encode($l->edificio) ?>,
                piso: <?= json_encode($l->piso) ?>,
                servico: <?= json_encode($l->servico) ?>,
                sala: <?= json_encode($l->sala) ?>
            },
        <?php endforeach; ?>
    };
</script>

<?php include '../../includes/footer.php'; ?>