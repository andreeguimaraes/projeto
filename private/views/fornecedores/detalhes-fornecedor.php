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
                        <h2 class="mb-0">Ficha do Fornecedor</h2>
                    </div>
                    <a href="fornecedor.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                </div>

                <div class="card shadow rounded">
                    <div class="card-body">

                        <!-- Cabeçalho da ficha -->
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <h4 class="mb-0">Philips Healthcare Portugal</h4>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <!-- INFORMAÇÃO GERAL -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-info-circle me-2"></i>Informação geral
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Código</label>
                                <p class="form-control-plaintext">FOR001</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nome da empresa</label>
                                <p class="form-control-plaintext">Philips Healthcare Portugal</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">NIF</label>
                                <p class="form-control-plaintext">500 123 456</p>
                            </div>
                            
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tipo de fornecedor</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-primary">Fabricante</span>
                                </p>
                            </div>                            
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Morada</label>
                                <p class="form-control-plaintext">Av. da Liberdade, 110, Lisboa</p>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Website</label>
                                <p class="form-control-plaintext">
                                    <a href="https://www.philips.pt" target="_blank">www.philips.pt
                                        <i class="fas fa-external-link-alt ms-1 small"></i>
                                    </a>
                                </p>
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
                        <hr>

                        <!-- EQUIPAMENTOS ASSOCIADOS -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-stethoscope me-2"></i>
                            Equipamentos associados (3)
                        </h5>
                        <div class="mb-4">
                            <table class="table table-hover table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Designação</th>
                                        <th>Tipo de relação</th>
                                        <th>Estado</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>EQ001</td>
                                        <td>Monitor IntelliVue MP5</td>
                                        <td><span class="badge bg-primary">Fabricante</span></td>
                                        <td><span class="badge bg-success">Ativo</span></td>
                                        <td>
                                            <a href="../equipamentos/detalhes-equipamentos.php" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>EQ006</td>
                                        <td>Monitor IntelliVue MX450</td>
                                        <td><span class="badge bg-primary">Fabricante</span></td>
                                        <td><span class="badge bg-success">Ativo</span></td>
                                        <td>
                                            <a href="../equipamentos/detalhes-equipamentos.php" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>EQ012</td>
                                        <td>Ventilador Trilogy</td>
                                        <td><span class="badge bg-primary">Fabricante</span></td>
                                        <td><span class="badge bg-warning text-dark">Em manutenção</span></td>
                                        <td>
                                            <a href="../equipamentos/detalhes-equipamentos.php" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>

                        <!-- OBSERVAÇÕES -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-note-sticky me-2"></i>Observações
                        </h5>
                        <div class="mb-4">
                            <p class="form-control-plaintext">
                                Fornecedor certificado. Contrato de assistência técnica renovado em janeiro de 2026.
                                Tempo de resposta garantido de 4 horas para equipamentos críticos.
                            </p>
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
                    <p>Tem a certeza que pretende eliminar este fornecedor?</p>
                    <div class="alert alert-light border">
                        <strong>Philips Healthcare Portugal</strong><br>
                        <small class="text-muted">Esta ação não pode ser revertida. Os equipamentos associados perderão esta ligação.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Cancelar
                    </button>
                    <a href="fornecedor.php" class="btn btn-outline-secondary">
                        <i class="fas fa-trash me-1"></i>Eliminar fornecedor
                    </a>
                </div>
            </div>
        </div>
    </div>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>
