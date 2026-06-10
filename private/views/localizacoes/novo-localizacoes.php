<?php require_once '../../includes/header.php';?>


    <!-- Navbar -->
    <?php include '../../includes/nav.php'; ?>

    <div class="container-fluid">

        <div class="row">

            <!-- Sidebar -->
            <?php include '../../includes/sidebar.php'; ?>

            <!-- Conteúdo Principal -->
            <main class="col-12 p-4">

                <!-- Cabeçalho -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h2 class="mb-0">
                            Nova localização
                        </h2>

                        <p class="text-muted mb-0">
                            Registo de localização física de equipamentos
                        </p>

                    </div>

                    <a href="localizacoes.php"
                       class="btn btn-outline-secondary">

                        <i class="fas fa-arrow-left me-2"></i>
                        Voltar

                    </a>

                </div>

                <!-- Formulário -->
                <div class="card shadow rounded">

                    <div class="card-body">

                        <form>

                            <!-- LOCALIZAÇÃO -->
                            <h5 class="text-muted mb-3">

                                <i class="fas fa-location-dot me-2"></i>
                                Dados da localização

                            </h5>

                            <div class="row mb-4">

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Código <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control" placeholder="Ex.: LOC001" required>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Edifício <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select" required>

                                        <option value="" selected disabled>
                                            Selecionar edifício 
                                        </option>

                                        <option>
                                            A
                                        </option>

                                        <option>
                                            B
                                        </option>

                                        <option>
                                            C
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Piso <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select" required>

                                        <option value="" selected disabled>
                                            Selecionar piso
                                        </option>

                                        <option>
                                            Piso 0
                                        </option>

                                        <option>
                                            Piso 1
                                        </option>

                                        <option>
                                            Piso 2
                                        </option>

                                        <option>
                                            Piso 3
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Sala / Gabinete <span class="text-danger">*</span>
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           placeholder="Ex.: Sala 201"
                                           required>

                                </div>

                            </div>

                            <hr>

                            <!-- SERVIÇO -->
                            <h5 class="text-muted mb-3">

                                <i class="fas fa-hospital me-2"></i>
                                Serviço / Departamento 

                            </h5>

                            <div class="row mb-4">

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Serviço <span class="text-danger">*</span>
                                    </label>

                                    <select class="form-select" required>

                                        <option selected disabled>
                                            Selecionar serviço
                                        </option>

                                        <option>
                                            UCI
                                        </option>

                                        <option>
                                            Urgência
                                        </option>

                                        <option>
                                            Bloco Operatório
                                        </option>

                                        <option>
                                            Medicina
                                        </option>

                                        <option>
                                            Pediatria
                                        </option>

                                        <option>
                                            Ortopedia
                                        </option>

                                    </select>

                                </div>

                            </div>

                            <hr>

                            <!-- EQUIPAMENTOS 
                            <h5 class="text-muted mb-3">

                                <i class="fas fa-stethoscope me-2"></i>
                                Equipamentos associados

                            </h5>

                            <div class="row mb-4">

                                <div class="col-md-12">

                                    <label class="form-label">
                                        Selecionar equipamentos
                                    </label>

                                    <select class="form-select">

                                        <option>
                                            EQ001 — Monitor IntelliVue MP5
                                        </option>

                                        <option>
                                            EQ002 — Ventilador Evita V500
                                        </option>

                                        <option>
                                            EQ003 — Desfibrilhador Zoll
                                        </option>

                                        <option>
                                            EQ004 — Bomba de infusão
                                        </option>

                                    </select>

                                    <small class="text-muted">
                                        Poderá associar vários equipamentos futuramente.
                                    </small>

                                </div>

                            </div>-->


                            <!-- OBSERVAÇÕES -->
                            <h5 class="text-muted mb-3">

                                <i class="fas fa-note-sticky me-2"></i>
                                Observações

                            </h5>

                            <div class="mb-4">

                                <textarea class="form-control"
                                          rows="4"
                                          placeholder="Informações adicionais sobre a localização..."></textarea>

                            </div>

                            <p class="text-muted small mb-3">
                                <span class="text-danger">*</span> Campos obrigatórios
                            </p>


                            <!-- Botões -->
                            <div class="d-flex justify-content-between">

                                <button type="submit"
                                        class="btn btn-primary">

                                    <i class="fas fa-floppy-disk me-1"></i>
                                    Guardar localização

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
