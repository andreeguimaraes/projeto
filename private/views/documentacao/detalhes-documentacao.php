<?php include '../../includes/header.php'; ?>

    <!-- Navbar -->
    <?php include '../../includes/nav.php'; ?>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <?php include '../../includes/sidebar.php'; ?>

            <!-- Conteúdo -->
            <main class="col-12 p-4">

                <!-- Cabeçalho -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h2 class="mb-0">
                            Detalhes do documento
                        </h2>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="documentacao.php"
                           class="btn btn-outline-secondary">

                            <i class="fas fa-arrow-left me-2"></i>Voltar

                        </a>

                    </div>

                </div>

                <!-- Cartão -->
                <div class="card shadow rounded">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Código
                                </label>

                                <p class="form-control-plaintext">
                                    DOC001
                                </p>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Tipo de documento
                                </label>

                                <p class="form-control-plaintext">
                                    <span class="badge bg-secondary">
                                        Manual de utilizador
                                    </span>
                                </p>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Nome do documento
                                </label>

                                <p class="form-control-plaintext">
                                    Manual IntelliVue MP5
                                </p>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Estado da validade
                                </label>

                                <p class="form-control-plaintext">
                                    <span class="badge bg-success">
                                        Não aplicável
                                    </span>
                                </p>
                            </div>

                        </div>

                        <div class="row mb-4">

                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Data do documento
                                </label>

                                <p class="form-control-plaintext">
                                    15/01/2025
                                </p>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Data de validade
                                </label>

                                <p class="form-control-plaintext">
                                    Não aplicável
                                </p>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Equipamento associado
                                </label>

                                <p class="form-control-plaintext">
                                    EQ001 — Monitor IntelliVue MP5
                                </p>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">
                                    Fornecedor associado
                                </label>

                                <p class="form-control-plaintext">
                                    Philips Healthcare
                                </p>
                            </div>

                        </div>
                        <div class="row mb-4">
                            <div class="col-md-12">

                                <label class="form-label fw-bold">
                                    Ficheiro associado
                                </label>

                                <div class="border rounded p-3 bg-light d-flex align-items-center">

                                    <i class="fas fa-file-pdf text-danger fs-3 me-3"></i>

                                    <div>
                                        <div class="fw-semibold">
                                            manual_intellivue_mp5.pdf
                                        </div>

                                        <small class="text-muted">
                                            PDF
                                        </small>
                                    </div>

                                </div>

                            </div>
                        </div> <!-- ESTE FALTAVA -->

                        <div class="row mb-4">
                            <div class="col-md-12">

                                <label class="form-label fw-bold">
                                    Observações
                                </label>

                                <div class="border rounded p-3 bg-light">

                                    Documento fornecido pelo fabricante
                                    durante a instalação do equipamento.
                                    Utilizado pela equipa técnica e clínica.

                                </div>

                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="editar-documentacao.php" class="btn btn-warning">
                                <i class="fas fa-pen me-2"></i>Editar
                            </a>
                            <button type="button"
                                    class="btn btn-primary">

                                <i class="fas fa-download me-2"></i>
                                Transferir documento

                            </button>

                        </div>

                    </div>

                </div>

            </main>

        </div>
    </div>
<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>
