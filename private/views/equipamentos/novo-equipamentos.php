<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// --------------------------------------------------------------------
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged(); // Inicia a sessão (se necessário) e verifica se o utilizador está autenticado

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Recolher dados
    $codigo        = $_POST["codigo"] ?? "";
    $designacao    = $_POST["designacao"] ?? "";
    $categoria     = $_POST["categoria"] ?? "";
    $marca         = $_POST["marca"] ?? "";
    $modelo        = $_POST["modelo"] ?? "";
    $num_serie     = $_POST["num_serie"] ?? "";
    $fabricante    = $_POST["fabricante"] ?? "";
    $ano_fabrico   = $_POST["ano_fabrico"] ?? "";
    $criticidade   = $_POST["criticidade"] ?? "";
    $estado        = $_POST["estado_equipamento"] ?? "";
    $data_aquisicao = $_POST["data_aquisicao"] ?? "";
    $custo         = $_POST["custo"] ?? "";
    $tipo_entrada  = $_POST["tipo_entrada"] ?? "";
    $localizacao_id = $_POST["localizacao_id"] ?? "";
    $fornecedor_id  = $_POST["fornecedor_id"] ?? "";

    // Garantia
    $tipo_garantia       = $_POST["tipo_garantia"] ?? "";
    $data_inicio_garantia = $_POST["data_inicio"] ?? "";
    $data_fim_garantia   = $_POST["data_fim"] ?? "";
    $estado_garantia     = $_POST["estado_garantia"] ?? "";
    $periodicidade_garantia = $_POST["periodicidade_garantia"] ?? "";

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
    $num_serie   = trim($num_serie);
    $fabricante  = trim($fabricante);
    $observacoes = trim($observacoes);

    // ----------------------------------------------------------------
    // 3. VALIDAR
    // ----------------------------------------------------------------
    $erros        = [];
    $erro_sistema = '';
    $sucesso      = false;

    // === TAB: INFORMAÇÃO GERAL ===

    if (empty($codigo)) {
        $erros[] = "O código interno é obrigatório.";
    } elseif (!preg_match('/^[A-Za-z0-9\-_]+$/', $codigo)) {
        $erros[] = "O código interno só pode conter letras, números, hífens e underscores.";
    } elseif (strlen($codigo) > 50) {
        $erros[] = "O código interno não pode ter mais de 50 caracteres.";
    }

    if (empty($designacao)) {
        $erros[] = "A designação é obrigatória.";
    } elseif (strlen($designacao) < 3) {
        $erros[] = "A designação deve ter pelo menos 3 caracteres.";
    } elseif (!preg_match('/[a-zA-ZÀ-ÿ]/', $designacao)) {
        $erros[] = "A designação deve conter pelo menos uma letra.";
    } elseif (strlen($designacao) > 150) {
        $erros[] = "A designação não pode ter mais de 150 caracteres.";
    }

    if (empty($categoria)) {
        $erros[] = "A categoria é obrigatória.";
    }

    if (empty($marca)) {
        $erros[] = "A marca é obrigatória.";
    } elseif (strlen($marca) < 2) {
        $erros[] = "A marca deve ter pelo menos 2 caracteres.";
    } elseif (!preg_match('/[a-zA-ZÀ-ÿ]/', $marca)) {
        $erros[] = "A marca deve conter pelo menos uma letra.";
    } elseif (strlen($marca) > 100) {
        $erros[] = "A marca não pode ter mais de 100 caracteres.";
    }

    if (!empty($fabricante)) {
        if (strlen($fabricante) < 2) {
            $erros[] = "O fabricante deve ter pelo menos 2 caracteres.";
        } elseif (!preg_match('/[a-zA-ZÀ-ÿ]/', $fabricante)) {
            $erros[] = "O fabricante deve conter pelo menos uma letra.";
        } elseif (strlen($fabricante) > 150) {
            $erros[] = "O fabricante não pode ter mais de 150 caracteres.";
        }
    }

    if (empty($modelo)) {
        $erros[] = "O modelo é obrigatório.";
    } elseif (strlen($modelo) > 100) {
        $erros[] = "O modelo não pode ter mais de 100 caracteres.";
    }

    if (empty($num_serie)) {
        $erros[] = "O número de série é obrigatório.";
    } elseif (strlen($num_serie) > 100) {
        $erros[] = "O número de série não pode ter mais de 100 caracteres.";
    }


    if (!empty($ano_fabrico)) {
        if (!preg_match('/^\d{4}$/', $ano_fabrico)) {
            $erros[] = "O ano de fabrico deve ter 4 dígitos (ex: 2022).";
        } elseif ((int)$ano_fabrico < 1900 || (int)$ano_fabrico > (int)date('Y')) {
            $erros[] = "O ano de fabrico deve estar entre 1900 e " . date('Y') . ".";
        }
    }

    $criticidades_validas = ['baixa', 'média', 'alta', 'suporte de vida'];
    if (empty($criticidade)) {
        $erros[] = "A criticidade é obrigatória.";
    } elseif (!in_array(strtolower($criticidade), $criticidades_validas)) {
        $erros[] = "O valor de criticidade selecionado não é válido.";
    }

    $estados_validos = ['ativo', 'em manutenção', 'inativo', 'em calibração', 'em quarentena', 'abatido'];
    if (empty($estado)) {
        $erros[] = "O estado é obrigatório.";
    } elseif (!in_array(strtolower($estado), $estados_validos)) {
        $erros[] = "O valor de estado selecionado não é válido.";
    }

    if (!empty($data_aquisicao)) {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data_aquisicao)) {
            $erros[] = "Formato da data de aquisição inválido.";
        } else {
            $partes = explode('-', $data_aquisicao);
            if (!checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0])) {
                $erros[] = "A data de aquisição não é uma data válida.";
            } elseif ($data_aquisicao > date('Y-m-d')) {
                $erros[] = "A data de aquisição não pode ser no futuro.";
            }
        }
    }

    if (!empty($custo)) {
        if (!is_numeric($custo) || (float)$custo < 0) {
            $erros[] = "O custo de aquisição deve ser um valor numérico positivo.";
        } elseif ((float)$custo > 9999999.99) {
            $erros[] = "O custo de aquisição introduzido é demasiado elevado.";
        }
    }

    $tipos_entrada_validos = ['compra', 'doação', 'aluguer', 'empréstimo'];
    if (!empty($tipo_entrada) && !in_array(strtolower($tipo_entrada), $tipos_entrada_validos)) {
        $erros[] = "O tipo de entrada selecionado não é válido.";
    }

    // === TAB: LOCALIZAÇÃO ===

    if (empty($localizacao_id)) {
        $erros[] = "A localização é obrigatória.";
    } elseif (!filter_var($localizacao_id, FILTER_VALIDATE_INT) || (int)$localizacao_id <= 0) {
        $erros[] = "A localização selecionada não é válida.";
    }

    // === TAB: FORNECEDOR ===

    if (!empty($fornecedor_id)) {
        if (!filter_var($fornecedor_id, FILTER_VALIDATE_INT) || (int)$fornecedor_id <= 0) {
            $erros[] = "O fornecedor selecionado não é válido.";
        }
    }

    // === TAB: GARANTIA ===

    $tem_garantia = !empty($tipo_garantia)
        || !empty($data_inicio_garantia)
        || !empty($data_fim_garantia);

    if ($tem_garantia) {
        if (empty($tipo_garantia)) {
            $erros[] = "O tipo de garantia é obrigatório quando a garantia está preenchida.";
        }
        if (empty($data_inicio_garantia)) {
            $erros[] = "A data de início da garantia é obrigatória.";
        } else {
            $partes = explode('-', $data_inicio_garantia);
            if (!checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0])) {
                $erros[] = "A data de início da garantia não é uma data válida.";
            }
        }
        if (empty($data_fim_garantia)) {
            $erros[] = "A data de fim da garantia é obrigatória.";
        } else {
            $partes = explode('-', $data_fim_garantia);
            if (!checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0])) {
                $erros[] = "A data de fim da garantia não é uma data válida.";
            }
        }
        if (!empty($data_inicio_garantia) && !empty($data_fim_garantia)) {
            if ($data_fim_garantia <= $data_inicio_garantia) {
                $erros[] = "A data de fim da garantia deve ser posterior à data de início.";
            }
        }
    }

    // === TAB: CONTRATO ===

    $tem_contrato = !empty($tipo_contrato)
        || !empty($data_inicio_contrato)
        || !empty($data_fim_contrato)
        || !empty($entidade_contrato);

    if ($tem_contrato) {
        if (empty($tipo_contrato)) {
            $erros[] = "O tipo de contrato é obrigatório quando o contrato está preenchido.";
        }
        if (empty($data_inicio_contrato)) {
            $erros[] = "A data de início do contrato é obrigatória.";
        } else {
            $partes = explode('-', $data_inicio_contrato);
            if (!checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0])) {
                $erros[] = "A data de início do contrato não é uma data válida.";
            }
        }
        if (empty($data_fim_contrato)) {
            $erros[] = "A data de fim do contrato é obrigatória.";
        } else {
            $partes = explode('-', $data_fim_contrato);
            if (!checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0])) {
                $erros[] = "A data de fim do contrato não é uma data válida.";
            }
        }
        if (!empty($data_inicio_contrato) && !empty($data_fim_contrato)) {
            if ($data_fim_contrato <= $data_inicio_contrato) {
                $erros[] = "A data de fim do contrato deve ser posterior à data de início.";
            }
        }
        $periodicidades_validas = ['mensal', 'trimestral', 'semestral', 'anual'];
        if (!empty($periodicidade_contrato) && !in_array(strtolower($periodicidade_contrato), $periodicidades_validas)) {
            $erros[] = "A periodicidade do contrato selecionada não é válida.";
        }
    }

    // === TAB: DOCUMENTAÇÃO ===

    $documentos = [];
    $i = 1;
    while (isset($_POST["tipo_documento_$i"])) {
        $tipo_doc  = trim($_POST["tipo_documento_$i"] ?? "");
        $nome_doc  = trim($_POST["nome_documento_$i"] ?? "");
        $data_doc  = trim($_POST["data_documento_$i"] ?? "");
        $valid_doc = trim($_POST["validade_documento_$i"] ?? "");

        $linha_preenchida = !empty($tipo_doc) || !empty($nome_doc) || !empty($data_doc) || !empty($valid_doc);

        if ($linha_preenchida) {
            if (empty($tipo_doc)) {
                $erros[] = "O tipo do documento na linha $i é obrigatório.";
            }
            if (empty($nome_doc)) {
                $erros[] = "O nome do documento na linha $i é obrigatório.";
            }
            if (!empty($data_doc)) {
                $partes = explode('-', $data_doc);
                if (!checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0])) {
                    $erros[] = "A data do documento na linha $i não é válida.";
                }
            }
            if (!empty($valid_doc)) {
                $partes = explode('-', $valid_doc);
                if (!checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0])) {
                    $erros[] = "A data de validade do documento na linha $i não é válida.";
                }
            }
            if (!empty($data_doc) && !empty($valid_doc) && $valid_doc < $data_doc) {
                $erros[] = "A validade do documento na linha $i não pode ser anterior à sua data.";
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
    // ----------------------------------------------------------------
    // 4. NORMALIZAR E GRAVAR (só se não houver erros)
    // Normalizar entrada. independentemente de como o utilizador escreve os dados, o sistema assegura consistência e padronização antes de qualquer registo na base de dados. 
    // ----------------------------------------------------------------
    $designacao  = ucwords(strtolower($designacao));
    $marca       = ucwords(strtolower($marca));
    $num_serie   = strtoupper($num_serie);
    $fabricante  = !empty($fabricante) ? ucwords(strtolower($fabricante)) : null;
    $ano_fabrico    = !empty($ano_fabrico)    ? (int)$ano_fabrico   : null;
    $custo          = !empty($custo)          ? (float)$custo        : null;
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
                "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );

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
                ':numero_serie'    => $num_serie,
                ':fabricante'      => $fabricante,
                ':ano_fabrico'     => $ano_fabrico,
                ':data_aquisicao'  => $data_aquisicao,
                ':custo_aquisicao' => $custo,
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
                    INSERT INTO garantias (codigo, equipamento_id, tipo_id, data_inicio, data_fim, entidade_responsavel, estado)
                    VALUES (:codigo, :equipamento_id, :tipo_id, :data_inicio, :data_fim, :entidade_responsavel, :estado)
                ")->execute([
                        ':codigo'               => $codigo_gar,
                        ':equipamento_id'       => $equipamento_id,
                        ':tipo_id'              => $tipo_garantia_id,
                        ':data_inicio'          => $data_inicio_garantia ?: null,
                        ':data_fim'             => $data_fim_garantia    ?: null,
                        ':entidade_responsavel' => !empty($_POST['entidade_garantia']) ? trim($_POST['entidade_garantia']) : null,
                        ':estado'               => !empty($estado_garantia) ? strtolower($estado_garantia) : 'ativa',
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
                    INSERT INTO contratos (codigo, equipamento_id, tipo_id, data_inicio, data_fim, entidade_responsavel, periodicidade, estado)
                    VALUES (:codigo, :equipamento_id, :tipo_id, :data_inicio, :data_fim, :entidade_responsavel, :periodicidade, :estado)
                ")->execute([
                        ':codigo'               => $codigo_con,
                        ':equipamento_id'       => $equipamento_id,
                        ':tipo_id'              => $tipo_contrato_id,
                        ':data_inicio'          => $data_inicio_contrato ?: null,
                        ':data_fim'             => $data_fim_contrato    ?: null,
                        ':entidade_responsavel' => $entidade_contrato    ?: null,
                        ':periodicidade'        => !empty($periodicidade_contrato) ? strtolower($periodicidade_contrato) : null,
                        ':estado'               => !empty($estado_contrato) ? strtolower($estado_contrato) : 'ativo',
                    ]);
                }
            }

            // INSERT equipamento_fornecedor
            if (!empty($fornecedor_id)) {
                $ligacao->prepare("
                INSERT INTO equipamento_fornecedor (equipamento_id, fornecedor_id, tipo_id)
                VALUES (:equipamento_id, :fornecedor_id, :tipo_id)
            ")->execute([
                    ':equipamento_id' => $equipamento_id,
                    ':fornecedor_id'  => (int)$fornecedor_id,
                    ':tipo_id'        => 1,
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
                                            placeholder="Ex: EQ048" required
                                            value="<?= $_POST['codigo'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Designação <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="designacao"
                                            placeholder="Ex: Monitor multiparamétrico" required
                                            value="<?= $_POST['designacao'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Categoria <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select" name="categoria" required>
                                            <option value="">Selecione...</option>
                                            <option <?= ($_POST['categoria'] ?? '') == 'Monitorização' ? 'selected' : '' ?>>Monitorização</option>
                                            <option <?= ($_POST['categoria'] ?? '') == 'Suporte de vida' ? 'selected' : '' ?>>Suporte de vida</option>
                                            <option <?= ($_POST['categoria'] ?? '') == 'Terapia' ? 'selected' : '' ?>>Terapia</option>
                                            <option <?= ($_POST['categoria'] ?? '') == 'Diagnóstico' ? 'selected' : '' ?>>Diagnóstico</option>
                                            <option <?= ($_POST['categoria'] ?? '') == 'Laboratório' ? 'selected' : '' ?>>Laboratório</option>
                                            <option <?= ($_POST['categoria'] ?? '') == 'Esterilização' ? 'selected' : '' ?>>Esterilização</option>
                                            <option <?= ($_POST['categoria'] ?? '') == 'Reabilitação' ? 'selected' : '' ?>>Reabilitação</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Marca <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="marca"
                                            placeholder="Ex: Philips" required
                                            value="<?= $_POST['marca'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Modelo <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="modelo"
                                            placeholder="Ex: IntelliVue MP5" required
                                            value="<?= $_POST['modelo'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Número de série <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="num_serie"
                                            placeholder="Ex: MP5-2022-45873" required
                                            value="<?= $_POST['num_serie'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Fabricante</label>
                                        <input type="text" class="form-control" name="fabricante"
                                            placeholder="Ex: Philips Healthcare"
                                            value="<?= $_POST['fabricante'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Ano de fabrico</label>
                                        <input type="number" class="form-control" name="ano_fabrico"
                                            placeholder="Ex: 2022" min="1900" max="2026"
                                            value="<?= $_POST['ano_fabrico'] ?? '' ?>">

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
                                        <select class="form-select" name="estado_equipamento" required>
                                            <option value="">Selecione...</option>
                                            <option <?= ($_POST['estado_equipamento'] ?? '') == 'Ativo' ? 'selected' : '' ?>>Ativo</option>
                                            <option <?= ($_POST['estado_equipamento'] ?? '') == 'Em manutenção' ? 'selected' : '' ?>>Em manutenção</option>
                                            <option <?= ($_POST['estado_equipamento'] ?? '') == 'Inativo' ? 'selected' : '' ?>>Inativo</option>
                                            <option <?= ($_POST['estado_equipamento'] ?? '') == 'Em calibração' ? 'selected' : '' ?>>Em calibração</option>
                                            <option <?= ($_POST['estado_equipamento'] ?? '') == 'Em quarentena' ? 'selected' : '' ?>>Em quarentena</option>
                                            <option <?= ($_POST['estado_equipamento'] ?? '') == 'Abatido' ? 'selected' : '' ?>>Abatido</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <!-- Aquisição — FORA da row anterior -->
                                <h6 class="text-muted mb-3"><i class="fas fa-shopping-cart me-2"></i>Aquisição</h6>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Data de aquisição</label>
                                        <input type="date" class="form-control" name="data_aquisicao"
                                            value="<?= $_POST['data_aquisicao'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Custo de aquisição (€)</label>
                                        <input type="number" class="form-control" name="custo"
                                            placeholder="Ex: 12500" step="0.01" min="0"
                                            value="<?= $_POST['custo'] ?? '' ?>">
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
                                    <button type="button" class="btn btn-primary" id="btn-next-geral" disabled
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-localizacao')).show()">
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
                                            <option value="1">UCI — Sala 201 — Piso 2</option>
                                            <option value="2">Urgência — Sala 101 — Piso 1</option>
                                            <option value="3">Bloco Operatório — Sala 301 — Piso 3</option>
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
                                    <button type="button" class="btn btn-primary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-fornecedor')).show()">
                                        Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
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
                                            <option value="1">Philips Healthcare Portugal</option>
                                            <option value="2">Dräger Portugal</option>
                                            <option value="3">B. Braun Portugal</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Painel de informação — só aparece quando um fornecedor é selecionado -->
                                <div id="infoFornecedor" class="d-none">
                                    <hr>
                                    <h6 class="text-muted mb-3"><i class="fas fa-building me-2"></i>Informação do
                                        fornecedor</h6>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Nome da empresa</label>
                                            <p class="form-control-plaintext" id="f-nome">—</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">NIF</label>
                                            <p class="form-control-plaintext" id="f-nif">—</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Tipo de fornecedor</label>
                                            <p class="form-control-plaintext" id="f-tipo">—</p>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Morada</label>
                                            <p class="form-control-plaintext" id="f-morada">—</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Website</label>
                                            <p class="form-control-plaintext" id="f-website">—</p>
                                        </div>
                                    </div>

                                    <hr>
                                    <h6 class="text-muted mb-3"><i class="fas fa-address-book me-2"></i>Contactos
                                    </h6>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Telefone</label>
                                            <p class="form-control-plaintext" id="f-telefone">—</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Email</label>
                                            <p class="form-control-plaintext" id="f-email">—</p>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Pessoa de contacto</label>
                                            <p class="form-control-plaintext" id="f-contacto">—</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Telefone direto</label>
                                            <p class="form-control-plaintext" id="f-tel-direto">—</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Email direto</label>
                                            <p class="form-control-plaintext" id="f-email-direto">—</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-localizacao')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-garantia')).show()">
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
                                            <option <?= ($_POST['tipo_garantia'] ?? '') == 'Garantia do fabricante' ? 'selected' : '' ?>>Garantia do fabricante</option>
                                            <option <?= ($_POST['tipo_garantia'] ?? '') == 'Garantia estendida' ? 'selected' : '' ?>>Garantia estendida</option>
                                            <option <?= ($_POST['tipo_garantia'] ?? '') == 'Sem garantia' ? 'selected' : '' ?>>Sem garantia</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de início</label>
                                        <input type="date" class="form-control" name="data_inicio"
                                            value="<?= $_POST['data_inicio'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de fim</label>
                                        <input type="date" class="form-control" name="data_fim"
                                            value="<?= $_POST['data_fim'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Entidade responsável</label>
                                        <select class="form-select" name="entidade_garantia">
                                            <option selected>Philips Healthcare Portugal</option>
                                            <option>Dräger Portugal</option>
                                            <option>B. Braun</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <select class="form-select" name="estado_garantia">
                                            <option <?= ($_POST['estado_garantia'] ?? '') == 'Ativa' ? 'selected' : '' ?>>Ativa</option>
                                            <option <?= ($_POST['estado_garantia'] ?? '') == 'Expirada' ? 'selected' : '' ?>>Expirada</option>
                                            <option <?= ($_POST['estado_garantia'] ?? '') == 'Cancelada' ? 'selected' : '' ?>>Cancelada</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Periodicidade</label>
                                        <select class="form-select" name="periodicidade_garantia">
                                            <option <?= ($_POST['periodicidade_garantia'] ?? '') == 'Anual' ? 'selected' : '' ?>>Anual</option>
                                            <option <?= ($_POST['periodicidade_garantia'] ?? '') == 'Semestral' ? 'selected' : '' ?>>Semestral</option>
                                            <option <?= ($_POST['periodicidade_garantia'] ?? '') == 'Trimestral' ? 'selected' : '' ?>>Trimestral</option>
                                            <option <?= ($_POST['periodicidade_garantia'] ?? '') == 'Mensal' ? 'selected' : '' ?>>Mensal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Ficheiro</label>
                                        <input type="file" class="form-control" name="ficheiro_garantia" accept=".pdf,.doc,.docx">
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-fornecedor')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-contrato')).show()">
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
                                            <option <?= ($_POST['tipo_contrato'] ?? '') == 'Manutenção preventiva' ? 'selected' : '' ?>>Manutenção preventiva</option>
                                            <option <?= ($_POST['tipo_contrato'] ?? '') == 'Manutenção corretiva' ? 'selected' : '' ?>>Manutenção corretiva</option>
                                            <option <?= ($_POST['tipo_contrato'] ?? '') == 'Manutenção total' ? 'selected' : '' ?>>Manutenção total</option>
                                            <option <?= ($_POST['tipo_contrato'] ?? '') == 'Sem contrato' ? 'selected' : '' ?>>Sem contrato</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Entidade responsável</label>
                                        <select class="form-select" name="entidade_contrato">
                                            <option value="">Selecione...</option>
                                            <option <?= ($_POST['entidade_contrato'] ?? '') == 'Philips Healthcare Portugal' ? 'selected' : '' ?>>Philips Healthcare Portugal</option>
                                            <option <?= ($_POST['entidade_contrato'] ?? '') == 'Dräger Portugal' ? 'selected' : '' ?>>Dräger Portugal</option>
                                            <option <?= ($_POST['entidade_contrato'] ?? '') == 'B. Braun' ? 'selected' : '' ?>>B. Braun</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de início</label>
                                        <input type="date" class="form-control" name="data_inicio_contrato"
                                            value="<?= $_POST['data_inicio_contrato'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de fim</label>
                                        <input type="date" class="form-control" name="data_fim_contrato"
                                            value="<?= $_POST['data_fim_contrato'] ?? '' ?>">
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
                                            placeholder="Informações adicionais sobre o equipamento..."><?= $_POST['observacoes'] ?? '' ?></textarea>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <button type="button" class="btn btn-outline-secondary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-garantia')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-docs')).show()">
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
                                                    <select class="form-select" name="tipo_documento_1">
                                                        <option value="">Selecione...</option>
                                                        <option>Manual de utilizador</option>
                                                        <option>Manual de serviço</option>
                                                        <option>Certificado de calibração</option>
                                                        <option>Contrato de manutenção</option>
                                                        <option>Fatura de aquisição</option>
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

                                <h6 class="text-muted mb-3"><i class="fas fa-note-sticky me-2"></i>Observações</h6>
                                <div class="mb-4">
                                    <textarea class="form-control" name="observacoes" rows="4"
                                        placeholder="Informações adicionais sobre o equipamento..."></textarea>
                                </div>

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
    // Configuração: para cada tab com campos obrigatórios, define qual botão "Seguinte"
    // controla e qual tab do nav deve ser desbloqueada ao avançar
    const validacoes = [{
        painelId: 'geral',
        btnNextId: 'btn-next-geral',
        tabNavNextId: 'tab-localizacao'
    }, ];

    function tabEstaValida(painelId) {
        const painel = document.getElementById(painelId);
        const camposObrigatorios = painel.querySelectorAll('[required]');
        return Array.from(camposObrigatorios).every(campo => campo.value.trim() !== '');
    }

    function atualizarBotao(config) {
        const valida = tabEstaValida(config.painelId);
        const btnNext = document.getElementById(config.btnNextId);
        const tabNext = document.getElementById(config.tabNavNextId);

        // Ativa ou desativa o botão "Seguinte"
        btnNext.disabled = !valida;

        // Ativa ou desativa o link do separador na nav
        if (valida) {
            tabNext.classList.remove('disabled', 'pe-none');
        } else {
            tabNext.classList.add('disabled', 'pe-none');
        }
    }

    // Para cada configuração, monitoriza em tempo real todos os campos obrigatórios
    validacoes.forEach(config => {
        const painel = document.getElementById(config.painelId);
        painel.addEventListener('input', () => atualizarBotao(config));
        painel.addEventListener('change', () => atualizarBotao(config));
        // Avaliação inicial (garante estado correto ao carregar a página)
        atualizarBotao(config);
    });

    // Adicionar linhas na tabela de documentação
    let numLinhas = 1;
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
    <td><input type="file" class="form-control form-control-sm" name="ficheiro_documento_${n}" accept=".pdf,.doc,.docx"></td>`;
        document.querySelector('#tabelaDocs tbody').appendChild(tr);
    });
    // Base de dados local dos fornecedores
    // Em produção, estes dados viriam de uma chamada ao servidor (fetch/AJAX)
    const fornecedores = {
        1: {
            nome: "Philips Healthcare Portugal",
            nif: "500 123 456",
            tipo: "Fabricante",
            morada: "Av. da Liberdade, 110, Lisboa",
            website: "www.philips.pt",
            telefone: "+351 210 000 000",
            email: "geral@philips.pt",
            contacto: "João Ferreira",
            telDireto: "+351 962 000 000",
            emailDireto: "joao.ferreira@philips.pt"
        },
        2: {
            nome: "Dräger Portugal",
            nif: "500 234 567",
            tipo: "Fabricante",
            morada: "Rua do Ouro, 55, Porto",
            website: "www.draeger.com/pt",
            telefone: "+351 220 000 000",
            email: "geral@draeger.pt",
            contacto: "Ana Sousa",
            telDireto: "+351 933 000 000",
            emailDireto: "ana.sousa@draeger.pt"
        },
        3: {
            nome: "B. Braun Portugal",
            nif: "500 345 678",
            tipo: "Distribuidor",
            morada: "Av. do Brasil, 23, Lisboa",
            website: "www.bbraun.pt",
            telefone: "+351 210 111 000",
            email: "geral@bbraun.pt",
            contacto: "Carlos Mendes",
            telDireto: "+351 912 000 000",
            emailDireto: "carlos.mendes@bbraun.pt"
        }
    };

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
    const localizacoes = {
        1: {
            edificio: "Principal",
            piso: "2",
            servico: "UCI",
            sala: "201"
        },
        2: {
            edificio: "Principal",
            piso: "1",
            servico: "Urgência",
            sala: "101"
        },
        3: {
            edificio: "Principal",
            piso: "3",
            servico: "Bloco Operatório",
            sala: "301"
        }
    };

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
</script>


<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>