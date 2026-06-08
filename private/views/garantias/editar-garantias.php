<?php
require_once __DIR__ . '/../../includes/header.php'; ?>
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
                        <h2 class="mb-0">Editar garantia / contrato</h2>
                    </div>
                    <a href="garantias.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Voltar
                    </a>
                </div>

                <form action="" method="post">
                    <div class="card shadow rounded">
                        <div class="card-body">

                            <!-- EQUIPAMENTO -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-stethoscope me-2"></i>Equipamento
                            </h5>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Equipamento associado</label>
                                    <input type="text" class="form-control" value="EQ001 — Monitor IntelliVue MP5" readonly>
                                    <small class="text-muted">O equipamento não pode ser alterado</small>
                                </div>
                            </div>
                            <hr>

                            <!-- GARANTIA -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-shield-halved me-2"></i>Garantia
                            </h5>
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Código</label>
                                    <input type="text" class="form-control" value="GAR001" readonly>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Início de garantia <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="inicio_garantia" value="2022-03-15" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Fim de garantia <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control" name="fim_garantia" value="2027-03-15" required>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Duração</label>
                                    <input type="text" class="form-control" value="5 anos" disabled>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold">Estado</label>
                                    <select class="form-select" name="estado">
                                        <option selected>Ativa</option>
                                        <option>A expirar</option>
                                        <option>Expirada</option>
                                        <option>Cancelada</option>
                                    </select>
                                </div>
                            </div>
                            <hr>

                            <!-- CONTRATO -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-file-contract me-2"></i>Contrato de manutenção
                            </h5>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Existe contrato de manutenção?</label>
                                <div class="d-flex gap-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tem_contrato" id="simContrato" value="sim" checked>
                                        <label class="form-check-label" for="simContrato">Sim</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tem_contrato" id="naoContrato" value="nao">
                                        <label class="form-check-label" for="naoContrato">Não</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tipo de contrato</label>
                                    <select class="form-select" name="tipo_contrato">
                                        <option value="">Selecione...</option>
                                        <option selected>Manutenção preventiva</option>
                                        <option>Garantia do fabricante</option>
                                        <option>Garantia estendida</option>
                                        <option>Contrato de manutenção preventiva</option>
                                        <option>Contrato de manutenção corretiva</option>
                                        <option>Contrato de manutenção total</option>
                                        <option>Sem contrato</option>>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Entidade responsável</label>
                                    <input type="text" class="form-control" name="entidade" value="Philips Healthcare Portugal">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Periodicidade</label>
                                    <select class="form-select" name="periodicidade">
                                        <option value="">Selecione...</option>
                                        <option>Mensal</option>
                                        <option>Trimestral</option>
                                        <option>Semestral</option>
                                        <option selected>Anual</option>
                                    </select>
                                </div>
                            </div>
                            <hr>

                            <!-- OBSERVAÇÕES -->
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-note-sticky me-2"></i>Observações
                            </h5>
                            <div class="mb-4">
                                <textarea class="form-control" name="observacoes" rows="4">Contrato renovado em janeiro de 2026. Inclui manutenção preventiva anual e suporte técnico remoto.</textarea>
                            </div>

                            <!-- Nota campos obrigatórios -->
                            <p class="text-muted small mb-3">
                                <span class="text-danger">*</span> Campos obrigatórios
                            </p>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between">
                                <a href="garantias.php" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-floppy-disk me-1"></i>Guardar alterações
                                </button>
                            </div>

                        </div>
                    </div>
                </form>

            </main>
        </div>
    </div>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>
