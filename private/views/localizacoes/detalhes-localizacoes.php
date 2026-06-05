<?php
require_once '../../includes/header.php';?>

    <!-- Navbar -->
    <?php include '../../includes/nav.php'; ?>

    <div class="container-fluid">

        <div class="row">

            <!-- Sidebar -->
            <?php include '../../includes/sidebar.php'; ?>

            <!-- Conteúdo principal -->
            <main class="col-12 p-4">

                <!-- Cabeçalho -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h2 class="mb-0">
                            Detalhes da localização
                        </h2>

                        <p class="text-muted mb-0">
                            UCI — Sala 201
                        </p>

                    </div>

                    <a href="localizacoes.html"
                       class="btn btn-outline-secondary">

                        <i class="fas fa-arrow-left me-2"></i>
                        Voltar

                    </a>

                </div>

                <!-- Card principal -->
                <div class="card shadow rounded">

                    <div class="card-body">

                        <!-- Cabeçalho -->
                        <div class="d-flex justify-content-between align-items-start mb-4">

                            <div class="d-flex align-items-center gap-3">
                                <div>

                                    <h4 class="mb-1">
                                        Unidade de Cuidados Intensivos
                                    </h4>
                                </div>

                            </div>

                            <div class="d-flex gap-2">

                                <a href="editar-localizacoes.html"
                                   class="btn btn-warning">

                                    <i class="fas fa-pen me-1"></i>
                                    Editar

                                </a>

                                <button class="btn btn-outline-secondary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEliminar">

                                    <i class="fas fa-trash me-1"></i>
                                    Eliminar

                                </button>

                            </div>

                        </div>

                        <hr>

                        <!-- Dados da localização -->
                        <h5 class="text-muted mb-3">

                            <i class="fas fa-building me-2"></i>
                            Informações da localização

                        </h5>

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
                                    Edifício A
                                </p>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label fw-bold">
                                    Piso
                                </label>

                                <p class="form-control-plaintext">
                                    Piso 2
                                </p>

                            </div>

                            <div class="col-md-3">

                                <label class="form-label fw-bold">
                                    Sala
                                </label>

                                <p class="form-control-plaintext">
                                    Sala 201
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

                        <hr>

                        <!-- Equipamentos associados -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-stethoscope me-2"></i>
                            Equipamentos associados (3)
                        </h5>

                        <div class="table-responsive mb-4">

                            <table class="table table-hover align-middle">

                                <thead class="table-light">

                                    <tr>

                                        <th>Código</th>
                                        <th>Equipamento</th>
                                        <th>Marca</th>
                                        <th>Estado</th>
                                        <th>Ações</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr>

                                        <td>EQ001</td>

                                        <td>Monitor IntelliVue MP5</td>

                                        <td>Philips</td>

                                        <td>
                                            <span>
                                                Operacional
                                            </span>
                                        </td>

                                        <td>
                                            <a href="../equipamentos/detalhes-equipamentos.html"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Ver detalhes do equipamento">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>

                                    </tr>

                                    <tr>

                                        <td>EQ002</td>

                                        <td>Ventilador Evita V500</td>

                                        <td>Dräger</td>

                                        <td>
                                            <span>
                                                Manutenção
                                            </span>
                                        </td>

                                        <td>

                                            <a href="../equipamentos/detalhes-equipamentos.html"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Ver detalhes do equipamento">

                                                <i class="fas fa-eye"></i>

                                            </a>

                                        </td>

                                    </tr>

                                    <tr>

                                        <td>EQ003</td>

                                        <td>Bomba de Infusão Space</td>

                                        <td>B. Braun</td>

                                        <td>
                                            <span>
                                                Operacional
                                            </span>
                                        </td>

                                        <td>

                                            <a href="../equipamentos/detalhes-equipamentos.html"
                                               class="btn btn-sm btn-outline-primary"
                                               title="Ver detalhes do equipamento">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                        <!-- Observações -->
                        <h5 class="text-muted mb-3">

                            <i class="fas fa-note-sticky me-2"></i>
                            Observações

                        </h5>

                        <div class="mb-4">

                            <p class="form-control-plaintext">

                                Área equipada com monitorização contínua e equipamentos de suporte de vida.
                                Acesso reservado a profissionais autorizados.

                            </p>

                        </div>


                    </div>

                </div>

            </main>

        </div>

    </div>

    <!-- Modal eliminar -->
    <div class="modal fade"
         id="modalEliminar"
         tabindex="-1"
         aria-hidden="true">

        <div class="modal-dialog">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title">

                        <i class="fas fa-triangle-exclamation me-2"></i>
                        Confirmar eliminação

                    </h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <p>
                        Tem a certeza que pretende eliminar esta localização?
                    </p>

                    <div class="alert alert-light border">

                        <strong>
                            LOC001 — UCI Sala 201
                        </strong>

                        <br>

                        <small class="text-muted">
                            Esta ação não pode ser revertida.
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

                    <a href="localizacoes.html"
                       class="btn btn-danger">

                        <i class="fas fa-trash me-1"></i>
                        Eliminar

                    </a>

                </div>

            </div>

        </div>

    </div>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>