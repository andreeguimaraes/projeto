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
$garantia = null;
$contrato = null;
$tipos_garantia_bd = [];
$tipos_contrato_bd = [];


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
    $entidade_garantia = trim($_POST['entidade_garantia'] ?? '');
    $estado_garantia = trim($_POST['estado_garantia'] ?? '');

    $periodicidade_contrato = trim($_POST['periodicidade_contrato'] ?? '');
    $estado_contrato = trim($_POST['estado_contrato'] ?? '');
    $obs_contrato = trim($_POST['obs_contrato'] ?? '');


    if ($estado_contrato === '') {
        $estado_contrato = 'ativo';
    }
    if ($estado_garantia === '') {
        $estado_garantia = 'ativa';
    }

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
        data_aquisicao  = :data_aquisicao,
        ano_fabrico     = :ano_fabrico,
        custo_aquisicao = :custo_aquisicao,
        tipo_entrada    = :tipo_entrada,
        estado          = :estado,
        criticidade     = :criticidade,
        localizacao_id  = :localizacao_id,
        observacoes     = :observacoes
    WHERE id = :id
");

            $data_aquisicao = $data_aquisicao !== '' ? $data_aquisicao : null;
            $ano_fabrico = $ano_fabrico !== '' ? $ano_fabrico : null;
            $custo_aquisicao = $custo_aquisicao !== '' ? $custo_aquisicao : null;
            $stmt->bindParam(':designacao',       $designacao,       PDO::PARAM_STR);
            $stmt->bindParam(':categoria_id',     $categoria_id,     PDO::PARAM_INT);
            $stmt->bindParam(':marca',            $marca,            PDO::PARAM_STR);
            $stmt->bindParam(':modelo',           $modelo,           PDO::PARAM_STR);
            $stmt->bindParam(':numero_serie',     $numero_serie,     PDO::PARAM_STR);
            $stmt->bindParam(':fabricante',       $fabricante,       PDO::PARAM_STR);
            $stmt->bindParam(':data_aquisicao',   $data_aquisicao,   PDO::PARAM_STR);
            $stmt->bindParam(':ano_fabrico',      $ano_fabrico,      PDO::PARAM_INT);
            $stmt->bindParam(':custo_aquisicao',  $custo_aquisicao,  PDO::PARAM_STR);
            $stmt->bindParam(':tipo_entrada',     $tipo_entrada,     PDO::PARAM_STR);
            $stmt->bindParam(':estado',           $estado,           PDO::PARAM_STR);
            $stmt->bindParam(':criticidade',      $criticidade,      PDO::PARAM_STR);
            $stmt->bindParam(':localizacao_id',   $localizacao_id,   PDO::PARAM_INT);
            $stmt->bindParam(':observacoes',      $observacoes,      PDO::PARAM_STR);
            $stmt->bindParam(':id',               $id,               PDO::PARAM_INT);


            $stmt->execute();

            if (!empty($fornecedor_id)) {

                $stmtAtual = $ligacao->prepare("
                    SELECT id
                    FROM equipamento_fornecedor
                    WHERE equipamento_id = :equipamento_id
                    LIMIT 1
                ");
                $stmtAtual->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
                $stmtAtual->execute();
                $fornecedorAtual = $stmtAtual->fetch(PDO::FETCH_OBJ);

                if ($fornecedorAtual) {
                    $stmtForn = $ligacao->prepare("
                        UPDATE equipamento_fornecedor
                        SET fornecedor_id = :fornecedor_id
                        WHERE id = :id
                    ");

                    $stmtForn->bindParam(':fornecedor_id', $fornecedor_id, PDO::PARAM_INT);
                    $stmtForn->bindParam(':id', $fornecedorAtual->id, PDO::PARAM_INT);
                    $stmtForn->execute();
                } else {
                    $tipo_fornecedor_id = 1;

                    $stmtForn = $ligacao->prepare("
                        INSERT INTO equipamento_fornecedor 
                            (equipamento_id, fornecedor_id, tipo_id)
                        VALUES 
                            (:equipamento_id, :fornecedor_id, :tipo_id)
                    ");

                    $stmtForn->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
                    $stmtForn->bindParam(':fornecedor_id', $fornecedor_id, PDO::PARAM_INT);
                    $stmtForn->bindParam(':tipo_id', $tipo_fornecedor_id, PDO::PARAM_INT);
                    $stmtForn->execute();
                }
            }
            if (!empty($tipo_garantia) && !empty($data_inicio_garantia) && !empty($data_fim_garantia)) {

                $stmtGarantiaAtual = $ligacao->prepare("
                    SELECT id
                    FROM garantias
                    WHERE equipamento_id = :equipamento_id
                    LIMIT 1
                ");
                $stmtGarantiaAtual->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
                $stmtGarantiaAtual->execute();
                $garantiaAtual = $stmtGarantiaAtual->fetch(PDO::FETCH_OBJ);

                if ($garantiaAtual) {
                    $stmtGarantia = $ligacao->prepare("
                        UPDATE garantias
                        SET tipo_id = :tipo_id,
                            data_inicio = :data_inicio,
                            data_fim = :data_fim,
                            entidade_responsavel = :entidade_responsavel,
                            estado = :estado
                        WHERE id = :id
                    ");

                    $stmtGarantia->bindParam(':tipo_id', $tipo_garantia, PDO::PARAM_INT);
                    $stmtGarantia->bindParam(':data_inicio', $data_inicio_garantia, PDO::PARAM_STR);
                    $stmtGarantia->bindParam(':data_fim', $data_fim_garantia, PDO::PARAM_STR);
                    $stmtGarantia->bindParam(':entidade_responsavel', $entidade_garantia, PDO::PARAM_STR);
                    $stmtGarantia->bindParam(':estado', $estado_garantia, PDO::PARAM_STR);
                    $stmtGarantia->bindParam(':id', $garantiaAtual->id, PDO::PARAM_INT);
                    $stmtGarantia->execute();
                } else {
                    $codigo_garantia = 'GAR' . str_pad($id, 5, '0', STR_PAD_LEFT);

                    $stmtGarantia = $ligacao->prepare("
                        INSERT INTO garantias
                            (codigo, equipamento_id, tipo_id, data_inicio, data_fim, entidade_responsavel, estado)
                        VALUES
                            (:codigo, :equipamento_id, :tipo_id, :data_inicio, :data_fim, :entidade_responsavel, :estado)
                    ");

                    $stmtGarantia->bindParam(':codigo', $codigo_garantia, PDO::PARAM_STR);
                    $stmtGarantia->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
                    $stmtGarantia->bindParam(':tipo_id', $tipo_garantia, PDO::PARAM_INT);
                    $stmtGarantia->bindParam(':data_inicio', $data_inicio_garantia, PDO::PARAM_STR);
                    $stmtGarantia->bindParam(':data_fim', $data_fim_garantia, PDO::PARAM_STR);
                    $stmtGarantia->bindParam(':entidade_responsavel', $entidade_garantia, PDO::PARAM_STR);
                    $stmtGarantia->bindParam(':estado', $estado_garantia, PDO::PARAM_STR);
                    $stmtGarantia->execute();
                }
            }

            if (!empty($tipo_contrato) && !empty($data_inicio_contrato) && !empty($data_fim_contrato)) {

                $stmtContratoAtual = $ligacao->prepare("
                    SELECT id
                    FROM contratos
                    WHERE equipamento_id = :equipamento_id
                    LIMIT 1
                ");
                $stmtContratoAtual->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
                $stmtContratoAtual->execute();
                $contratoAtual = $stmtContratoAtual->fetch(PDO::FETCH_OBJ);

                if ($contratoAtual) {
                    $stmtContrato = $ligacao->prepare("
                        UPDATE contratos
                        SET tipo_id = :tipo_id,
                            data_inicio = :data_inicio,
                            data_fim = :data_fim,
                            entidade_responsavel = :entidade_responsavel,
                            periodicidade = :periodicidade,
                            estado = :estado,
                            observacoes = :observacoes
                        WHERE id = :id
                    ");

                    $stmtContrato->bindParam(':tipo_id', $tipo_contrato, PDO::PARAM_INT);
                    $stmtContrato->bindParam(':data_inicio', $data_inicio_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':data_fim', $data_fim_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':entidade_responsavel', $entidade_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':periodicidade', $periodicidade_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':estado', $estado_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':observacoes', $obs_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':id', $contratoAtual->id, PDO::PARAM_INT);
                    $stmtContrato->execute();
                } else {
                    $codigo_contrato = 'CON' . str_pad($id, 5, '0', STR_PAD_LEFT);

                    $stmtContrato = $ligacao->prepare("
                        INSERT INTO contratos
                            (codigo, equipamento_id, tipo_id, data_inicio, data_fim, entidade_responsavel, periodicidade, estado, observacoes)
                        VALUES
                            (:codigo, :equipamento_id, :tipo_id, :data_inicio, :data_fim, :entidade_responsavel, :periodicidade, :estado, :observacoes)
                    ");

                    $stmtContrato->bindParam(':codigo', $codigo_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
                    $stmtContrato->bindParam(':tipo_id', $tipo_contrato, PDO::PARAM_INT);
                    $stmtContrato->bindParam(':data_inicio', $data_inicio_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':data_fim', $data_fim_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':entidade_responsavel', $entidade_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':periodicidade', $periodicidade_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':estado', $estado_contrato, PDO::PARAM_STR);
                    $stmtContrato->bindParam(':observacoes', $obs_contrato, PDO::PARAM_STR);
                    $stmtContrato->execute();
                }
            }

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

    $stmt = $ligacao->prepare("
        SELECT 
            e.*,
            ef.fornecedor_id,
            ef.tipo_id AS tipo_fornecedor_associado
        FROM equipamentos e
        LEFT JOIN equipamento_fornecedor ef 
            ON ef.equipamento_id = e.id
        WHERE e.id = :id
        LIMIT 1
    ");
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

    $stmtGarantia = $ligacao->prepare("
        SELECT *
        FROM garantias
        WHERE equipamento_id = :equipamento_id
        LIMIT 1
    ");
    $stmtGarantia->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
    $stmtGarantia->execute();
    $garantia = $stmtGarantia->fetch(PDO::FETCH_OBJ);

    $stmtContrato = $ligacao->prepare("
        SELECT *
        FROM contratos
        WHERE equipamento_id = :equipamento_id
        LIMIT 1
    ");
    $stmtContrato->bindParam(':equipamento_id', $id, PDO::PARAM_INT);
    $stmtContrato->execute();
    $contrato = $stmtContrato->fetch(PDO::FETCH_OBJ);

    $stmtTiposGarantia = $ligacao->prepare("
        SELECT *
        FROM tipos_garantia
        ORDER BY id
    ");
    $stmtTiposGarantia->execute();
    $tipos_garantia_bd = $stmtTiposGarantia->fetchAll(PDO::FETCH_OBJ);

    $stmtTiposContrato = $ligacao->prepare("
        SELECT *
        FROM tipos_contrato
        ORDER BY id
    ");
    $stmtTiposContrato->execute();
    $tipos_contrato_bd = $stmtTiposContrato->fetchAll(PDO::FETCH_OBJ);
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

            <form action="editar-equipamentos.php?id_equipamento=<?= htmlspecialchars($idEncriptado) ?>" method="post" enctype="multipart/form-data" novalidate>
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
                                        <input type="text" class="form-control" name="modelo" value="<?= htmlspecialchars($modelo ?? $eq->modelo ?? '') ?>" required>

                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Número de série <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="numero_serie"
                                            value="<?= htmlspecialchars($eq->numero_serie ?? '') ?>" required>
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
                                        <select class="form-select" name="fornecedor_id" id="selectFornecedor" onchange="preencherFornecedor()">
                                            <option value="">Selecione...</option>
                                            <?php foreach ($fornecedores_bd as $f): ?>
                                                <option value="<?= $f->id ?>"
                                                    <?= (($_POST['fornecedor_id'] ?? $eq->fornecedor_id ?? '') == $f->id) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($f->nome) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div id="infoFornecedor" class="d-none">
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
                                            <?php foreach ($tipos_garantia_bd as $tg): ?>
                                                <option value="<?= $tg->id ?>"
                                                    <?= (($_POST['tipo_garantia'] ?? $garantia->tipo_id ?? '') == $tg->id) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($tg->nome) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de início</label>
                                        <input type="date" class="form-control" name="data_inicio_garantia" value="<?= htmlspecialchars($_POST['data_inicio_garantia'] ?? $garantia->data_inicio ?? '') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de fim</label>
                                        <input type="date" class="form-control" name="data_fim_garantia" value="<?= htmlspecialchars($_POST['data_fim_garantia'] ?? $garantia->data_fim ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Entidade responsável</label>
                                        <input type="text" class="form-control" name="entidade_garantia" value="<?= htmlspecialchars($_POST['entidade_garantia'] ?? $garantia->entidade_responsavel ?? '') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <select class="form-select" name="estado_garantia">
                                            <option value="">Selecione...</option>
                                            <option value="ativa" <?= (($_POST['estado_garantia'] ?? $garantia->estado ?? '') == 'ativa') ? 'selected' : '' ?>>Ativa</option>
                                            <option value="expirada" <?= (($_POST['estado_garantia'] ?? $garantia->estado ?? '') == 'expirada') ? 'selected' : '' ?>>Expirada</option>
                                            <option value="cancelada" <?= (($_POST['estado_garantia'] ?? $garantia->estado ?? '') == 'cancelada') ? 'selected' : '' ?>>Cancelada</option>
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
                                            <?php foreach ($tipos_contrato_bd as $tc): ?>
                                                <option value="<?= $tc->id ?>"
                                                    <?= (($_POST['tipo_contrato'] ?? $contrato->tipo_id ?? '') == $tc->id) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($tc->nome) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Entidade responsável</label>
                                        <input type="text" class="form-control" name="entidade_contrato"
                                            value="<?= htmlspecialchars($_POST['entidade_contrato'] ?? $contrato->entidade_responsavel ?? '') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de início</label>
                                        <input type="date" class="form-control" name="data_inicio_contrato" value="<?= htmlspecialchars($_POST['data_inicio_contrato'] ?? $contrato->data_inicio ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de fim</label>
                                        <input type="date" class="form-control" name="data_fim_contrato" value="<?= htmlspecialchars($_POST['data_fim_contrato'] ?? $contrato->data_fim ?? '') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Periodicidade</label>
                                        <select class="form-select" name="periodicidade_contrato">
                                            <option value="">Selecione...</option>
                                            <option value="mensal" <?= (($_POST['periodicidade_contrato'] ?? $contrato->periodicidade ?? '') == 'mensal') ? 'selected' : '' ?>>Mensal</option>
                                            <option value="trimestral" <?= (($_POST['periodicidade_contrato'] ?? $contrato->periodicidade ?? '') == 'trimestral') ? 'selected' : '' ?>>Trimestral</option>
                                            <option value="semestral" <?= (($_POST['periodicidade_contrato'] ?? $contrato->periodicidade ?? '') == 'semestral') ? 'selected' : '' ?>>Semestral</option>
                                            <option value="anual" <?= (($_POST['periodicidade_contrato'] ?? $contrato->periodicidade ?? '') == 'anual') ? 'selected' : '' ?>>Anual</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <select class="form-select" name="estado_contrato">
                                            <option value="">Selecione...</option>
                                            <option value="ativo" <?= (($_POST['estado_contrato'] ?? $contrato->estado ?? 'ativo') == 'ativo') ? 'selected' : '' ?>>Ativo</option>
                                            <option value="expirado" <?= (($_POST['estado_contrato'] ?? $contrato->estado ?? '') == 'expirado') ? 'selected' : '' ?>>Expirado</option>
                                            <option value="cancelado" <?= (($_POST['estado_contrato'] ?? $contrato->estado ?? '') == 'cancelado') ? 'selected' : '' ?>>Cancelado</option>
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
                                        <input type="text" class="form-control" name="obs_contrato" value="<?= htmlspecialchars($_POST['obs_contrato'] ?? $contrato->observacoes ?? '') ?>">
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

        if (!id || !fornecedores[id]) {
            painel.classList.add('d-none');
            return;
        }

        const f = fornecedores[id];

        document.getElementById('f-nome').textContent = f.nome ?? '—';
        document.getElementById('f-nif').textContent = f.nif ?? '—';
        document.getElementById('f-tipo').textContent = f.tipo ?? '—';
        document.getElementById('f-morada').textContent = f.morada ?? '—';
        document.getElementById('f-website').textContent = f.website ?? '—';
        document.getElementById('f-telefone').textContent = f.telefone ?? '—';
        document.getElementById('f-email').textContent = f.email ?? '—';
        document.getElementById('f-contacto').textContent = f.contacto ?? '—';
        document.getElementById('f-tel-direto').textContent = f.telDireto ?? '—';
        document.getElementById('f-email-direto').textContent = f.emailDireto ?? '—';

        painel.classList.remove('d-none');
    }


    function preencherLocalizacao() {
        const id = document.getElementById('selectLocalizacao').value;
        const painel = document.getElementById('infoLocalizacao');

        if (!id || !localizacoes[id]) {
            painel.classList.add('d-none');
            return;
        }

        const l = localizacoes[id];

        document.getElementById('l-edificio').textContent = l.edificio ?? '—';
        document.getElementById('l-piso').textContent = l.piso ?? '—';
        document.getElementById('l-servico').textContent = l.servico ?? '—';
        document.getElementById('l-sala').textContent = l.sala ?? '—';

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

    document.addEventListener('DOMContentLoaded', function() {
        preencherFornecedor();
        preencherLocalizacao();
    });
</script>

<?php include '../../includes/footer.php'; ?>