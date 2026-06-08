<?php
require_once '../../includes/header.php';?>
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
                        <h2 class="mb-0">Localizações</h2>
                        <p class="text-muted mb-0">Gere a localização física dos equipamentos médicos</p>
                    </div>
                    <a href="novo-localizacoes.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Nova localização
                    </a>
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
                                            id="pesquisaLocalizacao"
                                            class="form-control pe-5"
                                            placeholder="Pesquisar localização..."
                                        >

                                        <button
                                            type="button"
                                            onclick="document.getElementById('pesquisaLocalizacao').value='';"
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
                                <div class="col-md-auto">
                                    <select class="form-select">
                                        <option value="">Edifício</option>
                                        <option>A</option>
                                        <option>B</option>
                                        <option>C</option>
                                    </select>
                                </div>
                                <div class="col-md-auto">
                                    <select class="form-select">
                                        <option value="">Piso</option>
                                        <option>1</option>
                                        <option>2</option>
                                        <option>3</option>
                                    </select>
                                </div>
                                <div class="col-md-auto">
                                    <select class="form-select">
                                        <option value="">Serviço</option>
                                        <option>UCI</option>
                                        <option>Urgência</option>
                                        <option>Bloco Operatório</option>
                                        <option>Medicina</option>
                                        <option>Pediatria</option>
                                        <option>Ortopedia</option>
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
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="alert alert-info d-inline-block" role="alert">
                        <i class="fas fa-circle-info me-2"></i>
                        <strong>5 resultados encontrados</strong>
                    </div>  
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>Ordenar: Código ↑</option>
                            <option>Ordenar: Código ↓</option>
                            <option>Ordenar: Nome ↑</option>
                            <option>Ordenar: Nome ↓</option>
                        </select>
                    </div>
                </div>
                <!-- Tabela de localizações -->
                <div id="vista-tabela-loc" class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Edifício</th>
                                        <th>Piso</th>
                                        <th>Serviço / Departamento</th>
                                        <th>Sala / Gabinete</th>
                                        <th>Equipamentos</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>LOC001</td>
                                        <td>A</td>
                                        <td>2</td>
                                        <td>UCI</td>
                                        <td>201</td>
                                        <td><span>12 equipamentos</span></td>
                                        <td>
                                            <a href="detalhes-localizacoes.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-localizacoes.php" class="btn btn-sm btn-outline-warning" title="Editar">
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
                                        <td>LOC002</td>
                                        <td>B</td>
                                        <td>1</td>
                                        <td>Urgência</td>
                                        <td>101</td>
                                        <td><span>10 equipamentos</span></td>
                                        <td>
                                            <a href="detalhes-localizacoes.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-localizacoes.php" class="btn btn-sm btn-outline-warning" title="Editar">
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
                                        <td>LOC003</td>
                                        <td>B</td>
                                        <td>3</td>
                                        <td>Bloco Operatório</td>
                                        <td>301</td>
                                        <td><span>8 equipamentos</span></td>
                                        <td>
                                            <a href="detalhes-localizacoes.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-localizacoes.php" class="btn btn-sm btn-outline-warning" title="Editar">
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
                                        <td>LOC004</td>
                                        <td>A</td>
                                        <td>2</td>
                                        <td>Medicina</td>
                                        <td>210</td>
                                        <td><span>7 equipamentos</span></td>
                                        <td>
                                            <a href="detalhes-localizacoes.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-localizacoes.php" class="btn btn-sm btn-outline-warning" title="Editar">
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
                                        <td>LOC005</td>
                                        <td>C</td>
                                        <td>1</td>
                                        <td>Pediatria</td>
                                        <td>105</td>
                                        <td><span>5 equipamentos</span></td>
                                        <td>
                                            <a href="detalhes-localizacoes.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-localizacoes.php" class="btn btn-sm btn-outline-warning" title="Editar">
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
                <!-- Vista cards localizações -->
                <div id="vista-cards-loc" class="row g-3" style="display: none;">

                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="mb-0">UCI - Sala 201</h6>
                                    <span class="badge bg-primary">LOC001</span>
                                </div>

                                <p class="text-muted small mb-1">
                                    <i class="fas fa-building me-1"></i>Edifício A
                                </p>

                                <p class="text-muted small mb-1">
                                    <i class="fas fa-layer-group me-1"></i>Piso 2
                                </p>

                                <p class="text-muted small mb-1">
                                    <i class="fas fa-hospital me-1"></i>Unidade de Cuidados Intensivos
                                </p>

                                <p class="text-muted small mb-2">
                                    <i class="fas fa-stethoscope me-1"></i>12 equipamentos associados
                                </p>

                                <div class="d-flex gap-1">
                                    <a href="detalhes-localizacoes.php" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="editar-localizacoes.php" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-pen"></i>
                                    </a>

                                    <button
                                        class="btn btn-sm btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    const btnTabelaLoc = document.getElementById('btn-tabela-loc');
                    const btnCardsLoc = document.getElementById('btn-cards-loc');
                    const vistaTabelaLoc = document.getElementById('vista-tabela-loc');
                    const vistaCardsLoc = document.getElementById('vista-cards-loc');

                    btnTabelaLoc.addEventListener('click', function () {
                        vistaTabelaLoc.style.display = 'block';
                        vistaCardsLoc.style.display = 'none';
                        btnTabelaLoc.classList.replace('btn-outline-secondary', 'btn-primary');
                        btnCardsLoc.classList.replace('btn-primary', 'btn-outline-secondary');
                    });

                    btnCardsLoc.addEventListener('click', function () {
                        vistaTabelaLoc.style.display = 'none';
                        vistaCardsLoc.style.display = 'flex';
                        btnCardsLoc.classList.replace('btn-outline-secondary', 'btn-primary');
                        btnTabelaLoc.classList.replace('btn-primary', 'btn-outline-secondary');
                    });
                </script>
            </main>
        </div>
    </div>
    <!-- Modal de confirmação -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarLabel">
                        <i class="fas fa-triangle-exclamation me-2"></i>Confirmar eliminação
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>

                <div class="modal-body">
                    <p>Tem a certeza que pretende eliminar a seguinte localização?</p>
                    <div>
                        <strong>LOC001 — Edifício A, Piso 2, Sala 201</strong>
                    </div>
                </div>

                <div class="modal-footer">
                    <!-- Cancela e fecha o modal -->
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-arrow-left me-1"></i>Cancelar
                    </button>
                    <!-- Confirma a eliminação -->
                    <a href="localizacoes.php" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Eliminar localização
                    </a>
                </div> 

            </div>
        </div>
    </div>
<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>
