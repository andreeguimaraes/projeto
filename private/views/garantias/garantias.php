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
                        <h2 class="mb-0">Garantias e Contratos</h2>
                        <p class="text-muted mb-0">Gestão de garantias e contratos de manutenção dos equipamentos</p>
                    </div>
                </div>

                <!-- Alertas -->
                <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span>4 garantias expiradas — revisão necessária</span>
                </div>
                <div class="alert alert-warning d-flex align-items-center gap-2 mb-4">
                    <i class="fas fa-clock"></i>
                    <span>3 garantias a expirar nos próximos 30 dias</span>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card indicador-card text-center">
                            <div class="card-body py-3">
                                <h4 class="mb-0 text-primary">47</h4>
                                <p class="text-muted mb-0 small">Total de registos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card text-center">
                            <div class="card-body py-3">
                                <h4 class="mb-0 text-success">39</h4>
                                <p class="text-muted mb-0 small">Garantias ativas</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card text-center">
                            <div class="card-body py-3">
                                <h4 class="mb-0 text-warning">3</h4>
                                <p class="text-muted mb-0 small">A expirar em 30 dias</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card text-center">
                            <div class="card-body py-3">
                                <h4 class="mb-0 text-danger">4</h4>
                                <p class="text-muted mb-0 small">Expiradas</p>
                            </div>
                        </div>
                    </div>
                </div> 

                <!-- Filtros -->
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row g-2">
                            <!-- Pesquisa -->
                            <div class="col">
                                <div class="input-group">
                                    <div style="position: relative; flex: 1;">
                                        <input
                                            type="text"
                                            id="pesquisaEquipamento"
                                            class="form-control pe-5"
                                            placeholder="Pesquisar garantia ou equipamento..."
                                        >

                                        <button
                                            type="button"
                                            onclick="document.getElementById('pesquisaEquipamento').value='';"
                                            title="Limpar pesquisa"
                                            class="btn-limpar-pesquisa"
                                        >
                                            <i class="fas fa-xmark"></i>
                                        </button>
                                    </div>

                                    <button
                                        class="btn btn-primary"
                                        type="button"
                                        onclick="filtrar()"
                                    >
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>     
                            <div class="row g-2">                 
                                <div class="col-md-3">
                                    <select class="form-select">
                                        <option value="">Tipo de garantia/contrato</option>
                                        <option>Garantia do fabricante</option>
                                        <option>Garantia estendida</option>
                                        <option>Manutenção preventiva</option>
                                        <option>Manutenção corretiva</option>
                                        <option>Manutenção total</option>
                                        <option>Sem contrato</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select">
                                        <option value="">Estado da garantia</option>
                                        <option>Ativa</option>
                                        <option>A expirar (30 dias)</option>
                                        <option>Expirada</option>
                                    </select>
                                </div>                               
                                <div class="col-md-3">
                                    <select class="form-select">
                                        <option value="">Entidade responsável</option>
                                        <option>Philips Healthcare</option>
                                        <option>Dräger Portugal</option>
                                        <option>Zoll Medical</option>
                                        <option>GE Healthcare</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select class="form-select">
                                        <option value="">Periodicidade</option>
                                        <option>Mensal</option>
                                        <option>Trimestral</option>
                                        <option>Semestral</option>
                                        <option>Anual</option>
                                    </select>
                                </div>
                                <div class="col-md-auto">
                                    <button
                                        type="button"
                                        class="btn btn-outline-secondary"
                                        title="Limpar filtros">

                                        <i class="fas fa-filter-circle-xmark"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabela -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Equipamento</th>
                                        <th>Início garantia</th>
                                        <th>Fim garantia</th>
                                        <th>Estado</th>
                                        <th>Tipo contrato</th>
                                        <th>Entidade responsável</th>
                                        <th>Periodicidade</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>GAR001</td>
                                        <td>Monitor IntelliVue MP5</td>
                                        <td>15/03/2022</td>
                                        <td>15/03/2027</td>
                                        <td><span class="badge bg-success">Ativa</span></td>
                                        <td>Manutenção preventiva</td>
                                        <td>Philips Healthcare</td>
                                        <td>Anual</td>
                                        <td style="white-space: nowrap;">
                                            <a href="detalhes-garantia.php" class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-garantias.php"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Editar">

                                                <i class="fas fa-pen"></i>
                                            </a> 
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                data-bs-toggle="modal" data-bs-target="#modalEliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>GAR002</td>
                                        <td>Monitor IntelliVue MP5</td>
                                        <td>20/06/2019</td>
                                        <td>20/06/2024</td>
                                        <td><span class="badge bg-danger">Expirada</span></td>
                                        <td>Manutenção total</td>
                                        <td>Dräger Portugal</td>
                                        <td>Semestral</td>
                                        <td>
                                            <a href="detalhes-garantia.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-garantias.php"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Editar">

                                                <i class="fas fa-pen"></i>
                                            </a> 
                                            <!-- botao eliminar modal --> 
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                data-bs-toggle="modal" data-bs-target="#modalEliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr> 
                                    <tr>
                                        <td>GAR003</td>
                                        <td>Monitor IntelliVue MP5</td>
                                        <td>20/06/2019</td>
                                        <td>20/06/2024</td>
                                        <td><span class="badge bg-danger">Expirada</span></td>
                                        <td>Manutenção corretiva</td>
                                        <td>Zoll Medical</td>
                                        <td>Anual</td>
                                        <td>
                                            <a href="detalhes-garantia.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-garantias.php"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Editar">

                                                <i class="fas fa-pen"></i>
                                            </a> 
                                            <!-- botao eliminar modal --> 
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                data-bs-toggle="modal" data-bs-target="#modalEliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>GAR004</td>
                                        <td>Monitor IntelliVue MP5</td>
                                        <td>05/09/2020</td>
                                        <td>05/09/2025</td>
                                        <td><span class="badge bg-danger">Expirada</span></td>
                                        <td>Sem contrato</td>
                                        <td>—</td>
                                        <td>—</td>
                                        <td>
                                            <a href="detalhes-garantia.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-garantias.php"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Editar">

                                                <i class="fas fa-pen"></i>
                                            </a> 
                                            <!-- botao eliminar modal --> 
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                data-bs-toggle="modal" data-bs-target="#modalEliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>GAR005</td>
                                        <td>Monitor IntelliVue MP5</td>
                                        <td>10/11/2023</td>
                                        <td>10/11/2028</td>
                                        <td><span class="badge bg-success">Ativa</span></td>
                                        <td>Manutenção preventiva</td>
                                        <td>GE Healthcare</td>
                                        <td>Anual</td>
                                        <td>
                                            <a href="detalhes-garantia.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-garantias.php"
                                                class="btn btn-sm btn-outline-warning"
                                                title="Editar">

                                                <i class="fas fa-pen"></i>
                                            </a> 
                                            <!-- botao eliminar modal --> 
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                data-bs-toggle="modal" data-bs-target="#modalEliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
                    <button type="button" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Eliminar
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>
