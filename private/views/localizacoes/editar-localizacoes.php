<?php include '../../includes/header.php'; ?>

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
                            Editar localização
                        </h2>

                        <p class="text-muted mb-0">
                            LOC001 — UCI Piso 2 Sala 201
                        </p>

                    </div>

                    <a href="localizacoes.html"
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
                                        Código
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           value="LOC001">

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Edifício
                                    </label>

                                    <select class="form-select">

                                        <option selected>
                                            Principal
                                        </option>

                                        <option>
                                            Anexo
                                        </option>

                                        <option>
                                            Bloco Cirúrgico
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Piso
                                    </label>

                                    <select class="form-select">

                                        <option>
                                            Piso 0
                                        </option>

                                        <option>
                                            Piso 1
                                        </option>

                                        <option selected>
                                            Piso 2
                                        </option>

                                        <option>
                                            Piso 3
                                        </option>

                                    </select>

                                </div>

                                <div class="col-md-3">

                                    <label class="form-label">
                                        Sala / Gabinete
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           value="Sala 201">

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
                                        Serviço
                                    </label>

                                    <select class="form-select">

                                        <option selected>
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

                                <div class="col-md-6">

                                    <label class="form-label">
                                        Responsável pela área
                                    </label>

                                    <input type="text"
                                           class="form-control"
                                           value="Enf. Marta Silva">

                                </div>

                            </div>

                            <hr>

                            <!-- EQUIPAMENTOS ASSOCIADOS
                            <h5 class="text-muted mb-3">
                                <i class="fas fa-stethoscope me-2"></i>
                                Equipamentos associados
                            </h5>

                            <div class="row mb-3">
                                <div class="col-md-9">
                                    <select class="form-select" name="novo_equipamento">
                                        <option value="">Selecione um equipamento para associar...</option>
                                        <option>EQ004 — Desfibrilhador Zoll</option>
                                        <option>EQ005 — Bomba de infusão</option>
                                        <option>EQ006 — Monitor IntelliVue MX450</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <button type="button" class="btn btn-primary w-100">
                                        <i class="fas fa-plus me-1"></i>
                                        Associar
                                    </button>
                                </div>
                            </div>

                            <div class="list-group mb-4">

                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>EQ001 — Monitor IntelliVue MP5</span>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalRemoverEquipamento">
                                        <i class="fas fa-trash me-1"></i>
                                        Remover
                                    </button>
                                </div>

                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>EQ002 — Ventilador Evita V500</span>


                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalRemoverEquipamento">
                                        <i class="fas fa-trash me-1"></i>
                                        Remover
                                    </button>
                                </div>

                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>EQ003 — Bomba de Infusão Space</span>

                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalRemoverEquipamento">
                                        <i class="fas fa-trash me-1"></i>
                                        Remover
                                    </button>
                                </div>

                            </div>

                            <small class="text-muted">
                                Ao associar um equipamento a esta localização, a localização atual desse equipamento será atualizada.
                            </small>
                            -->

                            <hr>

                            <!-- OBSERVAÇÕES -->
                            <h5 class="text-muted mb-3">

                                <i class="fas fa-note-sticky me-2"></i>
                                Observações

                            </h5>

                            <div class="mb-4">

                                <textarea class="form-control"
                                          rows="4">Localização reservada para equipamentos de monitorização intensiva.</textarea>

                            </div>

                            <hr>

                            <!-- Botões -->
                            <div class="d-flex justify-content-between">

                                <button type="submit"
                                        class="btn btn-warning">

                                    <i class="fas fa-floppy-disk me-1"></i>
                                    Guardar alterações

                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            </main>

        </div>

    </div>
    <!-- Modal remover equipamento associado
    <div class="modal fade" id="modalRemoverEquipamento" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-triangle-exclamation me-2"></i>
                        Remover equipamento da localização
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <p>
                        Tem a certeza que pretende remover este equipamento desta localização?
                    </p>

                    <div class="alert alert-light border">
                        <strong>EQ001 — Monitor IntelliVue MP5</strong><br>
                        <small class="text-muted">
                            O equipamento deixará de estar associado à localização LOC001 — UCI Sala 201.
                        </small>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-outline-secondary"
                            data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>
                        Cancelar
                    </button>

                    <button type="button"
                            class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Remover associação
                    </button>
                </div>

            </div>
        </div>
    </div> -->

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>