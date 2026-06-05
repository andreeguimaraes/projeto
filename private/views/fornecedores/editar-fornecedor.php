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
                        <h2 class="mb-0">Editar fornecedor</h2>
                        <p class="text-muted mb-0">Philips Healthcare Portugal</p>
                    </div>
                    <a href="fornecedor.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                </div>

                <form action="" method="post">
                    <div class="card shadow rounded">
                        <div class="card-body">

                            <!-- INFORMAÇÃO GERAL -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-info-circle me-2"></i>Informação geral
                            </h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Código</label>
                                    <input type="text"
                                        class="form-control"
                                        value="FOR001"
                                        readonly>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Nome da empresa <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nome" value="Philips Healthcare Portugal" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">NIF <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="nif" value="500 123 456" required>
                                </div>                                
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tipo de fornecedor <span class="text-danger">*</span></label>
                                    <select class="form-select" name="tipo" required>
                                        <option value="">Selecione...</option>
                                        <option selected>Fabricante</option>
                                        <option>Distribuidor / Fornecedor comercial</option>
                                        <option>Assistência técnica</option>
                                        <option>Fornecedor de consumíveis</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Morada</label>
                                    <input type="text" class="form-control" name="morada" value="Av. da Liberdade, 110, Lisboa">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Website</label>
                                    <input type="url" class="form-control" name="website" value="https://www.philips.pt">
                                </div>
                            </div>
                            <hr>

                            <!-- CONTACTOS -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-address-book me-2"></i>Contactos gerais
                            </h5>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Telefone <span class="text-danger">*</span></label>
                                    <input type="tel" class="form-control" name="telefone" value="+351 210 000 000" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" value="geral@philips.pt" required>
                                </div>
                            </div>
                            <hr>

                            <!-- PESSOA DE CONTACTO -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-user me-2"></i>Pessoa de contacto
                            </h5>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Nome</label>
                                    <input type="text" class="form-control" name="pessoa_contacto" value="João Ferreira">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Telefone direto</label>
                                    <input type="tel" class="form-control" name="telefone_contacto" value="+351 962 000 000">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Email direto</label>
                                    <input type="email" class="form-control" name="email_contacto" value="joao.ferreira@philips.pt">
                                </div>
                            </div>
                            <hr>

                            <!-- OBSERVAÇÕES -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-note-sticky me-2"></i>Observações
                            </h5>
                            <div class="mb-4">
                                <textarea class="form-control" name="observacoes" rows="4">Fornecedor certificado. Contrato de assistência técnica renovado em janeiro de 2026. Tempo de resposta garantido de 4 horas para equipamentos críticos.</textarea>
                            </div>

                            <!-- Nota campos obrigatórios -->
                            <p class="text-muted small mb-3">
                                <span class="text-danger">*</span> Campos obrigatórios
                            </p>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between">
                                <a href="fornecedor.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Cancelar
                                </a>
                                <div class="d-flex gap-2">
                                    <a href="detalhes-fornecedor.php" class="btn btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>Ver detalhes
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-floppy-disk me-1"></i>Guardar alterações
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>

            </main>
        </div>
    </div>
<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>

