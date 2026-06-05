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
                        <h2 class="mb-0">Fornecedores</h2>
                        <p class="text-muted mb-0">Gere o inventário de fornecedores</p>
                    </div>
                    <a href="novo-fornecedor.html" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Novo fornecedor
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
                                            id="pesquisaFornecedor"
                                            class="form-control pe-5"
                                            placeholder="Pesquisar fornecedor..."
                                        >

                                        <button
                                            type="button"
                                            class="btn-limpar-pesquisa"
                                            onclick="document.getElementById('pesquisaFornecedor').value='';"
                                            title="Limpar pesquisa"
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
                                        <option value="">Tipo de fornecedor</option>
                                        <option>Fabricante</option>
                                        <option>Distribuidor / Fornecedor comercial</option>
                                        <option>Assistência técnica</option>
                                        <option>Fornecedor de consumíveis</option>
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
                        <div class="btn-group">
                            <button class="btn btn-sm btn-primary" id="btn-tabela-forn" title="Vista tabela">
                                <i class="fas fa-table"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-secondary" id="btn-cards-forn" title="Vista cards">
                                <i class="fas fa-grip"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabela de fornecedores -->
                <div id="vista-tabela-forn" class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Empresa</th>
                                        <th>Tipo</th>
                                        <th>Contacto</th>
                                        <th>Email</th>
                                        <th>Equipamentos associados</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <tr>
                                        <td>FOR001</td>
                                        <td>MedTech Portugal</td>
                                        <td>Assistência técnica</td>
                                        <td>225 456 700</td>
                                        <td>geral@medtech.pt</td> 
                                        <td>12</td>
                                        <td>
                                            <a href="detalhes-fornecedor.html"
                                            class="btn btn-sm btn-outline-primary"
                                            title="Ver detalhes">

                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="editar-fornecedor.html"
                                            class="btn btn-sm btn-outline-warning"
                                            title="Editar">

                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Eliminar"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEliminar">

                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>FOR002</td>
                                        <td>BioEquip Solutions</td>
                                        <td>Distribuidor</td>
                                        <td>229 887 654</td>
                                        <td>contacto@bioequip.pt</td>
                                        <td>8</td>
                                        <td>
                                            <a href="detalhes-fornecedor.html"
                                            class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="editar-fornecedor.html"
                                            class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEliminar">

                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>FOR003</td>
                                        <td>Hospital Devices Europe</td>
                                        <td>Fabricante</td>
                                        <td>226 345 222</td>
                                        <td>support@hde.pt</td>
                                        <td>15</td>
                                        <td>
                                            <a href="detalhes-fornecedor.html"
                                            class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            <a href="editar-fornecedor.html"
                                            class="btn btn-sm btn-outline-warning">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <button type="button"
                                                    class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalEliminar">

                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- Vista cards fornecedores -->
                <div id="vista-cards-forn" class="row g-3" style="display: none;">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="mb-0">MedTech Portugal</h6>
                                    <span class="badge bg-info text-dark">
                                        Assistência técnica
                                    </span>
                                </div>

                                <p class="text-muted small mb-1">
                                    <i class="fas fa-barcode me-1"></i>FOR001
                                </p>

                                <p class="text-muted small mb-1">
                                    <i class="fas fa-phone me-1"></i>225 456 700
                                </p>

                                <p class="text-muted small mb-1">
                                    <i class="fas fa-envelope me-1"></i>geral@medtech.pt
                                </p>

                                <p class="text-muted small mb-2">
                                    <i class="fas fa-stethoscope me-1"></i>12 equipamentos associados
                                </p>

                                <div class="d-flex gap-1">
                                    <a href="detalhes-fornecedor.html" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <a href="editar-fornecedor.html" class="btn btn-sm btn-outline-warning">
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
                    const btnTabelaForn = document.getElementById('btn-tabela-forn');
                    const btnCardsForn = document.getElementById('btn-cards-forn');
                    const vistaTabelaForn = document.getElementById('vista-tabela-forn');
                    const vistaCardsForn = document.getElementById('vista-cards-forn');

                    btnTabelaForn.addEventListener('click', function () {
                        vistaTabelaForn.style.display = 'block';
                        vistaCardsForn.style.display = 'none';
                        btnTabelaForn.classList.replace('btn-outline-secondary', 'btn-primary');
                        btnCardsForn.classList.replace('btn-primary', 'btn-outline-secondary');
                    });

                    btnCardsForn.addEventListener('click', function () {
                        vistaTabelaForn.style.display = 'none';
                        vistaCardsForn.style.display = 'flex';
                        btnCardsForn.classList.replace('btn-outline-secondary', 'btn-primary');
                        btnTabelaForn.classList.replace('btn-primary', 'btn-outline-secondary');
                    });
                </script>
            </main>
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
                        <p>Tem a certeza que pretende eliminar o seguinte fornecedor?</p>
                        <div>
                            <strong>FOR003 — Hospital Devices Europe</strong><br>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <!-- Cancela e fecha o modal -->
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-arrow-left me-1"></i>Cancelar
                        </button>
                        <!-- Confirma a eliminação -->
                        <a href="fornecedor.html" class="btn btn-danger">
                            <i class="fas fa-trash me-1"></i>Eliminar fornecedor
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>    

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>