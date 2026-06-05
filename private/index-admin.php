<!-- cabeçalho -->
<?php include 'includes/header.php'; ?> 
    <!-- navbar -->
    <?php include 'includes/nav.php'; ?> 

    <div class="container-fluid">
        <div class="row">

            <!-- sidebar -->
            <?php include 'includes/sidebar.php'; ?> 


            <!-- Conteúdo Principal -->
            <main class="col-12 p-4">

                <!-- Boas vindas -->
                <div class="boas-vindas-card mb-4">
                    <div class="d-flex align-items-center gap-4">
                            <div>
                            <h4 class="mb-1">Bem-vindo, Administrador(a)</h4>
                            <p class="mb-1 text-muted">
                                <i class="fas fa-envelope me-1"></i>admin@medinv.pt
                            </p>
                            <p class="mb-0 text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Último acesso: 17/05/2026 às 14:32
                            </p>
                        </div>
                        <div class="ms-auto text-end">
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>Online
                            </span>
                            <p class="text-muted mt-2 mb-0" style="font-size: 0.85rem;">
                                17 de maio de 2026
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Indicadores -->
                <h5 class="mb-3 text-muted">
                    <i class="fas fa-chart-bar me-2"></i>Resumo do inventário
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-hospital-user fa-2x mb-2 text-primary"></i>
                                <h3 class="mb-0">47</h3>
                                <p class="text-muted mb-0">Total de equipamentos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-circle-check fa-2x mb-2 text-success"></i>
                                <h3 class="mb-0">38</h3>
                                <p class="text-muted mb-0">Ativos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-wrench fa-2x mb-2 text-warning"></i>
                                <h3 class="mb-0">5</h3>
                                <p class="text-muted mb-0">Em manutenção</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-triangle-exclamation fa-2x mb-2 text-danger"></i>
                                <h3 class="mb-0">4</h3>
                                <p class="text-muted mb-0">Garantias expiradas</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Alertas -->
                <h5 class="mb-3 text-muted">
                    <i class="fas fa-bell me-2"></i>Alertas
                </h5>
                <div class="alert alert-danger d-flex align-items-center gap-2">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span>4 equipamentos com garantia expirada — 
                        <a href="views/equipamentos/equipamentos.html" class="alert-link">ver lista</a>
                    </span>
                </div>
                <div class="alert alert-warning d-flex align-items-center gap-2">
                    <i class="fas fa-clock"></i>
                    <span>3 garantias a expirar nos próximos 30 dias — 
                        <a href="views/garantias/garantias.html" class="alert-link">ver lista</a>
                    </span>
                </div>
                <div class="alert alert-info d-flex align-items-center gap-2">
                    <i class="fas fa-file-circle-exclamation"></i>
                    <span>2 equipamentos sem documentação associada — 
                        <a href="views/documentacao/documentacao.html" class="alert-link">ver lista</a>
                    </span>
                </div>
            </main>
        </div> 
    </div>
<!-- rodapé -->
<?php include 'includes/footer.php'; ?> 