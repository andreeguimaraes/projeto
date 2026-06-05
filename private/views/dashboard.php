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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-0">Dashboard</h2>
                        <p class="text-muted mb-0">Visão geral do parque tecnológico hospitalar</p>
                    </div>
                </div>

                <!-- INDICADORES MÍNIMOS OBRIGATÓRIOS -->
                <h5 class="mb-3 text-muted">
                    <i class="fas fa-chart-bar me-2"></i>Indicadores gerais
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-hospital-user fa-2x mb-2 text-primary"></i>
                                <h3 class="mb-0">47</h3>
                                <p class="text-muted mb-0 small">Total</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-circle-check fa-2x mb-2 text-success"></i>
                                <h3 class="mb-0">38</h3>
                                <p class="text-muted mb-0 small">Ativos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-wrench fa-2x mb-2 text-warning"></i>
                                <h3 class="mb-0">5</h3>
                                <p class="text-muted mb-0 small">Em manutenção</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-circle-xmark fa-2x mb-2 text-secondary"></i>
                                <h3 class="mb-0">4</h3>
                                <p class="text-muted mb-0 small">Inativos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-file-circle-xmark fa-2x mb-2 text-danger"></i>
                                <h3 class="mb-0">4</h3>
                                <p class="text-muted mb-0 small">Garantia expirada</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="card indicador-card text-center">
                            <div class="card-body">
                                <i class="fas fa-folder-open fa-2x mb-2 text-info"></i>
                                <h3 class="mb-0">2</h3>
                                <p class="text-muted mb-0 small">Sem documentação</p>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- GRÁFICOS -->
                <div class="row g-3 mb-4">
                    <!-- Gráfico 1 — Estado dos equipamentos -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fas fa-circle-half-stroke me-2"></i>Estado dos equipamentos
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <canvas id="graficoEstados"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- Gráfico 2 — Equipamentos por categoria -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fas fa-tags me-2"></i>Equipamentos por categoria
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <canvas id="graficoCategorias"></canvas>
                            </div>
                        </div>
                    </div>
                    <!-- Gráfico 3 — Criticidade -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fas fa-triangle-exclamation me-2"></i>Criticidade clínica
                            </div>
                            <div class="card-body d-flex align-items-center justify-content-center">
                                <canvas id="graficoCriticidade"></canvas>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Gráfico 4 — Equipamentos por serviço (barra) -->
                <div class="row g-3 mb-4">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-location-dot me-2"></i>Equipamentos por serviço
                            </div>
                            <div class="card-body">
                                <canvas id="graficoServicos"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- INDICADORES ADICIONAIS VALORIZADOS -->
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-header">
                                <i class="fas fa-heart-pulse me-2"></i>Suporte de vida por serviço
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Serviço</th>
                                            <th class="text-center">Equipamentos</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>UCI</td>
                                            <td class="text-center"><span>8</span></td>
                                        </tr>
                                        <tr>
                                            <td>Urgência</td>
                                            <td class="text-center"><span>5</span></td>
                                        </tr>
                                        <tr>
                                            <td>Bloco Operatório</td>
                                            <td class="text-center"><span>3</span></td>
                                        </tr>
                                        <tr>
                                            <td>Medicina</td>
                                            <td class="text-center"><span>2</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabela — Garantias a expirar -->
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header text-warning">
                                <i class="fas fa-clock me-2"></i>Garantias a expirar nos próximos 30 dias
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Equipamento</th>
                                            <th>Serviço</th>
                                            <th>Expira em</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Ventilador Dräger</td>
                                            <td>UCI</td>
                                            <td><span class="badge bg-warning">5 dias</span></td>
                                        </tr>
                                        <tr>
                                            <td>Desfibrilhador Zoll</td>
                                            <td>Urgência</td>
                                            <td><span class="badge bg-warning">12 dias</span></td>
                                        </tr>
                                        <tr>
                                            <td>Bomba de infusão</td>
                                            <td>Medicina</td>
                                            <td><span class="badge bg-warning">28 dias</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Tabela — Criticidade elevada -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header text-danger">
                                <i class="fas fa-triangle-exclamation me-2"></i>Equipamentos de criticidade elevada
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Equipamento</th>
                                            <th>Estado</th>
                                            <th>Criticidade</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Ventilador Evita V500</td>
                                            <td><span>Ativo</span></td>
                                            <td><span class="badge bg-danger">Suporte de vida</span></td>
                                        </tr>
                                        <tr>
                                            <td>Desfibrilhador R Series</td>
                                            <td><span>Ativo</span></td>
                                            <td><span class="badge bg-danger">Suporte de vida</span></td>
                                        </tr>
                                        <tr>
                                            <td>Monitor IntelliVue MP5</td>
                                            <td><span>Em manutenção</span></td>
                                            <td><span class="badge bg-danger">Alta</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </main>
        </div>
    </div>

    <!-- GRÁFICOS CHART.JS -->
    <script>
        /* Gráfico 1 — Estado dos equipamentos (pizza) */
        new Chart(document.getElementById('graficoEstados'), {
            type: 'pie',
            data: {
                labels: ['Ativos', 'Em manutenção', 'Inativos'],
                datasets: [{
                    data: [38, 5, 4],
                    backgroundColor: ['#1d5c7f', '#f0ad4e', '#6c757d']
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        /* Gráfico 2 — Equipamentos por categoria (donut) */
        new Chart(document.getElementById('graficoCategorias'), {
            type: 'doughnut',
            data: {
                labels: ['Monitorização', 'Suporte de vida', 'Terapia', 'Diagnóstico', 'Laboratório'],
                datasets: [{
                    data: [15, 12, 10, 6, 4],
                    backgroundColor: ['#1d5c7f', '#d9534f', '#f0ad4e', '#5cb85c', '#9b59b6']
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        /* Gráfico 3 — Criticidade clínica (pizza) */
        new Chart(document.getElementById('graficoCriticidade'), {
            type: 'pie',
            data: {
                labels: ['Suporte de vida', 'Alta', 'Média', 'Baixa'],
                datasets: [{
                    data: [13, 15, 12, 7],
                    backgroundColor: ['#d9534f', '#f0ad4e', '#1d5c7f', '#5cb85c']
                }]
            },
            options: {
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });

        /* Gráfico 4 — Equipamentos por serviço (barras) */
        new Chart(document.getElementById('graficoServicos'), {
            type: 'bar',
            data: {
                labels: ['UCI', 'Urgência', 'Bloco Operatório', 'Medicina', 'Pediatria', 'Ortopedia'],
                datasets: [{
                    label: 'Equipamentos',
                    data: [12, 10, 8, 7, 5, 5],
                    backgroundColor: '#1d5c7f',
                    borderRadius: 6
                }]
            },
            options: {
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    </script>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>
