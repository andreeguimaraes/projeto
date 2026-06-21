<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// --------------------------------------------------------------------
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged(); // Inicia a sessão (se necessário) e verifica se o utilizador está autenticado
redirect_if_not_allowed(['administrador', 'tecnico']);

require_once __DIR__ . '/../../includes/validacoes.php';

$localizacoes_bd = [];
$fornecedores_bd = [];
$categorias_bd   = [];
$tipos_documento_bd = [];
$erros        = [];
$erro_sistema = '';
$sucesso      = false;
$tipos_garantia_bd = [];
$tipos_contrato_bd = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $categorias_bd = $ligacao->query("SELECT id, nome FROM categorias_equipamento ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);

    $fornecedores_bd = $ligacao->query("
        SELECT f.id, f.nome, f.nif, f.morada, f.website,
            f.telefone, f.email,
            f.pessoa_contacto, f.telefone_contacto, f.email_contacto,
            tf.nome AS tipo
        FROM fornecedores f
        JOIN tipos_fornecedor tf ON tf.id = f.tipo_id
        ORDER BY f.nome
    ")->fetchAll(PDO::FETCH_OBJ);

    $localizacoes_bd = $ligacao->query("
        SELECT l.id, l.edificio, l.piso, l.sala, s.nome AS servico
        FROM localizacoes l
        JOIN servicos s ON s.id = l.servico_id
        ORDER BY s.nome, l.sala
    ")->fetchAll(PDO::FETCH_OBJ);
    $tipos_garantia_bd  = $ligacao->query("SELECT id, nome FROM tipos_garantia ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
    $tipos_contrato_bd  = $ligacao->query("SELECT id, nome FROM tipos_contrato ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
    $tipos_fornecedor_bd = $ligacao->query("SELECT id, nome FROM tipos_fornecedor ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
    $tipos_documento_bd = $ligacao->query("SELECT id, nome, tem_validade FROM tipos_documento ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
    $maxCodigo = $ligacao->query("SELECT COALESCE(MAX(CAST(SUBSTRING(codigo, 3) AS UNSIGNED)), 0) FROM equipamentos")->fetchColumn();
    $codigo_sugerido = 'EQ' . str_pad($maxCodigo + 1, 3, '0', STR_PAD_LEFT);
} catch (PDOException $e) {
    $erro_sistema = "Erro ao ligar à base de dados.";
}

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Recolher dados
    $codigo        = $_POST["codigo"] ?? "";
    $designacao    = $_POST["designacao"] ?? "";
    $categoria     = $_POST["categoria"] ?? "";
    $marca         = $_POST["marca"] ?? "";
    $modelo        = $_POST["modelo"] ?? "";
    $numero_serie     = $_POST["numero_serie"] ?? "";
    $fabricante    = $_POST["fabricante"] ?? "";
    $ano_fabrico   = $_POST["ano_fabrico"] ?? "";
    $criticidade   = $_POST["criticidade"] ?? "";
    $estado        = $_POST["estado"] ?? "";
    $data_aquisicao = $_POST["data_aquisicao"] ?? "";
    $custo_aquisicao         = $_POST["custo_aquisicao"] ?? "";
    $tipo_entrada  = $_POST["tipo_entrada"] ?? "";
    $localizacao_id = $_POST["localizacao_id"] ?? "";

    // Garantia
    $tipo_garantia       = $_POST["tipo_garantia"] ?? "";
    $data_inicio_garantia = $_POST["data_inicio"] ?? "";
    $data_fim_garantia   = $_POST["data_fim"] ?? "";
    $estado_garantia     = $_POST["estado_garantia"] ?? "";
    $entidade_garantia    = $_POST["entidade_garantia"] ?? "";

    // Contrato
    $tipo_contrato       = $_POST["tipo_contrato"] ?? "";
    $entidade_contrato   = $_POST["entidade_contrato"] ?? "";
    $data_inicio_contrato = $_POST["data_inicio_contrato"] ?? "";
    $data_fim_contrato   = $_POST["data_fim_contrato"] ?? "";
    $periodicidade_contrato = $_POST["periodicidade_contrato"] ?? "";
    $estado_contrato     = $_POST["estado_contrato"] ?? "";
    $obs_contrato        = $_POST["obs_contrato"] ?? "";

    // Documentação
    $observacoes = $_POST["observacoes"] ?? "";

    // ----------------------------------------------------------------
    // 2. TRIM
    // ----------------------------------------------------------------
    $codigo      = trim($codigo);
    $designacao  = trim($designacao);
    $marca       = trim($marca);
    $modelo      = trim($modelo);
    $numero_serie   = trim($numero_serie);
    $fabricante  = trim($fabricante);
    $observacoes = trim($observacoes);
    $entidade_garantia = trim($entidade_garantia);

    // ----------------------------------------------------------------
    // 3. VALIDAR
    // ----------------------------------------------------------------
    $erros = array_merge(
        validar_codigo($codigo),
        validar_designacao($designacao),
        validar_categoria_nome($categoria),
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
        validar_garantia($tipo_garantia, $data_inicio_garantia, $data_fim_garantia),
        validar_contrato($tipo_contrato, $data_inicio_contrato, $data_fim_contrato, $entidade_contrato)
    );

    // Validações que ficam aqui (dependem de $_FILES ou índice dinâmico)

    // Ficheiro da garantia
    if (!empty($_FILES['ficheiro_garantia']['name'])) {
        $ext = strtolower(pathinfo($_FILES['ficheiro_garantia']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['pdf', 'doc', 'docx'])) {
            $erros[] = "O ficheiro da garantia deve ser PDF, DOC ou DOCX.";
        }
        if ($_FILES['ficheiro_garantia']['size'] > 5 * 1024 * 1024) {
            $erros[] = "O ficheiro da garantia não pode exceder 5MB.";
        }
    }

    // Ficheiro do contrato
    if (!empty($_FILES['ficheiro_contrato']['name'])) {
        $ext = strtolower(pathinfo($_FILES['ficheiro_contrato']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, ['pdf', 'doc', 'docx'])) {
            $erros[] = "O ficheiro do contrato deve ser PDF, DOC ou DOCX.";
        }
        if ($_FILES['ficheiro_contrato']['size'] > 5 * 1024 * 1024) {
            $erros[] = "O ficheiro do contrato não pode exceder 5MB.";
        }
    }

    // Documentação linha a linha
    $documentos = [];
    $i = 1;
    while (isset($_POST["tipo_documento_$i"])) {
        $tipo_doc  = trim($_POST["tipo_documento_$i"] ?? "");
        $nome_doc  = trim($_POST["nome_documento_$i"] ?? "");
        $data_doc  = trim($_POST["data_documento_$i"] ?? "");
        $valid_doc = trim($_POST["validade_documento_$i"] ?? "");

        $linha_preenchida = !empty($tipo_doc) || !empty($nome_doc) || !empty($data_doc) || !empty($valid_doc);

        if ($linha_preenchida) {
            if (empty($tipo_doc))  $erros[] = "O tipo do documento na linha $i é obrigatório.";
            if (empty($nome_doc))  $erros[] = "O nome do documento na linha $i é obrigatório.";
            if (!empty($data_doc)) {
                $partes = explode('-', $data_doc);

                if (count($partes) !== 3 || !checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0])) {
                    $erros[] = "A data do documento na linha $i não é válida.";
                }
            }
            if (!empty($valid_doc)) {
                $partes = explode('-', $valid_doc);

                if (count($partes) !== 3 || !checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0])) {
                    $erros[] = "A data de validade do documento na linha $i não é válida.";
                }
            }
            if (!empty($data_doc) && !empty($valid_doc) && $valid_doc < $data_doc) {
                $erros[] = "A validade do documento na linha $i não pode ser anterior à sua data.";
            }
            if (!empty($_FILES["ficheiro_documento_$i"]['name'])) {
                $ext = strtolower(pathinfo($_FILES["ficheiro_documento_$i"]['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, ['pdf', 'doc', 'docx'])) {
                    $erros[] = "O ficheiro do documento na linha $i deve ser PDF, DOC ou DOCX.";
                }
                if ($_FILES["ficheiro_documento_$i"]['size'] > 5 * 1024 * 1024) {
                    $erros[] = "O ficheiro do documento na linha $i não pode exceder 5MB.";
                }
            }
            $documentos[] = [
                'tipo'     => $tipo_doc,
                'nome'     => $nome_doc,
                'data'     => !empty($data_doc) ? $data_doc : null,
                'validade' => !empty($valid_doc) ? $valid_doc : null,
            ];
        }
        $i++;
    }
    // Fornecedores linha a linha (múltiplos, com tipo de relação)
    $fornecedores_associar = [];
    $tipos_usados = [];
    $i = 1;
    while (isset($_POST["fornecedor_id_$i"])) {
        $fornecedor_id_linha = trim($_POST["fornecedor_id_$i"] ?? "");
        $tipo_relacao = trim($_POST["tipo_relacao_$i"] ?? "");

        $linha_preenchida = !empty($fornecedor_id_linha) || !empty($tipo_relacao);

        if ($linha_preenchida) {
            if (empty($fornecedor_id_linha)) {
                $erros[] = "O fornecedor na linha $i é obrigatório.";
            } elseif (!filter_var($fornecedor_id_linha, FILTER_VALIDATE_INT)) {
                $erros[] = "O fornecedor selecionado na linha $i não é válido.";
            }

            if (empty($tipo_relacao)) {
                $erros[] = "O tipo de relação na linha $i é obrigatório.";
            } elseif (!filter_var($tipo_relacao, FILTER_VALIDATE_INT)) {
                $erros[] = "O tipo de relação selecionado na linha $i não é válido.";
            } elseif (in_array($tipo_relacao, $tipos_usados)) {
                $erros[] = "Já selecionou este tipo de relação noutra linha — cada tipo só pode ter um fornecedor.";
            } else {
                $tipos_usados[] = $tipo_relacao;
            }

            if (!empty($fornecedor_id_linha) && !empty($tipo_relacao) && filter_var($fornecedor_id_linha, FILTER_VALIDATE_INT) && filter_var($tipo_relacao, FILTER_VALIDATE_INT)) {
                $fornecedores_associar[] = [
                    'fornecedor_id' => (int)$fornecedor_id_linha,
                    'tipo_id' => (int)$tipo_relacao,
                ];
            }
        }
        $i++;
    }

    // Verificar código duplicado
    if (empty($erros)) {
        $stmtCod = $ligacao->prepare("SELECT id FROM equipamentos WHERE codigo = :codigo");
        $stmtCod->execute([':codigo' => strtoupper($codigo)]);
        if ($stmtCod->fetch()) {
            $erros[] = "Já existe um equipamento com este código.";
        }
    }
    // ----------------------------------------------------------------
    // 4. NORMALIZAR E GRAVAR (só se não houver erros)
    // Normalizar entrada. independentemente de como o utilizador escreve os dados, o sistema assegura consistência e padronização antes de qualquer registo na base de dados. 
    // ----------------------------------------------------------------
    $designacao  = ucwords(strtolower($designacao));
    $marca       = ucwords(strtolower($marca));
    $numero_serie   = strtoupper($numero_serie);
    $fabricante  = !empty($fabricante) ? ucwords(strtolower($fabricante)) : null;
    $ano_fabrico    = !empty($ano_fabrico)    ? (int)$ano_fabrico   : null;
    $custo_aquisicao          = !empty($custo_aquisicao)          ? (float)$custo_aquisicao        : null;
    $data_aquisicao = !empty($data_aquisicao) ? $data_aquisicao     : null;
    $observacoes    = !empty($observacoes)    ? $observacoes         : null;

    $mapa_estado = [
        'ativo'          => 'ativo',
        'em manutenção'  => 'em_manutencao',
        'inativo'        => 'inativo',
        'em calibração'  => 'em_calibracao',
        'em quarentena'  => 'em_quarentena',
        'abatido'        => 'abatido',
    ];
    $mapa_criticidade = [
        'baixa'           => 'baixa',
        'média'           => 'media',
        'alta'            => 'alta',
        'suporte de vida' => 'suporte_de_vida',
    ];
    $mapa_tipo_entrada = [
        'compra'     => 'compra',
        'doação'     => 'doacao',
        'aluguer'    => 'aluguer',
        'empréstimo' => 'emprestimo',
    ];

    $estado_bd       = $mapa_estado[strtolower($estado)]           ?? 'ativo';
    $criticidade_bd  = $mapa_criticidade[strtolower($criticidade)] ?? 'baixa';
    $tipo_entrada_bd = !empty($tipo_entrada)
        ? ($mapa_tipo_entrada[strtolower($tipo_entrada)] ?? 'compra')
        : 'compra';


    // 3. Se não houver erros, guardar na base de dados
    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // 1. UPLOADS
            $pasta_base = $_SERVER['DOCUMENT_ROOT'] . '/MEDINV/uploads/';

            $path_garantia = null;
            if (!empty($_FILES['ficheiro_garantia']['name'])) {
                $ext     = strtolower(pathinfo($_FILES['ficheiro_garantia']['name'], PATHINFO_EXTENSION));
                $nome    = 'GAR_' . time() . '.' . $ext;
                $destino = $pasta_base . 'garantias/' . $nome;
                if (move_uploaded_file($_FILES['ficheiro_garantia']['tmp_name'], $destino)) {
                    $path_garantia = '/MEDINV/uploads/garantias/' . $nome;
                }
            }

            $path_contrato = null;
            if (!empty($_FILES['ficheiro_contrato']['name'])) {
                $ext     = strtolower(pathinfo($_FILES['ficheiro_contrato']['name'], PATHINFO_EXTENSION));
                $nome    = 'CON_' . time() . '.' . $ext;
                $destino = $pasta_base . 'contratos/' . $nome;
                if (move_uploaded_file($_FILES['ficheiro_contrato']['tmp_name'], $destino)) {
                    $path_contrato = '/MEDINV/uploads/contratos/' . $nome;
                }
            }

            // Upload docs — paths guardados no array $documentos
            foreach ($documentos as $idx => $doc) {
                $j = $idx + 1;
                $path_doc = null;
                if (!empty($_FILES["ficheiro_documento_$j"]['name'])) {
                    $ext     = strtolower(pathinfo($_FILES["ficheiro_documento_$j"]['name'], PATHINFO_EXTENSION));
                    $nome    = 'DOC_' . $j . '_' . time() . '.' . $ext;
                    $destino = $pasta_base . 'documentos/' . $nome;
                    if (move_uploaded_file($_FILES["ficheiro_documento_$j"]['tmp_name'], $destino)) {
                        $path_doc = '/MEDINV/uploads/documentos/' . $nome;
                    }
                }
                $documentos[$idx]['ficheiro_path'] = $path_doc;
            }
            // Obter categoria_id pelo nome
            $stmtCat = $ligacao->prepare("SELECT id FROM categorias_equipamento WHERE nome = :nome");
            $stmtCat->execute([':nome' => $categoria]);
            $categoria_id = $stmtCat->fetchColumn();

            // Gerar código automático
            $maxCodigo    = $ligacao->query("SELECT COALESCE(MAX(CAST(SUBSTRING(codigo, 3) AS UNSIGNED)), 0) FROM equipamentos")->fetchColumn();
            $codigo_final = 'EQ' . str_pad($maxCodigo + 1, 3, '0', STR_PAD_LEFT);


            // INSERT equipamento
            $stmt = $ligacao->prepare("
            INSERT INTO equipamentos (
                codigo, designacao, categoria_id, marca, modelo,
                numero_serie, fabricante, ano_fabrico, data_aquisicao,
                custo_aquisicao, tipo_entrada, estado, criticidade,
                localizacao_id, observacoes
            ) VALUES (
                :codigo, :designacao, :categoria_id, :marca, :modelo,
                :numero_serie, :fabricante, :ano_fabrico, :data_aquisicao,
                :custo_aquisicao, :tipo_entrada, :estado, :criticidade,
                :localizacao_id, :observacoes
            )
        ");
            $stmt->execute([
                ':codigo'          => $codigo_final,
                ':designacao'      => $designacao,
                ':categoria_id'    => $categoria_id,
                ':marca'           => $marca,
                ':modelo'          => $modelo,
                ':numero_serie'    => $numero_serie,
                ':fabricante'      => $fabricante,
                ':ano_fabrico'     => $ano_fabrico,
                ':data_aquisicao'  => $data_aquisicao,
                ':custo_aquisicao' => $custo_aquisicao,
                ':tipo_entrada'    => $tipo_entrada_bd,
                ':estado'          => $estado_bd,
                ':criticidade'     => $criticidade_bd,
                ':localizacao_id'  => (int)$localizacao_id,
                ':observacoes'     => $observacoes,
            ]);

            $equipamento_id = $ligacao->lastInsertId();

            // INSERT garantia
            $tem_garantia = !empty($tipo_garantia) || !empty($data_inicio_garantia) || !empty($data_fim_garantia);
            if ($tem_garantia && !empty($tipo_garantia)) {
                $stmtTG = $ligacao->prepare("SELECT id FROM tipos_garantia WHERE nome = :nome");
                $stmtTG->execute([':nome' => $tipo_garantia]);
                $tipo_garantia_id = $stmtTG->fetchColumn();

                if ($tipo_garantia_id) {
                    $maxGar     = $ligacao->query("SELECT COALESCE(MAX(CAST(SUBSTRING(codigo, 4) AS UNSIGNED)), 0) FROM garantias")->fetchColumn();
                    $codigo_gar = 'GAR' . str_pad($maxGar + 1, 3, '0', STR_PAD_LEFT);

                    $ligacao->prepare("
                    INSERT INTO garantias (codigo, equipamento_id, tipo_id, data_inicio, data_fim, entidade_responsavel, estado, ficheiro_path)
                    VALUES (:codigo, :equipamento_id, :tipo_id, :data_inicio, :data_fim, :entidade_responsavel, :estado, :ficheiro_path)
                ")->execute([
                        ':codigo'               => $codigo_gar,
                        ':equipamento_id'       => $equipamento_id,
                        ':tipo_id'              => $tipo_garantia_id,
                        ':data_inicio'          => $data_inicio_garantia ?: null,
                        ':data_fim'             => $data_fim_garantia    ?: null,
                        ':entidade_responsavel' => !empty($_POST['entidade_garantia']) ? trim($_POST['entidade_garantia']) : null,
                        ':estado'               => !empty($estado_garantia) ? strtolower($estado_garantia) : 'ativa',
                        ':ficheiro_path'        => $path_garantia,
                    ]);
                }
            }

            // INSERT contrato
            $tem_contrato = !empty($tipo_contrato) || !empty($data_inicio_contrato) || !empty($data_fim_contrato) || !empty($entidade_contrato);
            if ($tem_contrato && !empty($tipo_contrato)) {
                $stmtTC = $ligacao->prepare("SELECT id FROM tipos_contrato WHERE nome = :nome");
                $stmtTC->execute([':nome' => $tipo_contrato]);
                $tipo_contrato_id = $stmtTC->fetchColumn();

                if ($tipo_contrato_id) {
                    $maxCon     = $ligacao->query("SELECT COALESCE(MAX(CAST(SUBSTRING(codigo, 4) AS UNSIGNED)), 0) FROM contratos")->fetchColumn();
                    $codigo_con = 'CON' . str_pad($maxCon + 1, 3, '0', STR_PAD_LEFT);

                    $ligacao->prepare("
                    INSERT INTO contratos (codigo, equipamento_id, tipo_id, data_inicio, data_fim, entidade_responsavel, periodicidade, estado, ficheiro_path)
                    VALUES (:codigo, :equipamento_id, :tipo_id, :data_inicio, :data_fim, :entidade_responsavel, :periodicidade, :estado, :ficheiro_path)
                ")->execute([
                        ':codigo'               => $codigo_con,
                        ':equipamento_id'       => $equipamento_id,
                        ':tipo_id'              => $tipo_contrato_id,
                        ':data_inicio'          => $data_inicio_contrato ?: null,
                        ':data_fim'             => $data_fim_contrato    ?: null,
                        ':entidade_responsavel' => $entidade_contrato    ?: null,
                        ':periodicidade'        => !empty($periodicidade_contrato) ? strtolower($periodicidade_contrato) : null,
                        ':estado'               => !empty($estado_contrato) ? strtolower($estado_contrato) : 'ativo',
                        ':ficheiro_path'        => $path_contrato
                    ]);
                }
            }

            foreach ($documentos as $doc) {
                $maxDoc     = $ligacao->query("SELECT COALESCE(MAX(CAST(SUBSTRING(codigo, 4) AS UNSIGNED)), 0) FROM documentos")->fetchColumn();
                $codigo_doc = 'DOC' . str_pad($maxDoc + 1, 3, '0', STR_PAD_LEFT);

                $stmtTD = $ligacao->prepare("SELECT id FROM tipos_documento WHERE nome = :nome");
                $stmtTD->execute([':nome' => $doc['tipo']]);
                $tipo_doc_id = $stmtTD->fetchColumn() ?: null;
                $ligacao->prepare("
                    INSERT INTO documentos (codigo, equipamento_id, tipo_id, nome, data_documento, data_validade, ficheiro_path)
                    VALUES (:codigo, :equipamento_id, :tipo_id, :nome, :data_documento, :data_validade, :ficheiro_path)
                ")->execute([
                    ':codigo'         => $codigo_doc,
                    ':equipamento_id' => $equipamento_id,
                    ':tipo_id'        => $tipo_doc_id,
                    ':nome'           => $doc['nome'],
                    ':data_documento' => $doc['data'],
                    ':data_validade'  => $doc['validade'],
                    ':ficheiro_path'  => $doc['ficheiro_path'],
                ]);
            }

            // INSERT equipamento_fornecedor
            // INSERT equipamento_fornecedor (múltiplos, com tipo de relação)
            foreach ($fornecedores_associar as $fa) {
                $ligacao->prepare("
        INSERT INTO equipamento_fornecedor (equipamento_id, fornecedor_id, tipo_id)
        VALUES (:equipamento_id, :fornecedor_id, :tipo_id)
    ")->execute([
                    ':equipamento_id' => $equipamento_id,
                    ':fornecedor_id'  => $fa['fornecedor_id'],
                    ':tipo_id'        => $fa['tipo_id'],
                ]);
            }

            $ligacao = null;
            header("Location: equipamentos.php");
            exit;
        } catch (PDOException $err) {
            $erro_sistema = "Erro ao gravar os dados: " . $err->getMessage();
        }

        $ligacao = null;
    }
}
?>
<?php
require_once '../../includes/header.php'; ?>
<!-- Navbar -->
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <!-- Offcanvas Sidebar -->
        <?php include '../../includes/sidebar.php'; ?>

        <main class="col-12 p-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Novo equipamento</h2>
                    <p class="text-muted mb-0">Preencha os campos para registar um novo dispositivo médico</p>
                </div>
                <a href="equipamentos.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <!-- Erro de sistema -->
            <?php if (!empty($erro_sistema)) : ?>
                <div class="alert alert-danger">
                    <i class="fas fa-triangle-exclamation me-2"></i><?= htmlspecialchars($erro_sistema) ?>
                </div>
            <?php endif; ?>

            <!-- Erros de validação -->
            <?php if (!empty($erros)) : ?>
                <div class="alert alert-danger">
                    <strong><i class="fas fa-triangle-exclamation me-2"></i>Por favor corrija os seguintes erros:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach ($erros as $erro) : ?>
                            <li><?= htmlspecialchars($erro) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <!-- Sucesso -->
            <?php if (!empty($sucesso)) : ?>
                <div class="alert alert-success">
                    <i class="fas fa-circle-check me-2"></i>Equipamento registado com sucesso!
                </div>
            <?php endif; ?>
            <form action="#" method="post" id="formEquipamento" enctype="multipart/form-data">
                <div class="card shadow rounded">
                    <div class="card-body">

                        <ul class="nav nav-underline border-bottom mb-4" id="equipTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="tab-geral" data-bs-toggle="tab" href="#geral"
                                    role="tab">
                                    <i class="fas fa-info-circle me-1"></i>Informação geral
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link disabled pe-none" id="tab-localizacao" data-bs-toggle="tab"
                                    href="#localizacao" role="tab">
                                    <i class="fas fa-location-dot me-1"></i>Localização
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link disabled pe-none" id="tab-fornecedor" data-bs-toggle="tab"
                                    href="#fornecedor" role="tab">
                                    <i class="fas fa-truck-medical me-1"></i>Fornecedor
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link disabled pe-none" id="tab-garantia" data-bs-toggle="tab"
                                    href="#garantia" role="tab">
                                    <i class="fas fa-shield-halved me-1"></i>Garantia
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link disabled pe-none" id="tab-contrato" data-bs-toggle="tab"
                                    href="#contrato" role="tab">
                                    <i class="fas fa-file-signature me-1"></i>Contrato
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link disabled pe-none" id="tab-docs" data-bs-toggle="tab"
                                    href="#documentacao" role="tab">
                                    <i class="fas fa-folder-open me-1"></i>Documentação
                                </a>
                            </li>
                        </ul>

                        <div class="tab-content" id="equipTabsContent">

                            <!-- TAB: INFORMAÇÃO GERAL -->
                            <div class="tab-pane fade show active" id="geral" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Código interno <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="codigo"
                                            value="<?= htmlspecialchars($codigo_sugerido ?? '') ?>" readonly>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Designação <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="designacao"
                                            placeholder="Ex: Monitor multiparamétrico" required
                                            value="<?= htmlspecialchars($_POST['designacao'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Categoria <span class="text-danger">*</span></label>
                                        <select class="form-select" name="categoria" required>
                                            <option value="">Selecione...</option>
                                            <?php foreach ($categorias_bd as $cat) : ?>
                                                <option value="<?= htmlspecialchars($cat->nome) ?>"
                                                    <?= (($_POST['categoria'] ?? '') == $cat->nome) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cat->nome) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Marca <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="marca"
                                            placeholder="Ex: Philips" required
                                            value="<?= htmlspecialchars($_POST['marca'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Modelo <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="modelo"
                                            placeholder="Ex: IntelliVue MP5" required
                                            value="<?= htmlspecialchars($_POST['modelo'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Número de série <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="numero_serie"
                                            placeholder="Ex: MP5-2022-45873" required
                                            value="<?= htmlspecialchars($_POST['numero_serie'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Fabricante</label>
                                        <input type="text" class="form-control" name="fabricante"
                                            placeholder="Ex: Philips Healthcare"
                                            value="<?= htmlspecialchars($_POST['fabricante'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Ano de fabrico</label>
                                        <input type="number" class="form-control" name="ano_fabrico"
                                            placeholder="Ex: 2022" min="1900" max="2026"
                                            value="<?= htmlspecialchars($_POST['ano_fabrico'] ?? '') ?>">

                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Criticidade <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="criticidade" required>
                                            <option value="">Selecione...</option>
                                            <option <?= ($_POST['criticidade'] ?? '') == 'Baixa' ? 'selected' : '' ?>>Baixa</option>
                                            <option <?= ($_POST['criticidade'] ?? '') == 'Média' ? 'selected' : '' ?>>Média</option>
                                            <option <?= ($_POST['criticidade'] ?? '') == 'Alta' ? 'selected' : '' ?>>Alta</option>
                                            <option <?= ($_POST['criticidade'] ?? '') == 'Suporte de vida' ? 'selected' : '' ?>>Suporte de vida</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Estado atual <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="estado" required>
                                            <option value="">Selecione...</option>
                                            <option <?= ($_POST['estado'] ?? '') == 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                                            <option <?= ($_POST['estado'] ?? '') == 'Em manutenção' ? 'selected' : '' ?>>Em manutenção</option>
                                            <option <?= ($_POST['estado'] ?? '') == 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                                            <option <?= ($_POST['estado'] ?? '') == 'Em calibração' ? 'selected' : '' ?>>Em calibração</option>
                                            <option <?= ($_POST['estado'] ?? '') == 'Em quarentena' ? 'selected' : '' ?>>Em quarentena</option>
                                            <option <?= ($_POST['estado'] ?? '') == 'Abatido' ? 'selected' : '' ?>>Abatido</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <!-- Aquisição -->
                                <h6 class="text-muted mb-3"><i class="fas fa-shopping-cart me-2"></i>Aquisição</h6>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Data de aquisição</label>
                                        <input type="date" class="form-control" name="data_aquisicao"
                                            value="<?= htmlspecialchars($_POST['data_aquisicao'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Custo de aquisição (€)</label>
                                        <input type="number" class="form-control" name="custo_aquisicao"
                                            placeholder="Ex: 12500" step="0.01" min="0"
                                            value="<?= htmlspecialchars($_POST['custo_aquisicao'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Tipo de entrada</label>
                                        <select class="form-select" name="tipo_entrada">
                                            <option value="">Selecione...</option>
                                            <option <?= ($_POST['tipo_entrada'] ?? '') == 'Compra' ? 'selected' : '' ?>>Compra</option>
                                            <option <?= ($_POST['tipo_entrada'] ?? '') == 'Doação' ? 'selected' : '' ?>>Doação</option>
                                            <option <?= ($_POST['tipo_entrada'] ?? '') == 'Aluguer' ? 'selected' : '' ?>>Aluguer</option>
                                            <option <?= ($_POST['tipo_entrada'] ?? '') == 'Empréstimo' ? 'selected' : '' ?>>Empréstimo</option>
                                        </select>
                                    </div>
                                </div>

                                <p class="text-muted small"><span class="text-danger">*</span> Campos obrigatórios
                                </p>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary" id="btn-next-geral">
                                        Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- TAB: LOCALIZAÇÃO -->
                            <div class="tab-pane fade" id="localizacao" role="tabpanel">

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Selecionar localização</label>
                                        <select class="form-select" name="localizacao_id" id="selectLocalizacao"
                                            onchange="preencherLocalizacao()">
                                            <option value="">Selecione...</option>
                                            <?php foreach ($localizacoes_bd as $loc) : ?>
                                                <option value="<?= $loc->id ?>"
                                                    <?= (isset($localizacao_id) && $localizacao_id == $loc->id) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($loc->servico) ?> — Sala <?= htmlspecialchars($loc->sala) ?> — Piso <?= htmlspecialchars($loc->piso) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Painel de informação — só aparece quando uma localização é selecionada -->
                                <div id="infoLocalizacao" class="d-none">
                                    <hr>
                                    <h6 class="text-muted mb-3"><i class="fas fa-location-dot me-2"></i>Informação da localização</h6>

                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Edifício</label>
                                            <p class="form-control-plaintext" id="l-edificio">—</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Piso</label>
                                            <p class="form-control-plaintext" id="l-piso">—</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Serviço</label>
                                            <p class="form-control-plaintext" id="l-servico">—</p>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Sala</label>
                                            <p class="form-control-plaintext" id="l-sala">—</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-geral')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <button type="button" class="btn btn-primary" id="btn-next-localizacao">
                                        Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- TAB: FORNECEDOR -->
                            <div class="tab-pane fade" id="fornecedor" role="tabpanel">

                                <label class="form-label fw-bold">Selecionar fornecedor</label>
                                <div class="table-responsive mb-3">
                                    <table class="table align-middle" id="tabelaFornecedores">
                                        <thead>
                                            <tr>
                                                <th>Fornecedor</th>
                                                <th>Tipo de relação</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-select" name="fornecedor_id_1">
                                                        <option value="">Selecione...</option>
                                                        <?php foreach ($fornecedores_bd as $forn) : ?>
                                                            <option value="<?= $forn->id ?>"><?= htmlspecialchars($forn->nome) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select class="form-select" name="tipo_relacao_1">
                                                        <option value="">Selecione...</option>
                                                        <?php foreach ($tipos_fornecedor_bd as $tf) : ?>
                                                            <option value="<?= $tf->id ?>"><?= htmlspecialchars($tf->nome) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
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

                                <button type="button" class="btn btn-outline-secondary btn-sm mb-4" id="btnAddFornecedor">
                                    <i class="fas fa-plus me-1"></i> Adicionar fornecedor
                                </button>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-localizacao')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <button type="button" class="btn btn-primary" id="btn-next-fornecedor">
                                        Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- TAB: GARANTIAS -->
                            <!-- Garantias não tem campos obrigatórios, botão Seguinte sempre ativo -->
                            <div class="tab-pane fade" id="garantia" role="tabpanel">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Código</label>
                                        <input type="text" class="form-control" value="GAR001" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Tipo</label>
                                        <select class="form-select" name="tipo_garantia">
                                            <option value="">Selecione...</option>
                                            <?php foreach ($tipos_garantia_bd as $tg) : ?>
                                                <option value="<?= htmlspecialchars($tg->nome) ?>"
                                                    <?= (($_POST['tipo_garantia'] ?? '') == $tg->nome) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($tg->nome) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de início</label>
                                        <input type="date" class="form-control" name="data_inicio"
                                            value="<?= htmlspecialchars($_POST['data_inicio'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de fim</label>
                                        <input type="date" class="form-control" name="data_fim"
                                            value="<?= htmlspecialchars($_POST['data_fim'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Entidade responsável</label>
                                        <input type="text" class="form-control" name="entidade_garantia"
                                            placeholder="Ex: Philips Healthcare Portugal"
                                            value="<?= htmlspecialchars($_POST['entidade_garantia'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <select class="form-select" name="estado_garantia">
                                            <option value="ativa" <?= ($_POST['estado_garantia'] ?? '') == 'ativa' ? 'selected' : '' ?>>Ativa</option>
                                            <option value="expirada" <?= ($_POST['estado_garantia'] ?? '') == 'expirada' ? 'selected' : '' ?>>Expirada</option>
                                            <option value="cancelada" <?= ($_POST['estado_garantia'] ?? '') == 'cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Ficheiro</label>
                                        <input type="file" class="form-control" name="ficheiro_garantia" accept=".pdf,.doc,.docx">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-fornecedor')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <button type="button" class="btn btn-primary" id="btn-next-garantia">
                                        Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
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
                                            <?php foreach ($tipos_contrato_bd as $tc) : ?>
                                                <option value="<?= htmlspecialchars($tc->nome) ?>"
                                                    <?= (($_POST['tipo_contrato'] ?? '') == $tc->nome) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($tc->nome) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Entidade responsável</label>
                                        <input type="text" class="form-control" name="entidade_contrato"
                                            placeholder="Ex: Philips Healthcare Portugal"
                                            value="<?= htmlspecialchars($_POST['entidade_contrato'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de início</label>
                                        <input type="date" class="form-control" name="data_inicio_contrato"
                                            value="<?= htmlspecialchars($_POST['data_inicio_contrato'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de fim</label>
                                        <input type="date" class="form-control" name="data_fim_contrato"
                                            value="<?= htmlspecialchars($_POST['data_fim_contrato'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Periodicidade</label>
                                        <select class="form-select" name="periodicidade_contrato">
                                            <option value="">Selecione...</option>
                                            <option <?= ($_POST['periodicidade_contrato'] ?? '') == 'Anual' ? 'selected' : '' ?>>Anual</option>
                                            <option <?= ($_POST['periodicidade_contrato'] ?? '') == 'Semestral' ? 'selected' : '' ?>>Semestral</option>
                                            <option <?= ($_POST['periodicidade_contrato'] ?? '') == 'Trimestral' ? 'selected' : '' ?>>Trimestral</option>
                                            <option <?= ($_POST['periodicidade_contrato'] ?? '') == 'Mensal' ? 'selected' : '' ?>>Mensal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <select class="form-select" name="estado_contrato">
                                            <option value="">Selecione...</option>
                                            <option <?= ($_POST['estado_contrato'] ?? '') == 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                                            <option <?= ($_POST['estado_contrato'] ?? '') == 'Expirado' ? 'selected' : '' ?>>Expirado</option>
                                            <option <?= ($_POST['estado_contrato'] ?? '') == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Ficheiro</label>
                                        <input type="file" class="form-control" name="ficheiro_contrato" accept=".pdf,.doc,.docx">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label fw-bold">Observações</label>
                                        <textarea class="form-control" name="observacoes" rows="4"
                                            placeholder="Informações adicionais sobre o equipamento..."><?= htmlspecialchars($_POST['observacoes'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-garantia')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <button type="button" class="btn btn-primary" id="btn-next-contrato">
                                        Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
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
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <select class="form-select tipo-documento" name="tipo_documento_1" data-linha="1">
                                                        <option value="">Selecione...</option>
                                                        <?php foreach ($tipos_documento_bd as $td) : ?>
                                                            <option value="<?= htmlspecialchars($td->nome) ?>"
                                                                data-tem-validade="<?= (int)$td->tem_validade ?>">
                                                                <?= htmlspecialchars($td->nome) ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td><input type="text" class="form-control" name="nome_documento_1"
                                                        placeholder="Manual MP5"></td>
                                                <td><input type="date" class="form-control" name="data_documento_1">
                                                </td>
                                                <td><input type="date" class="form-control"
                                                        name="validade_documento_1"></td>
                                                <td>
                                                    <input type="file" class="form-control form-control-sm"
                                                        name="ficheiro_documento_1" accept=".pdf,.doc,.docx">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <button type="button" class="btn btn-outline-secondary btn-sm mb-4"
                                    id="btnAddLinha">
                                    <i class="fas fa-plus me-1"></i> Adicionar linha
                                </button>


                                <p class="text-muted small mb-3"><span class="text-danger">*</span> Campos
                                    obrigatórios</p>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-contrato')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-floppy-disk me-1"></i>Registar equipamento
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </form>

        </main>
    </div>
</div>
<script>
    // ----------------------------------------------------------------
    // VALIDAÇÃO SEPARADOR A SEPARADOR
    // ----------------------------------------------------------------

    function mostrarErros(idConteiner, erros) {
        // Remove erros anteriores
        const anterior = document.getElementById('erros-' + idConteiner);
        if (anterior) anterior.remove();

        if (erros.length > 0) {
            const div = document.createElement('div');
            div.id = 'erros-' + idConteiner;
            div.className = 'alert alert-danger mt-3';
            div.innerHTML = '<strong>Por favor corrija os seguintes erros:</strong><ul class="mb-0 mt-2">' +
                erros.map(e => `<li>${e}</li>`).join('') +
                '</ul>';
            document.getElementById(idConteiner).appendChild(div);
            return false;
        }
        return true;
    }

    // --- TAB: INFORMAÇÃO GERAL ---

    document.getElementById('btn-next-geral').addEventListener('click', function() {
        const erros = validarGeral();
        if (mostrarErros('geral', erros)) {
            bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-localizacao')).show();
        }
    });

    // --- TAB: LOCALIZAÇÃO ---
    function validarLocalizacao() {
        const erros = [];
        const localizacao = document.querySelector('[name="localizacao_id"]').value;
        if (!localizacao) erros.push("A localização é obrigatória.");
        return erros;
    }

    document.getElementById('btn-next-localizacao').addEventListener('click', function() {
        const erros = validarLocalizacao();
        if (mostrarErros('localizacao', erros)) {
            bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-fornecedor')).show();
        }
    });

    // --- TAB: FORNECEDOR ---
    // Opcional — apenas avança sem validação obrigatória
    document.getElementById('btn-next-fornecedor').addEventListener('click', function() {
        mostrarErros('fornecedor', []);
        bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-garantia')).show();
    });

    // --- TAB: GARANTIA ---
    function validarGarantia() {
        const erros = [];

        const tipo = document.querySelector('[name="tipo_garantia"]').value;
        const inicio = document.querySelector('[name="data_inicio"]').value;
        const fim = document.querySelector('[name="data_fim"]').value;

        const temGarantia = tipo || inicio || fim;

        if (temGarantia) {
            if (!tipo) erros.push("O tipo de garantia é obrigatório quando a garantia está preenchida.");
            if (!inicio) {
                erros.push("A data de início da garantia é obrigatória.");
            }
            if (!fim) {
                erros.push("A data de fim da garantia é obrigatória.");
            }
            if (inicio && fim && fim <= inicio) {
                erros.push("A data de fim da garantia deve ser posterior à data de início.");
            }
        }

        return erros;
    }

    document.getElementById('btn-next-garantia').addEventListener('click', function() {
        const erros = validarGarantia();
        if (mostrarErros('garantia', erros)) {
            bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-contrato')).show();
        }
    });

    // --- TAB: CONTRATO ---
    function validarContrato() {
        const erros = [];

        const tipo = document.querySelector('[name="tipo_contrato"]').value;
        const inicio = document.querySelector('[name="data_inicio_contrato"]').value;
        const fim = document.querySelector('[name="data_fim_contrato"]').value;
        const entidade = document.querySelector('[name="entidade_contrato"]').value;

        const temContrato = tipo || inicio || fim || entidade;

        if (temContrato) {
            if (!tipo) erros.push("O tipo de contrato é obrigatório quando o contrato está preenchido.");
            if (!inicio) erros.push("A data de início do contrato é obrigatória.");
            if (!fim) erros.push("A data de fim do contrato é obrigatória.");
            if (inicio && fim && fim <= inicio) {
                erros.push("A data de fim do contrato deve ser posterior à data de início.");
            }
        }

        return erros;
    }

    document.getElementById('btn-next-contrato').addEventListener('click', function() {
        const erros = validarContrato();
        if (mostrarErros('contrato', erros)) {
            bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-docs')).show();
        }
    });

    // ----------------------------------------------------------------
    // ADICIONAR LINHAS NA TABELA DE DOCUMENTAÇÃO
    // ----------------------------------------------------------------
    const opcoesTiposDocumento = `
    <option value="">Selecione...</option>
    <?php foreach ($tipos_documento_bd as $td) : ?>
        <option value="<?= htmlspecialchars($td->nome) ?>" data-tem-validade="<?= (int)$td->tem_validade ?>">
            <?= htmlspecialchars($td->nome) ?>
        </option>
    <?php endforeach; ?>
`;

    let numLinhas = 1;

    document.getElementById('btnAddLinha').addEventListener('click', function() {
        numLinhas++;
        const n = numLinhas;
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><select class="form-select tipo-documento" name="tipo_documento_${n}" data-linha="${n}">${opcoesTiposDocumento}</select></td>
            <td><input type="text" class="form-control" name="nome_documento_${n}"></td>
            <td><input type="date" class="form-control" name="data_documento_${n}"></td>
            <td><input type="date" class="form-control" name="validade_documento_${n}"></td>
            <td><input type="file" class="form-control form-control-sm" name="ficheiro_documento_${n}" accept=".pdf,.doc,.docx"></td>`;
        document.querySelector('#tabelaDocs tbody').appendChild(tr);
        tr.querySelector('.tipo-documento').dispatchEvent(new Event('change'));
    });

    // ----------------------------------------------------------------
    // DADOS DOS FORNECEDORES (carregados da BD via PHP)
    // ----------------------------------------------------------------

    let numFornecedores = 1;
    const opcoesFornecedor = `
    <option value="">Selecione...</option>
    <?php foreach ($fornecedores_bd as $forn) : ?>
        <option value="<?= $forn->id ?>"><?= htmlspecialchars($forn->nome) ?></option>
    <?php endforeach; ?>
`;
    const opcoesTipoRelacao = `
    <option value="">Selecione...</option>
    <?php foreach ($tipos_fornecedor_bd as $tf) : ?>
        <option value="<?= $tf->id ?>"><?= htmlspecialchars($tf->nome) ?></option>
    <?php endforeach; ?>
`;
    document.getElementById('btnAddFornecedor').addEventListener('click', function() {
        numFornecedores++;
        const n = numFornecedores;
        const tr = document.createElement('tr');
        tr.innerHTML = `
        <td><select class="form-select" name="fornecedor_id_${n}">${opcoesFornecedor}</select></td>
        <td><select class="form-select" name="tipo_relacao_${n}">${opcoesTipoRelacao}</select></td>
        <td>
            <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">
                <i class="fas fa-trash"></i>
            </button>
        </td>`;
        document.querySelector('#tabelaFornecedores tbody').appendChild(tr);
    });


    // ----------------------------------------------------------------
    // DADOS DAS LOCALIZAÇÕES (carregados da BD via PHP)
    // ----------------------------------------------------------------
    const localizacoes = {
        <?php foreach ($localizacoes_bd as $l) : ?>
            <?= $l->id ?>: {
                edificio: <?= json_encode($l->edificio) ?>,
                piso: <?= json_encode($l->piso) ?>,
                servico: <?= json_encode($l->servico) ?>,
                sala: <?= json_encode($l->sala) ?>
            },
        <?php endforeach; ?>
    };

    function preencherLocalizacao() {
        const id = document.getElementById('selectLocalizacao').value;
        const painel = document.getElementById('infoLocalizacao');

        if (!id) {
            painel.classList.add('d-none');
            return;
        }

        const l = localizacoes[id];
        if (!l) {
            painel.classList.add('d-none');
            return;
        }

        document.getElementById('l-edificio').textContent = l.edificio;
        document.getElementById('l-piso').textContent = l.piso;
        document.getElementById('l-servico').textContent = l.servico;
        document.getElementById('l-sala').textContent = l.sala;

        painel.classList.remove('d-none');
    }
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('tipo-documento')) {
            const linha = e.target.dataset.linha;
            const option = e.target.options[e.target.selectedIndex];
            const temValidade = option.dataset.temValidade === '1';

            const inputValidade = document.querySelector(`[name="validade_documento_${linha}"]`);

            if (inputValidade) {
                inputValidade.disabled = !temValidade;

                if (!temValidade) {
                    inputValidade.value = '';
                }
            }
        }
    });
    document.querySelectorAll('.tipo-documento').forEach(select => {
        select.dispatchEvent(new Event('change'));
    });
</script>


<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>