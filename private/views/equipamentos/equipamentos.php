<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// --------------------------------------------------------------------

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../includes/funcoes.php';

redirect_if_not_logged();
?>

<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

<?php $erro = '';
$equipamentos = [];

 try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $equipamentos = $ligacao->query("
        SELECT e.*, s.nome AS servico_nome
        FROM equipamentos e
        LEFT JOIN localizacoes l ON e.localizacao_id = l.id
        LEFT JOIN servicos s ON l.servico_id = s.id
    ")->fetchAll(PDO::FETCH_OBJ);

    $erro = '';

} catch (PDOException $err) {
    $erro = "Aconteceu um erro na ligação: " . $err->getMessage();
    $equipamentos = [];
}

$ligacao = null;
?>

<div class="container-fluid">
    <div class="row">

        <!-- Offcanvas Sidebar -->
        <?php include '../../includes/sidebar.php'; ?>
        <!-- Conteúdo Principal -->
        <main class="col-12 p-4">
            <!-- Cabeçalho -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Equipamentos médicos</h2>
                    <p class="text-muted mb-0">Gere o inventário de dispositivos médicos</p>
                </div>
                <a href="novo-equipamentos.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Novo equipamento
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
                                        id="pesquisaEquipamento"
                                        class="form-control pe-5"
                                        placeholder="Pesquisar equipamento...">

                                    <button
                                        type="button"
                                        onclick="document.getElementById('pesquisaEquipamento').value='';"
                                        title="Limpar pesquisa"
                                        class="btn-limpar-pesquisa">
                                        <i class="fas fa-xmark"></i>
                                    </button>
                                </div>

                                <button
                                    class="btn btn-primary"
                                    type="button"
                                    onclick="filtrar()">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-md-2">
                                <select class="form-select">
                                    <option value="">Categoria</option>
                                    <option>Monitorização</option>
                                    <option>Suporte de vida</option>
                                    <option>Terapia</option>
                                    <option>Diagnóstico</option>
                                    <option>Laboratório</option>
                                    <option>Esterilização</option>
                                    <option>Reabilitação</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select">
                                    <option value="">Estado</option>
                                    <option>Ativo</option>
                                    <option>Em manutenção</option>
                                    <option>Inativo</option>
                                    <option>Em calibração</option>
                                    <option>Em quarentena</option>
                                    <option>Abatido</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select">
                                    <option value="">Criticidade</option>
                                    <option>Baixa</option>
                                    <option>Média</option>
                                    <option>Alta</option>
                                    <option>Suporte de vida</option>
                                </select>
                            </div>
                            <div class="col-md-2">
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
                            <div class="col-md-2">
                                <select class="form-select">
                                    <option value="">Fornecedor</option>
                                    <option>Philips Healthcare</option>
                                    <option>Dräger Portugal</option>
                                    <option>Zoll Medical</option>
                                    <option>B. Braun</option>
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

            <!-- Barra de resultados e toggle de vista -->
            <div class="d-flex justify-content-between align-items-center mb-3">                
                <div class="d-flex gap-2">
                    <select class="form-select form-select-sm" style="width: auto;">
                        <option>Ordenar: Código ↑</option>
                        <option>Ordenar: Código ↓</option>
                        <option>Ordenar: Designação ↑</option>
                        <option>Ordenar: Estado</option>
                        <option>Ordenar: Criticidade</option>
                    </select>
                </div>
            </div>

            <!-- Verifica se houve erro na ligação ou query.  -->
            <?php if (!empty($erro)) : ?>
                <!-- Mostra a mensagem de erro definida no catch. -->
                <p class="text-center text-danger"><?= $erro ?></p>
            <?php else : ?>
                <!-- Se não há erro mas o array está vazio, mostra "Não existem clientes registados."-->
                <?php if (count($equipamentos) == 0) : ?>
                    <p class="text-muted">Nenhum equipamento registado.</p>
                <!-- Se existem resultados, será mostrada a tabela de equipamentos (a ser colocada logo a seguir). -->    
                <?php else : ?>

                    <!-- Barra de resultados -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="alert alert-info d-inline-block" role="alert">
                            <i class="fas fa-circle-info me-2"></i>
                            <strong><?= count($equipamentos) ?> resultados encontrados</strong>
                        </div>
                    </div>

                    <!-- Vista tabela -->
                    <div id="vista-tabela">
                        <div class="card">
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0" id="tabela-equipamentos">
                                        <thead style="background:#f8f9fa;">
                                            <tr>
                                                <th>Código</th>
                                                <th>Designação</th>
                                                <th>Marca</th>
                                                <th>Categoria</th>
                                                <th>Serviço</th>
                                                <th>Estado</th>
                                                <th>Criticidade</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Inicia o ciclo foreach, percorrendo cada equipamento do array $resultados obtido da base de dados.  -->
                                            <?php foreach ($equipamentos as $eq) : ?>
                                                <tr>
                                                    <!-- Mostra o campo nome do cliente atual -->
                                                    <td><?= htmlspecialchars($eq->codigo) ?></td>
                                                    <td><?= htmlspecialchars($eq->designacao) ?></td>
                                                    <td><?= htmlspecialchars($eq->marca) ?></td>
                                                    <td><?= htmlspecialchars($eq->categoria) ?></td>
                                                    <td><?= htmlspecialchars($eq->servico_nome ?? '—') ?></td>
                                                    <td>
                                                        <?php
                                                        $badgeEstado = match($eq->estado) {
                                                            'ativo' => 'success',
                                                            'em_manutencao' => 'warning',
                                                            'inativo', 'abatido' => 'secondary',
                                                            'em_calibracao', 'em_quarentena' => 'info',
                                                            default => 'secondary'
                                                        };
                                                        ?>
                                                        <span class="badge bg-<?= $badgeEstado ?>">
                                                            <?= htmlspecialchars($eq->estado) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $badgeCrit = match($eq->criticidade) {
                                                            'suporte_de_vida' => 'danger',
                                                            'alta' => 'warning',
                                                            'media' => 'info',
                                                            'baixa' => 'secondary',
                                                            default => 'secondary'
                                                        };
                                                        ?>
                                                        <span class="badge bg-<?= $badgeCrit ?>">
                                                            <?= htmlspecialchars($eq->criticidade) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div style="white-space: nowrap;">
                                                            <a href="detalhes-equipamentos.php?id=<?= $eq->id ?>"
                                                                class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <a href="editar-equipamentos.php?id=<?= $eq->id ?>"
                                                                class="btn btn-sm btn-outline-warning" title="Editar">
                                                                <i class="fas fa-pen"></i>
                                                            </a>
                                                            <button class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#modalEliminar"
                                                                data-id="<?= $eq->id ?>"
                                                                data-codigo="<?= htmlspecialchars($eq->codigo) ?>"
                                                                data-designacao="<?= htmlspecialchars($eq->designacao) ?>">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <!--Termina o ciclo foreach. -->
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?> <!-- Fecha o if (count($resultados) == 0) -->
            <?php endif; ?> <!-- Fecha o if (!empty($erro)) --> 

            <!-- Vista cards 
                <div id="vista-cards" class="row g-3" style="display: none;">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">EQ001</span>
                                    <span class="badge bg-success">Ativo</span>
                                </div>
                                <h6>Monitor IntelliVue MP5</h6>
                                <p class="text-muted small mb-1"><i class="fas fa-tag me-1"></i>Philips — Monitorização</p>
                                <p class="text-muted small mb-1"><i class="fas fa-location-dot me-1"></i>UCI — Sala 201</p>
                                <p class="text-muted small mb-2"><span class="badge bg-danger">Suporte de vida</span></p>
                                <div class="d-flex gap-1">
                                    <a href="detalhes-equipamentos.php" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="editar-equipamentos.php" class="btn btn-sm btn-outline-warning"><i class="fas fa-pen"></i></a>
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">EQ002</span>
                                    <span class="badge bg-success">Ativo</span>
                                </div>
                                <h6>Ventilador Evita V500</h6>
                                <p class="text-muted small mb-1"><i class="fas fa-tag me-1"></i>Dräger — Suporte de vida</p>
                                <p class="text-muted small mb-1"><i class="fas fa-location-dot me-1"></i>UCI — Sala 201</p>
                                <p class="text-muted small mb-2"><span class="badge bg-danger">Suporte de vida</span></p>
                                <div class="d-flex gap-1">
                                    <a href="detalhes-equipamentos.php" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="editar-equipamentos.php" class="btn btn-sm btn-outline-warning"><i class="fas fa-pen"></i></a>
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">EQ003</span>
                                    <span class="badge bg-warning text-dark">Em manutenção</span>
                                </div>
                                <h6>Desfibrilhador R Series</h6>
                                <p class="text-muted small mb-1"><i class="fas fa-tag me-1"></i>Zoll — Suporte de vida</p>
                                <p class="text-muted small mb-1"><i class="fas fa-location-dot me-1"></i>Urgência — Sala 101</p>
                                <p class="text-muted small mb-2"><span class="badge bg-danger">Alta</span></p>
                                <div class="d-flex gap-1">
                                    <a href="detalhes-equipamentos.php" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                                    <a href="editar-equipamentos.php" class="btn btn-sm btn-outline-warning"><i class="fas fa-pen"></i></a>
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

            <!-- JavaScript toggle de vista -->
            <script>
                const btnTabela = document.getElementById('btn-tabela');
                const btnCards = document.getElementById('btn-cards');
                const vistaTabela = document.getElementById('vista-tabela');
                const vistaCards = document.getElementById('vista-cards');

                btnTabela.addEventListener('click', function() {
                    vistaTabela.style.display = 'block';
                    vistaCards.style.display = 'none';
                    btnTabela.classList.replace('btn-outline-secondary', 'btn-primary');
                    btnCards.classList.replace('btn-primary', 'btn-outline-secondary');
                });

                btnCards.addEventListener('click', function() {
                    vistaTabela.style.display = 'none';
                    vistaCards.style.display = 'flex';
                    btnCards.classList.replace('btn-outline-secondary', 'btn-primary');
                    btnTabela.classList.replace('btn-primary', 'btn-outline-secondary');
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
                <p>Tem a certeza que pretende eliminar o seguinte equipamento?</p>
                <div>
                    <strong>EQ001 — Monitor IntelliVue MP5</strong><br>
                </div>
            </div>

            <div class="modal-footer">
                <!-- Cancela e fecha o modal -->
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-1"></i>Cancelar
                </button>
                <!-- Confirma a eliminação -->
                <a href="equipamentos.php" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i>Eliminar equipamento
                </a>
            </div>

        </div>
    </div>
</div>
<!-- rodape -->
<?php include '../../includes/footer.php'; ?>