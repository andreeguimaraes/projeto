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
                        <h2 class="mb-0">Documentação</h2>
                        <p class="text-muted mb-0">Gestão de documentos associados a equipamentos e fornecedores</p>
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
                                            id="pesquisaDocumentacao"
                                            class="form-control pe-5"
                                            placeholder="Pesquisar documento..."
                                        >

                                        <button
                                            type="button"
                                            onclick="document.getElementById('pesquisaDocumentacao').value='';"
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
                                        <option value="">Tipo de documento</option>
                                        <option>Manual de utilizador</option>
                                        <option>Manual de serviço</option>
                                        <option>Certificado de calibração</option>
                                        <option>Contrato de manutenção</option>
                                        <option>Fatura de aquisição</option>
                                        <option>Declaração de conformidade</option>
                                        <option>Relatório técnico</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select class="form-select">
                                        <option value="">Equipamento</option>
                                        <option>EQ001 — Monitor IntelliVue MP5</option>
                                        <option>EQ002 — Ventilador Evita V500</option>
                                        <option>EQ003 — Desfibrilhador R Series</option>
                                        <option>EQ004 — Bomba de infusão</option>
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

                <!-- Tabela de documentos -->
                <div class="card">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Código</th>
                                        <th>Documento</th>
                                        <th>Equipamento</th>
                                        <th>Tipo</th>                                    
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>DOC001</td>
                                        <td>Manual IntelliVue MP5</td>
                                        <td>EQ001 — Monitor MP5</td>
                                        <td><span class="badge bg-secondary">Manual utilizador</span></td>
                                        <td>                               
                                            <a href="detalhes-documentacao.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-documentacao.php" class="btn btn-sm btn-outline-warning" title="Editar">
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
                                        <td>DOC002</td>
                                        <td>Calibração Monitor MP5 2026</td>
                                        <td>EQ001 — Monitor MP5</td>
                                        <td><span class="badge bg-secondary">Cert. calibração</span></td>                                 
                                        <td>
                                            <a href="detalhes-documentacao.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-documentacao.php" class="btn btn-sm btn-outline-warning" title="Editar">
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
                                        <td>DOC003</td>
                                        <td>Contrato Philips 2026</td>
                                        <td>EQ002 — Ventilador V500</td>
                                        <td><span class="badge bg-secondary">Contrato manutenção</span></td>
                                        <td>
                                            <a href="detalhes-documentacao.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-documentacao.php" class="btn btn-sm btn-outline-warning" title="Editar">
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
                                        <td>DOC004</td>
                                        <td>CE Mark Desfibrilhador Zoll</td>
                                        <td>EQ003 — Desfibrilhador</td>
                                        <td><span class="badge bg-secondary">Declaração conformidade</span></td>
                                        <td>
                                            <a href="detalhes-documentacao.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-documentacao.php" class="btn btn-sm btn-outline-warning" title="Editar">
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
                                        <td>DOC005</td>
                                        <td>Fatura Bomba Infusão B.Braun</td>
                                        <td>EQ004 — Bomba de infusão</td>
                                        <td><span class="badge bg-secondary">Fatura aquisição</span></td>                                 
                                        <td>
                                            <a href="detalhes-documentacao.php" class="btn btn-sm btn-outline-primary"
                                                title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="editar-documentacao.php" class="btn btn-sm btn-outline-warning" title="Editar">
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
                    <p>Tem a certeza que pretende eliminar este documento?</p>
                    <div class="alert alert-light border">
                        <strong>Manual IntelliVue MP5</strong><br>
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
