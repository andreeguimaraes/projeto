<?php require_once __DIR__ . '/../../includes/header.php'; ?>
    <!-- Navbar -->
    <?php include '../../includes/nav.php'; ?>

    <div class="container-fluid">
        <div class="row">

            <!-- Offcanvas Sidebar -->            
            <?php include '../../includes/sidebar.php'; ?>

            <!-- Conteúdo Principal -->
            <main class="col-12 p-4">

                <!-- Cabeçalho -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">Editar equipamento</h2>
                        <p class="text-muted mb-0">EQ001 — Monitor IntelliVue MP5</p>
                    </div>
                    <a href="equipamentos.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                </div>

                <form action="" method="post">
                    <div class="card shadow rounded mt-4">
                        <div class="card-body">

                            <!-- TABS -->
                            <ul class="nav nav-underline border-bottom mb-4" id="equipTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link active" id="tab-geral" data-bs-toggle="tab" href="#geral"
                                        role="tab">
                                        <i class="fas fa-info-circle me-1"></i>Informação geral
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab-localizacao" data-bs-toggle="tab" href="#localizacao"
                                        role="tab">
                                        <i class="fas fa-location-dot me-1"></i>Localização
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab-fornecedor" data-bs-toggle="tab" href="#fornecedor"
                                        role="tab">
                                        <i class="fas fa-truck-medical me-1"></i>Fornecedor
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab-garantia" data-bs-toggle="tab" href="#garantia"
                                        role="tab">
                                        <i class="fas fa-file-contract me-1"></i>Garantia
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab-docs" data-bs-toggle="tab" href="#documentacao"
                                        role="tab">
                                        <i class="fas fa-folder-open me-1"></i>Documentação
                                    </a>
                                </li>
                            </ul>

                            <!-- CONTEÚDO DAS TABS -->
                            <div class="tab-content">

                                <!-- TAB: INFORMAÇÃO GERAL -->
                                <div class="tab-pane fade show active" id="geral" role="tabpanel">
                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Código interno <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="codigo" value="EQ001"
                                                required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Designação <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="designacao"
                                                value="Monitor IntelliVue MP5" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Categoria <span
                                                    class="text-danger">*</span></label>
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
                                            <label class="form-label fw-bold">Marca <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="marca" value="Philips"
                                                required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Modelo <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="modelo" value="IntelliVue MP5"
                                                required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Número de série <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" name="num_serie"
                                                value="MP5-2022-45873" required>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Fabricante</label>
                                            <input type="text" class="form-control" name="fabricante"
                                                value="Philips Healthcare">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Ano de fabrico</label>
                                            <input type="number" class="form-control" name="ano_fabrico" value="2022"
                                                min="1900" max="2026">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Criticidade <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" name="criticidade" required>
                                                <option value="">Selecione...</option>
                                                <option>Baixa</option>
                                                <option>Média</option>
                                                <option>Alta</option>
                                                <option selected>Suporte de vida</option>
                                            </select>
                                        </div>
                                    </div>

                                    <hr>
                                    <h5 class="text-muted mb-3"><i class="fas fa-shopping-cart me-2"></i>Aquisição</h5>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Data de aquisição</label>
                                            <input type="date" class="form-control" name="data_aquisicao"
                                                value="2022-03-15">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Custo de aquisição (€)</label>
                                            <input type="number" class="form-control" name="custo" value="12500"
                                                step="0.01" min="0">
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
                                        <textarea class="form-control" name="observacoes"
                                            rows="4">Equipamento em bom estado de conservação. Última manutenção preventiva realizada em janeiro de 2026.</textarea>
                                    </div>

                                    <p class="text-muted small"><span class="text-danger">*</span> Campos obrigatórios
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <a href="detalhe-equipamento.php" class="btn btn-outline-secondary">
                                            <i class="fas fa-arrow-left me-1"></i>Cancelar
                                        </a>
                                        <button type="button" class="btn btn-primary"
                                            onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-localizacao')).show()">
                                            Seguinte <i class="fas fa-arrow-right ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- TAB: LOCALIZAÇÃO -->
                                <div class="tab-pane fade" id="localizacao" role="tabpanel">
                                    <h5 class="text-muted mb-3"><i class="fas fa-location-dot me-2"></i>Estado e
                                        localização</h5>
                                    <div class="row mb-3">
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Estado atual <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-select" name="estado" required>
                                                <option value="">Selecione...</option>
                                                <option selected>Ativo</option>
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
                                                value="Edifício Principal">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Piso</label>
                                            <input type="text" class="form-control" name="piso" value="Piso 2">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label fw-bold">Serviço</label>
                                            <select class="form-select" name="servico">
                                                <option value="">Selecione...</option>
                                                <option selected>UCI</option>
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
                                            <input type="text" class="form-control" name="sala" value="Sala 201">
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

                                <!-- TAB 3 — Fornecedor -->
                                <div class="tab-pane fade" id="fornecedor" role="tabpanel">

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Nome da empresa</label>
                                            <p class="form-control-plaintext">Philips Healthcare Portugal</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">NIF</label>
                                            <p class="form-control-plaintext">500 123 456</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Tipo de fornecedor</label>
                                            <p class="form-control-plaintext">
                                                <span class="badge bg-primary">Fabricante</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Morada</label>
                                            <p class="form-control-plaintext">Av. da Liberdade, 110, Lisboa</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Website</label>
                                            <p class="form-control-plaintext">
                                                <a href="https://www.philips.pt" target="_blank">
                                                    www.philips.pt <i class="fas fa-external-link-alt ms-1 small"></i>
                                                </a>
                                            </p>
                                        </div>
                                    </div>

                                    <hr>
                                    <h5 class="text-muted mb-3">
                                        <i class="fas fa-address-book me-2"></i>Contactos
                                    </h5>

                                    <div class="row mb-3">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Telefone</label>
                                            <p class="form-control-plaintext">
                                                <i class="fas fa-phone me-1 text-muted"></i>+351 210 000 000
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Email</label>
                                            <p class="form-control-plaintext">
                                                <i class="fas fa-envelope me-1 text-muted"></i>geral@philips.pt
                                            </p>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Pessoa de contacto</label>
                                            <p class="form-control-plaintext">João Ferreira</p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Telefone direto</label>
                                            <p class="form-control-plaintext">
                                                <i class="fas fa-phone me-1 text-muted"></i>+351 962 000 000
                                            </p>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label fw-bold">Email direto</label>
                                            <p class="form-control-plaintext">
                                                <i class="fas fa-envelope me-1 text-muted"></i>joao.ferreira@philips.pt
                                            </p>
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

                                <!-- TAB: GARANTIA -->
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
                                                <option>Garantia do fabricante</option>
                                                <option>Garantia estendida</option>
                                                <option>Contrato de manutenção preventiva</option>
                                                <option>Contrato de manutenção corretiva</option>
                                                <option>Contrato de manutenção total</option>
                                                <option>Sem contrato</option>
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
                                                    <td><input type="text" class="form-control" name="nome_documento_1"
                                                            value="Manual MP5"></td>
                                                    <td><input type="date" class="form-control" name="data_documento_1"
                                                            value="2022-03-15"></td>
                                                    <td><input type="date" class="form-control"
                                                            name="validade_documento_1"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="caminho_documento_1" value="docs/manual_mp5.pdf"></td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            onclick="this.closest('tr').remove()">
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
                                                    <td><input type="text" class="form-control" name="nome_documento_2"
                                                            value="Certificado de calibração"></td>
                                                    <td><input type="date" class="form-control" name="data_documento_2"
                                                            value="2024-01-10"></td>
                                                    <td><input type="date" class="form-control"
                                                            name="validade_documento_2" value="2025-01-10"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="caminho_documento_2" value="docs/certificado.pdf">
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            onclick="this.closest('tr').remove()">
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
                                                    <td><input type="text" class="form-control" name="nome_documento_3"
                                                            value="Contrato de manutenção"></td>
                                                    <td><input type="date" class="form-control" name="data_documento_3"
                                                            value="2022-03-15"></td>
                                                    <td><input type="date" class="form-control"
                                                            name="validade_documento_3" value="2027-03-15"></td>
                                                    <td><input type="text" class="form-control"
                                                            name="caminho_documento_3" value="docs/contrato.pdf"></td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                            onclick="this.closest('tr').remove()">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <button type="button" class="btn btn-outline-secondary btn-sm mb-4"
                                        id="btnAddLinha">
                                        <i class="fas fa-plus me-1"></i> Adicionar linha
                                    </button>

                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="bootstrap.Tab.getOrCreateInstance(document.querySelector('#tab-garantia')).show()">
                                            <i class="fas fa-arrow-left me-1"></i> Anterior
                                        </button>
                                        <div class="d-flex gap-2">
                                            <a href="detalhe-equipamento.php" class="btn btn-outline-secondary">
                                                <i class="fas fa-arrow-left me-1"></i>Cancelar
                                            </a>
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
                <td><input type="text" class="form-control" name="caminho_documento_${n}"></td>
                <td>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="this.closest('tr').remove()">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>`;
            document.querySelector('#tabelaDocs tbody').appendChild(tr);
        });
    </script>
<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>
