<?php
require_once '../../includes/header.php';  ?>
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
                        <h2 class="mb-0">Detalhes da garantia</h2>
                    </div>
                    <a href="garantias.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                </div>

                <div class="card shadow rounded">
                    <div class="card-body">

                        <!-- Cabeçalho da ficha -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <h4 class="mb-0">GAR001</h4>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="editar-garantia.php" class="btn btn-warning">
                                    <i class="fas fa-pen me-1"></i>Editar
                                </a>
                                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalEliminar">
                                    <i class="fas fa-trash me-1"></i>Eliminar
                                </button>
                            </div>
                        </div>
                        <hr>

                        <!-- EQUIPAMENTO -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-stethoscope me-2"></i>Equipamento associado
                        </h5>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Código</label>
                                <p class="form-control-plaintext">EQ001</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Designação</label>
                                <p class="form-control-plaintext">Monitor IntelliVue MP5</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Marca</label>
                                <p class="form-control-plaintext">Philips</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Localização</label>
                                <p class="form-control-plaintext">UCI — Sala 201</p>
                            </div>
                        </div>
                        <hr>

                        <!-- GARANTIA -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-shield-halved me-2"></i>Garantia
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Código da garantia
                                </label>

                                <p class="form-control-plaintext">
                                    GAR001
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold"> Tipo </label>
                                <p class="form-control-plaintext"> Garantia do fabricante </p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Início de garantia</label>
                                <p class="form-control-plaintext">15/03/2022</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Fim de garantia</label>
                                <p class="form-control-plaintext">15/03/2027</p>
                            </div>
                            
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Duração</label>
                                <p class="form-control-plaintext">5 anos</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Estado</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-success">Ativa</span>
                                </p>
                            </div>                      
                        </div>

                        
                        <hr>

                        <!-- CONTRATO -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-file-contract me-2"></i>Contrato de manutenção
                        </h5>
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Tem contrato</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-success">Sim</span>
                                </p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Tipo de contrato</label>
                                <p class="form-control-plaintext">Manutenção preventiva</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Entidade responsável</label>
                                <p class="form-control-plaintext">Philips Healthcare Portugal</p>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Periodicidade</label>
                                <p class="form-control-plaintext">Anual</p>
                            </div>
                        </div>
                        <hr>

                        <!-- OBSERVAÇÕES -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-note-sticky me-2"></i>Observações
                        </h5>
                        <div class="mb-4">
                            <p class="form-control-plaintext">
                                Contrato renovado em janeiro de 2026. Inclui manutenção preventiva anual e suporte técnico remoto.
                            </p>
                        </div>
                        <hr>

                        <!-- DOCUMENTAÇÃO ASSOCIADA -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-folder-open me-2"></i>Documentação associada
                        </h5>
                        <div class="mb-4">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-file-pdf me-2 text-danger"></i>
                                        Contrato de manutenção 2026
                                    </span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Ver
                                    </a>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <i class="fas fa-file-pdf me-2 text-danger"></i>
                                        Certificado de calibração 2026
                                    </span>
                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Ver
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between">
                            <a href="garantias.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Voltar
                            </a>
                            <div class="d-flex gap-2">
                                <a href="../equipamentos/detalhes-equipamentos.php" class="btn btn-outline-primary">
                                    <i class="fas fa-stethoscope me-1"></i>Ver equipamento
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- Modal Eliminar -->
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
                    <p>Tem a certeza que pretende eliminar este registo de garantia?</p>
                    <div class="alert alert-light border">
                        <strong>GAR001 — Monitor IntelliVue MP5</strong><br>
                        <small class="text-muted">Esta ação não pode ser revertida.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Cancelar
                    </button>
                    <a href="garantias.php" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Eliminar
                    </a>
                </div>
            </div>
        </div>
    </div>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>
