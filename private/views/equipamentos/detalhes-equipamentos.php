<?php include '../../includes/header.php'; ?>


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
                        <h2 class="mb-0">Ficha do equipamento</h2>
                        <p class="text-muted mb-0">EQ001 — Monitor IntelliVue MP5</p>
                    </div>
                    <a href="equipamentos.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                </div>

                <div class="card shadow rounded mt-4">
                    <div class="card-body">

                        <!-- Badges de estado -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex gap-2">
                                <span class="badge bg-success fs-6">Ativo</span>
                                <span class="badge bg-danger fs-6">Suporte de vida</span>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="editar-equipamentos.php" class="btn btn-warning btn-sm">
                                    <i class="fas fa-pen me-1"></i>Editar
                                </a>
                                <button class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalEliminar">
                                    <i class="fas fa-trash me-1"></i>Eliminar
                                </button>
                            </div>
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
                                        <p class="form-control-plaintext">EQ001</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Designação</label>
                                        <p class="form-control-plaintext">Monitor IntelliVue MP5</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Categoria</label>
                                        <p class="form-control-plaintext">Monitorização</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Marca</label>
                                        <p class="form-control-plaintext">Philips</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Modelo</label>
                                        <p class="form-control-plaintext">IntelliVue MP5</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Número de série</label>
                                        <p class="form-control-plaintext">MP5-2022-45873</p>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Fabricante</label>
                                        <p class="form-control-plaintext">Philips Healthcare</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Ano de fabrico</label>
                                        <p class="form-control-plaintext">2022</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Criticidade</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-danger">Suporte de vida</span>
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
                                        <p class="form-control-plaintext">15/03/2022</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Custo de aquisição</label>
                                        <p class="form-control-plaintext">12.500,00 €</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Tipo de entrada</label>
                                        <p class="form-control-plaintext">Compra</p>
                                    </div>
                                </div>
                                <hr>
                                <h5 class="text-muted mb-3">
                                    <i class="fas fa-note-sticky me-2"></i>Observações
                                </h5>
                                <p class="form-control-plaintext">
                                    Equipamento em bom estado de conservação. Última manutenção preventiva realizada em janeiro de 2026.
                                </p>
                            </div>

                            <!-- TAB 2 — Localização -->
                            <div class="tab-pane fade" id="tab-localizacao">
                                <div class="row mb-4">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">
                                            Código
                                        </label>
                                        <p class="form-control-plaintext">
                                            LOC001
                                        </p>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">
                                            Edifício
                                        </label>
                                        <p class="form-control-plaintext">
                                            Edifício Principal
                                        </p>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">
                                            Piso
                                        </label>
                                        <p class="form-control-plaintext">
                                            Piso 2
                                        </p>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">
                                            Sala
                                        </label>
                                        <p class="form-control-plaintext">
                                            Sala 201
                                        </p>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-bold">
                                            Estado
                                        </label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-success">
                                                Ativa
                                            </span>
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
                                            Unidade de Cuidados Intensivos (UCI)
                                        </p>

                                    </div>

                                    <div class="col-md-6">

                                        <label class="form-label fw-bold">
                                            Responsável
                                        </label>

                                        <p class="form-control-plaintext">
                                            Dr. Ricardo Almeida
                                        </p>

                                    </div>

                                </div>
                                <!-- Botões -->
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" class="btn btn-primary">
                                        Ver localização
                                    </button>
                                </div>
                            </div>  
                            <!-- TAB 3 — Fornecedor -->
                            <div class="tab-pane fade" id="tab-fornecedor">                               
                                <!-- INFORMAÇÃO GERAL -->
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
                                        <label class="form-label fw-bold">Website</label>
                                        <p class="form-control-plaintext">
                                            <a href="https://www.philips.pt" target="_blank">www.philips.pt
                                                <i class="fas fa-external-link-alt ms-1 small"></i>
                                            </a>
                                        </p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-bold">Morada</label>
                                        <p class="form-control-plaintext">Av. da Liberdade, 110, Lisboa</p>
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
                                <!-- Botões -->
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <button type="button" class="btn btn-primary">
                                        Ver Fornecedor
                                    </button>
                                </div>
                            </div>

                            <!-- TAB 4 — Garantia -->
                            <div class="tab-pane fade" id="tab-garantia">
                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Código</label>
                                        <p class="form-control-plaintext">GAR001</p>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Tipo</label>
                                        <p class="form-control-plaintext">Garantia fabricante</p>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de início</label>
                                        <p class="form-control-plaintext">15/03/2022</p>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Data de fim</label>
                                        <p class="form-control-plaintext">15/03/2027</p>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Entidade responsável</label>
                                        <p class="form-control-plaintext">Philips Healthcare Portugal</p>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Periodicidade</label>
                                        <p class="form-control-plaintext">Anual</p>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label fw-bold">Estado</label>
                                        <p class="form-control-plaintext">
                                            <span class="badge bg-success">Ativa</span>
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <h6 class="text-muted mb-3">
                                    Observações
                                </h6>

                                <p class="form-control-plaintext">
                                    Contrato de manutenção preventiva associado ao equipamento.
                                </p>

                            </div>

                            <!-- TAB 5 — Documentação -->
                            <div class="tab-pane fade" id="tab-documentacao">
                                <ul class="list-group">

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                            Manual de utilizador
                                        </div>

                                        <a href="../../../docs/manual_mp5.pdf"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                            Ver
                                        </a>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                            Certificado de calibração
                                        </div>

                                        <a href="../../../docs/certificado.pdf"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                            Ver
                                        </a>
                                    </li>

                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <div>
                                            <i class="fas fa-file-pdf text-danger me-2"></i>
                                            Contrato de manutenção
                                        </div>

                                        <a href="../../../docs/contrato.pdf"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-primary">
                                            Ver
                                        </a>
                                    </li>

                                </ul>
                                <!-- Botões -->
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-primary">
                                    Ver Documentação
                                </button>
                            </div>
                        </div>
                        <!-- fim tab-content -->
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Modal de confirmação -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-triangle-exclamation me-2"></i>Confirmar eliminação
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Tem a certeza que pretende eliminar o seguinte equipamento?</p>
                    <div class="alert alert-light border">
                        <strong>EQ001 — Monitor IntelliVue MP5</strong><br>
                        <small class="text-muted">Esta ação não pode ser revertida.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Cancelar
                    </button>
                    <a href="equipamentos.php" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Eliminar equipamento
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- rodapé -->
    <?php include '../../includes/footer.php'; ?>
