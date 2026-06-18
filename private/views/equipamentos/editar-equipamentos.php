<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

require_once __DIR__ . '/../../includes/validacoes.php';

// Só aceita GET e POST
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/equipamentos.php');
    exit;
}

// Desencriptar e validar o ID
$idEncriptado = $_GET['id'] ?? null;
$id = aes_decrypt($idEncriptado);

if (!$id || !is_numeric($id)) {
    header('Location: ' . BASE_URL . '/private/views/equipamentos/equipamentos.php');
    exit;
}
echo ($id);

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
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
                    <p class="text-muted mb-0">EQ001 — Monitor IntelliVue MP5</p>
                </div>
                <a href="equipamentos.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <form action="" method="post" id="formEditar" enctype="multipart/form-data">
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
                                        <input type="text" class="form-control" name="codigo" value="EQ001" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Designação <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="designacao" value="Monitor IntelliVue MP5" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Categoria <span class="text-danger">*</span></label>
                                        <select class="form-select" name="categoria" required>
                                            <option value="">Selecione...</option>
                                            <option selected>Monitorização</option>
                                            <option>Suporte de vida</option>
                                            <option>Terapia</option>
                                            <option>Diagnóstico</option>
                                            <option>Laboratório</option>
                                            <option>Esterilização</option>
                                            <option>Reabilitação</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Marca <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="marca" value="Philips" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Modelo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="modelo" value="IntelliVue MP5" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Número de série <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="num_serie" value="MP5-2022-45873" required>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Fabricante</label>
                                        <input type="text" class="form-control" name="fabricante" value="Philips Healthcare">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Ano de fabrico</label>
                                        <input type="number" class="form-control" name="ano_fabrico" value="2022" min="1900" max="2026">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Criticidade <span class="text-danger">*</span></label>
                                        <select class="form-select" name="criticidade" required>
                                            <option value="">Selecione...</option>
                                            <option>Baixa</option>
                                            <option>Média</option>
                                            <option>Alta</option>
                                            <option selected>Suporte de vida</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Estado atual <span class="text-danger">*</span></label>
                                        <select class="form-select" name="estado_equipamento" required>
                                            <option value="">Selecione...</option>
                                            <option selected>Ativo</option>
                                            <option>Em manutenção</option>
                                            <option>Inativo</option>
                                            <option>Em calibração</option>
                                            <option>Em quarentena</option>
                                            <option>Abatido</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="text-muted mb-3"><i class="fas fa-shopping-cart me-2"></i>Aquisição</h5>
                                <div class="row mb-4">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Data de aquisição</label>
                                        <input type="date" class="form-control" name="data_aquisicao" value="2022-03-15">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Custo de aquisição (€)</label>
                                        <input type="number" class="form-control" name="custo" value="12500" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Tipo de entrada</label>
                                        <select class="form-select" name="tipo_entrada">
                                            <option value="">Selecione...</option>
                                            <option selected>Compra</option>
                                            <option>Doação</option>
                                            <option>Aluguer</option>
                                            <option>Empréstimo</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="text-muted mb-3"><i class="fas fa-note-sticky me-2"></i>Observações</h5>
                                <div class="mb-4">
                                    <textarea class="form-control" name="observacoes" rows="4">Equipamento em bom estado de conservação. Última manutenção preventiva realizada em janeiro de 2026.</textarea>
                                </div>
                                <p class="text-muted small"><span class="text-danger">*</span> Campos obrigatórios</p>
                                <div class="d-flex justify-content-end">
                                    <button type="button" class="btn btn-primary"
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
                                            <option value="1" selected>UCI — Sala 201 — Piso 2</option>
                                            <option value="2">Urgência — Sala 101 — Piso 1</option>
                                            <option value="3">Bloco Operatório — Sala 301 — Piso 3</option>
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
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-localizacao')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <button type="button" class="btn btn-primary"
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-garantia')).show()">
                                        Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
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
                                        onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-contrato')).show()">
                                        <i class="fas fa-arrow-left me-1"></i> Anterior
                                    </button>
                                    <button type="submit" class="btn btn-warning">
                                        <i class="fas fa-floppy-disk me-1"></i>Guardar alterações
                                    </button>
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

    const fornecedores = {
        1: { nome: "Philips Healthcare Portugal", nif: "500 123 456", tipo: "Fabricante", morada: "Av. da Liberdade, 110, Lisboa", website: "www.philips.pt", telefone: "+351 210 000 000", email: "geral@philips.pt", contacto: "João Ferreira", telDireto: "+351 962 000 000", emailDireto: "joao.ferreira@philips.pt" },
        2: { nome: "Dräger Portugal", nif: "500 234 567", tipo: "Fabricante", morada: "Rua do Ouro, 55, Porto", website: "www.draeger.com/pt", telefone: "+351 220 000 000", email: "geral@draeger.pt", contacto: "Ana Sousa", telDireto: "+351 933 000 000", emailDireto: "ana.sousa@draeger.pt" },
        3: { nome: "B. Braun Portugal", nif: "500 345 678", tipo: "Distribuidor", morada: "Av. do Brasil, 23, Lisboa", website: "www.bbraun.pt", telefone: "+351 210 111 000", email: "geral@bbraun.pt", contacto: "Carlos Mendes", telDireto: "+351 912 000 000", emailDireto: "carlos.mendes@bbraun.pt" }
    };

    function preencherFornecedor() {
        const id = document.getElementById('selectFornecedor').value;
        const painel = document.getElementById('infoFornecedor');
        if (!id) { painel.classList.add('d-none'); return; }
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
        1: { edificio: "Principal", piso: "2", servico: "UCI", sala: "201" },
        2: { edificio: "Principal", piso: "1", servico: "Urgência", sala: "101" },
        3: { edificio: "Principal", piso: "3", servico: "Bloco Operatório", sala: "301" }
    };

    function preencherLocalizacao() {
        const id = document.getElementById('selectLocalizacao').value;
        const painel = document.getElementById('infoLocalizacao');
        if (!id) { painel.classList.add('d-none'); return; }
        const l = localizacoes[id];
        document.getElementById('l-edificio').textContent = l.edificio;
        document.getElementById('l-piso').textContent = l.piso;
        document.getElementById('l-servico').textContent = l.servico;
        document.getElementById('l-sala').textContent = l.sala;
        painel.classList.remove('d-none');
    }
</script>

<?php include '../../includes/footer.php'; ?>