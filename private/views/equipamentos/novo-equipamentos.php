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
                    <a href="equipamentos.html" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                </div>

                <form action="" method="post" id="formEquipamento">
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
                                        <i class="fas fa-file-contract me-1"></i>Garantia
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
                                                placeholder="Ex: EQ048" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Designação <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="designacao"
                                                placeholder="Ex: Monitor multiparamétrico" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Categoria <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" name="categoria" required>
                                                <option value="">Selecione...</option>
                                                <option>Monitorização</option>
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
                                            <label class="form-label fw-bold">Marca <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="marca"
                                                placeholder="Ex: Philips" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Modelo <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="modelo"
                                                placeholder="Ex: IntelliVue MP5" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Número de série <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="num_serie"
                                                placeholder="Ex: MP5-2022-45873" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Fabricante</label>
                                            <input type="text" class="form-control" name="fabricante"
                                                placeholder="Ex: Philips Healthcare">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Ano de fabrico</label>
                                            <input type="number" class="form-control" name="ano_fabrico"
                                                placeholder="Ex: 2022" min="1900" max="2026">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Criticidade <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" name="criticidade" required>
                                                <option value="">Selecione...</option>
                                                <option>Baixa</option>
                                                <option>Média</option>
                                                <option>Alta</option>
                                                <option>Suporte de vida</option>
                                            </select>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- Aquisição — FORA da row anterior -->
                                    <h6 class="text-muted mb-3"><i class="fas fa-shopping-cart me-2"></i>Aquisição</h6>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Data de aquisição</label>
                                            <input type="date" class="form-control" name="data_aquisicao">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Custo de aquisição (€)</label>
                                            <input type="number" class="form-control" name="custo"
                                                placeholder="Ex: 12500" step="0.01" min="0">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Tipo de entrada</label>
                                            <select class="form-select" name="tipo_entrada">
                                                <option value="">Selecione...</option>
                                                <option>Compra</option>
                                                <option>Doação</option>
                                                <option>Aluguer</option>
                                                <option>Empréstimo</option>
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
                                    <!-- div de fecho a mais removida -->
                                    <h6 class="text-muted mb-3"><i class="fas fa-location-dot me-2"></i>Estado e
                                        localização</h6>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Estado atual <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" name="estado" required>
                                                <option value="">Selecione...</option>
                                                <option>Ativo</option>
                                                <option>Em manutenção</option>
                                                <option>Inativo</option>
                                                <option>Em calibração</option>
                                                <option>Em quarentena</option>
                                                <option>Abatido</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Edifício</label>
                                            <input type="text" class="form-control" name="edificio"
                                                placeholder="Ex: Principal">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Piso</label>
                                            <input type="text" class="form-control" name="piso" placeholder="Ex: 2">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Serviço</label>
                                            <select class="form-select" name="servico">
                                                <option value="">Selecione...</option>
                                                <option>UCI</option>
                                                <option>Urgência</option>
                                                <option>Bloco Operatório</option>
                                                <option>Medicina</option>
                                                <option>Pediatria</option>
                                                <option>Ortopedia</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Sala</label>
                                            <input type="text" class="form-control" name="sala" placeholder="Ex: 201">
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-geral')).show()">
                                            <i class="fas fa-arrow-left me-1"></i> Anterior
                                        </button>
                                        <button type="button" class="btn btn-primary" id="btn-next-localizacao" disabled
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
                                            <select class="form-select">
                                                <option selected>Garantia fabricante</option>
                                                <option>Contrato de manutenção</option>
                                                <option>Garantia estendida</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Data de início</label>
                                            <input type="date" class="form-control" value="2022-03-15">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Data de fim</label>
                                            <input type="date" class="form-control" value="2027-03-15">
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Entidade responsável</label>
                                            <select class="form-select">
                                                <option selected>Philips Healthcare Portugal</option>
                                                <option>Dräger Portugal</option>
                                                <option>B. Braun</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Estado</label>
                                            <select class="form-select">
                                                <option selected>Ativa</option>
                                                <option>Expirada</option>
                                                <option>Cancelada</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Periodicidade</label>
                                            <select class="form-select">
                                                <option selected>Anual</option>
                                                <option>Semestral</option>
                                                <option>Trimestral</option>
                                                <option>Mensal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-fornecedor')).show()">
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
                                                    <th>Caminho / Link</th>
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
                                                    <td><input type="text" class="form-control"
                                                            name="caminho_documento_1"
                                                            placeholder="docs/manual_mp5.pdf"></td>
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
                                            onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-garantia')).show()">
                                            <i class="fas fa-arrow-left me-1"></i> Anterior
                                        </button>
                                        <div class="d-flex gap-2">
                                            <a href="equipamentos.html" class="btn btn-outline-secondary">
                                                <i class="fas fa-arrow-left me-1"></i>Cancelar
                                            </a>
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

            <script>
                // Configuração: para cada tab com campos obrigatórios, define qual botão "Seguinte"
                // controla e qual tab do nav deve ser desbloqueada ao avançar
                const validacoes = [
                    {
                        painelId: 'geral',
                        btnNextId: 'btn-next-geral',
                        tabNavNextId: 'tab-localizacao'
                    },
                    {
                        painelId: 'localizacao',
                        btnNextId: 'btn-next-localizacao',
                        tabNavNextId: 'tab-fornecedor'
                    }
                ];

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
                document.getElementById('btnAddLinha').addEventListener('click', function () {
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
      <td><input type="text" class="form-control" name="caminho_documento_${n}"></td>`;
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
            </script>
        </div>
    </div>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>