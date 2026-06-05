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
            
                        <h2 class="mb-0">
                            Nova garantia
                        </h2>
            
                        <p class="text-muted mb-0">
                            Registo de garantia e contrato de manutenção
                        </p>
            
                    </div>
            
                    <a href="garantias.php" class="btn btn-outline-secondary">
            
                        <i class="fas fa-arrow-left me-2"></i>
                        Voltar
            
                    </a>
            
                </div>

                <!-- Formulário -->
                <div class="card shadow rounded">

                    <div class="card-body">

                        <form>

                            <!-- EQUIPAMENTO -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-stethoscope me-2"></i>
                                Equipamento
                            </h5>

                            <div class="row mb-4">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Equipamento
                                    </label>

                                    <select class="form-select">

                                        <option selected disabled>
                                            Selecionar equipamento
                                        </option>

                                        <option>
                                            EQ001 — Monitor IntelliVue MP5
                                        </option>

                                        <option>
                                            EQ002 — Ventilador Evita V500
                                        </option>

                                        <option>
                                            EQ003 — Desfibrilhador Zoll
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Tipo de equipamento
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           placeholder="Ex.: Monitor multiparamétrico">

                                </div>

                            </div>

                            <hr>

                            <!-- GARANTIA -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-shield-halved me-2"></i>
                                Garantia
                            </h5>

                            <div class="row mb-4">

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Data de início
                                    </label>

                                    <input type="date"
                                           class="form-control">

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Data de fim
                                    </label>

                                    <input type="date"
                                           class="form-control">

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Duração
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           placeholder="Ex.: 5 anos">

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Estado
                                    </label>

                                    <select class="form-select">

                                        <option>
                                            Ativa
                                        </option>

                                        <option>
                                            Expirada
                                        </option>

                                        <option>
                                            Suspensa
                                        </option>

                                    </select>

                                </div>

                            </div>

                            <hr>

                            <!-- CONTRATO -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-file-contract me-2"></i>
                                Contrato de manutenção
                            </h5>

                            <div class="row mb-4">

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Tem contrato?
                                    </label>

                                    <select class="form-select">

                                        <option>
                                            Sim
                                        </option>

                                        <option>
                                            Não
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Tipo de contrato
                                    </label>

                                    <select class="form-select">

                                        <option>
                                            Manutenção preventiva
                                        </option>

                                        <option>
                                            Manutenção corretiva
                                        </option>

                                        <option>
                                            Completo
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Entidade responsável
                                    </label>

                                    <select class="form-select">

                                        <option selected disabled>
                                            Selecionar fornecedor
                                        </option>

                                        <option>
                                            Philips Healthcare Portugal
                                        </option>

                                        <option>
                                            Dräger Portugal
                                        </option>

                                        <option>
                                            Siemens Healthineers
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Periodicidade
                                    </label>

                                    <select class="form-select">

                                        <option>
                                            Mensal
                                        </option>

                                        <option>
                                            Trimestral
                                        </option>

                                        <option>
                                            Semestral
                                        </option>

                                        <option>
                                            Anual
                                        </option>

                                    </select>

                                </div>

                            </div>

                            <hr>

                            <!-- DOCUMENTAÇÃO -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-folder-open me-2"></i>
                                Documentação associada
                            </h5>

                            <div class="row mb-4">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Documento associado
                                    </label>

                                    <select class="form-select">

                                        <option selected disabled>
                                            Selecionar documento
                                        </option>

                                        <option>
                                            Contrato de manutenção 2026
                                        </option>

                                        <option>
                                            Certificado de calibração
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Observações
                                    </label>

                                    <textarea class="form-control"
                                              rows="3"
                                              placeholder="Observações adicionais..."></textarea>

                                </div>

                            </div>

                            <hr>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between">

                                <a href="garantias.php"
                                   class="btn btn-outline-secondary">

                                    <i class="fas fa-arrow-left me-1"></i>
                                    Cancelar

                                </a>

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="fas fa-floppy-disk me-1"></i>
                                    Guardar garantia

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </main>

        </div>

    </div>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>
