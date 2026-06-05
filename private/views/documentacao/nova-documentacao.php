<?php require_once '../../includes/header.php';?>

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

                        <h2 class="mb-0">Novo documento</h2>

                        <p class="text-muted mb-0">
                            Registe um novo documento associado a equipamentos ou fornecedores
                        </p>

                    </div>

                    <a href="documentacao.php"
                       class="btn btn-outline-secondary">

                        <i class="fas fa-arrow-left me-2"></i>Voltar

                    </a>

                </div>

                <!-- Formulário -->
                <div class="card">

                    <div class="card-body">

                        <form>

                            <div class="row g-4">

                                <!-- Tipo -->
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Tipo de documento
                                    </label>

                                    <select class="form-select" required>

                                        <option value="">Selecionar...</option>

                                        <option>Manual de utilizador</option>
                                        <option>Manual de serviço</option>
                                        <option>Certificado de calibração</option>
                                        <option>Contrato de manutenção</option>
                                        <option>Fatura de aquisição</option>
                                        <option>Declaração de conformidade</option>
                                        <option>Relatório técnico</option>

                                    </select>

                                </div>

                                <!-- Nome -->
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Nome do documento
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           placeholder="Ex: Manual IntelliVue MP5"
                                           required>

                                </div>

                                <!-- Data documento -->
                                <div class="col-md-4">

                                    <label class="form-label">
                                        Data do documento
                                    </label>

                                    <input type="date"
                                           class="form-control">

                                </div>

                                <!-- Validade -->
                                <div class="col-md-4">

                                    <label class="form-label">
                                        Data de validade
                                    </label>

                                    <input type="date"
                                           class="form-control">

                                </div>

                                <!-- Equipamento -->
                                <div class="col-md-4">

                                    <label class="form-label">
                                        Equipamento associado
                                    </label>

                                    <select class="form-select">

                                        <option value="">Selecionar...</option>

                                        <option>
                                            EQ001 — Monitor IntelliVue MP5
                                        </option>

                                        <option>
                                            EQ002 — Ventilador Evita V500
                                        </option>

                                        <option>
                                            EQ003 — Desfibrilhador R Series
                                        </option>

                                        <option>
                                            EQ004 — Bomba de infusão
                                        </option>

                                    </select>

                                </div>

                                <!-- Fornecedor -->
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Fornecedor associado
                                    </label>

                                    <select class="form-select">

                                        <option value="">Selecionar...</option>

                                        <option>Philips Healthcare</option>
                                        <option>Dräger</option>
                                        <option>Zoll Medical</option>
                                        <option>B. Braun</option>

                                    </select>

                                </div>

                                <!-- Nome ficheiro -->
                                <div class="col-md-6">

                                    <label class="form-label">
                                        Nome do ficheiro / caminho
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           placeholder="Ex: documentos/manual_mp5.pdf">

                                </div>

                                <!-- Upload -->
                                <div class="col-12">

                                    <label class="form-label">
                                        Upload do ficheiro
                                    </label>

                                    <input type="file"
                                           class="form-control">

                                    <small class="text-muted">
                                        Opcional — pode utilizar upload real
                                        ou apenas guardar o nome/caminho do ficheiro.
                                    </small>

                                </div>

                                <!-- Observações -->
                                <div class="col-12">

                                    <label class="form-label">
                                        Observações
                                    </label>

                                    <textarea class="form-control"
                                              rows="4"
                                              placeholder="Informações adicionais sobre o documento..."></textarea>

                                </div>

                            </div>

                            <!-- Botões -->
                            <div class="d-flex justify-content-end gap-2 mt-4">

                                <a href="documentacao.php"
                                   class="btn btn-outline-secondary">

                                    <i class="fas fa-xmark me-2"></i>Cancelar

                                </a>

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="fas fa-floppy-disk me-2"></i>
                                    Guardar documento

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
