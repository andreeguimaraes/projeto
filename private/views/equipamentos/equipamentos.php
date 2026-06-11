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

    $pesquisa = $_GET['pesquisa'] ?? '';
    $categoria = $_GET['categoria'] ?? '';
    $estado = $_GET['estado'] ?? '';
    $criticidade = $_GET['criticidade'] ?? '';
    $servico = $_GET['servico'] ?? '';
    $fornecedor = $_GET['fornecedor'] ?? '';
    $ordenar = $_GET['ordenar'] ?? 'codigo_asc';

    $sql = "
        SELECT DISTINCT
            e.*,
            c.nome AS categoria_nome,
            s.nome AS servico_nome
        FROM equipamentos e
        LEFT JOIN categorias_equipamento c ON e.categoria_id = c.id
        LEFT JOIN localizacoes l ON e.localizacao_id = l.id
        LEFT JOIN servicos s ON l.servico_id = s.id
        LEFT JOIN equipamento_fornecedor ef ON e.id = ef.equipamento_id
        LEFT JOIN fornecedores f ON ef.fornecedor_id = f.id
        WHERE 1 = 1
    ";

    $params = [];

    /* Pesquisa geral */
    if (!empty($pesquisa)) {
        $sql .= " AND (
            e.codigo LIKE :pesquisa OR
            e.designacao LIKE :pesquisa OR
            e.marca LIKE :pesquisa OR
            e.modelo LIKE :pesquisa OR
            e.numero_serie LIKE :pesquisa OR
            c.nome LIKE :pesquisa OR
            s.nome LIKE :pesquisa OR
            f.nome LIKE :pesquisa
        )";

        $params[':pesquisa'] = '%' . $pesquisa . '%';
    }

    /* Filtro por categoria */
    if (!empty($categoria)) {
        $sql .= " AND e.categoria_id = :categoria";
        $params[':categoria'] = $categoria;
    }

    /* Filtro por estado */
    if (!empty($estado)) {
        $sql .= " AND e.estado = :estado";
        $params[':estado'] = $estado;
    }

    /* Filtro por criticidade */
    if (!empty($criticidade)) {
        $sql .= " AND e.criticidade = :criticidade";
        $params[':criticidade'] = $criticidade;
    }

    /* Filtro por serviço */
    if (!empty($servico)) {
        $sql .= " AND s.id = :servico";
        $params[':servico'] = $servico;
    }

    /* Filtro por fornecedor */
    if (!empty($fornecedor)) {
        $sql .= " AND f.id = :fornecedor";
        $params[':fornecedor'] = $fornecedor;
    }

    /* Ordenação */
    switch ($ordenar) {
        case 'codigo_desc':
            $sql .= " ORDER BY e.codigo DESC";
            break;

        case 'designacao_asc':
            $sql .= " ORDER BY e.designacao ASC";
            break;

        case 'designacao_desc':
            $sql .= " ORDER BY e.designacao DESC";
            break;

        case 'estado':
            $sql .= " ORDER BY e.estado ASC";
            break;

        case 'criticidade':
            $sql .= " ORDER BY e.criticidade ASC";
            break;

        case 'codigo_asc':
        default:
            $sql .= " ORDER BY e.codigo ASC";
            break;
    }

    $stmt = $ligacao->prepare($sql);
    $stmt->execute($params);
    $equipamentos = $stmt->fetchAll(PDO::FETCH_OBJ);

    $erro = '';
} catch (PDOException $e) {
    echo "Aconteceu um erro na ligação.";
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

                    <form method="GET" action="equipamentos.php">

                        <!-- Pesquisa -->
                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        id="pesquisaEquipamento"
                                        name="pesquisa"
                                        value="<?= htmlspecialchars($pesquisa) ?>"
                                        class="form-control"
                                        placeholder="Pesquisar equipamento...">

                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>

                                    <a href="equipamentos.php"
                                        class="btn btn-outline-secondary"
                                        title="Limpar filtros">
                                        <i class="fas fa-filter-circle-xmark"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros -->
                        <div class="row g-2 align-items-center">

                            <div class="col-md-2">
                                <select class="form-select" name="categoria">
                                    <option value="">Categoria</option>
                                    <option value="1" <?= $categoria == '1' ? 'selected' : '' ?>>Monitorização</option>
                                    <option value="2" <?= $categoria == '2' ? 'selected' : '' ?>>Suporte de vida</option>
                                    <option value="3" <?= $categoria == '3' ? 'selected' : '' ?>>Diagnóstico</option>
                                    <option value="4" <?= $categoria == '4' ? 'selected' : '' ?>>Terapia</option>
                                    <option value="5" <?= $categoria == '5' ? 'selected' : '' ?>>Laboratório</option>
                                    <option value="6" <?= $categoria == '6' ? 'selected' : '' ?>>Esterilização</option>
                                    <option value="7" <?= $categoria == '7' ? 'selected' : '' ?>>Reabilitação</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select class="form-select" name="estado">
                                    <option value="">Estado</option>
                                    <option value="ativo" <?= $estado == 'ativo' ? 'selected' : '' ?>>Ativo</option>
                                    <option value="em_manutencao" <?= $estado == 'em_manutencao' ? 'selected' : '' ?>>Em manutenção</option>
                                    <option value="inativo" <?= $estado == 'inativo' ? 'selected' : '' ?>>Inativo</option>
                                    <option value="em_calibracao" <?= $estado == 'em_calibracao' ? 'selected' : '' ?>>Em calibração</option>
                                    <option value="em_quarentena" <?= $estado == 'em_quarentena' ? 'selected' : '' ?>>Em quarentena</option>
                                    <option value="abatido" <?= $estado == 'abatido' ? 'selected' : '' ?>>Abatido</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select class="form-select" name="criticidade">
                                    <option value="">Criticidade</option>
                                    <option value="baixa" <?= $criticidade == 'baixa' ? 'selected' : '' ?>>Baixa</option>
                                    <option value="media" <?= $criticidade == 'media' ? 'selected' : '' ?>>Média</option>
                                    <option value="alta" <?= $criticidade == 'alta' ? 'selected' : '' ?>>Alta</option>
                                    <option value="suporte_de_vida" <?= $criticidade == 'suporte_de_vida' ? 'selected' : '' ?>>Suporte de vida</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select class="form-select" name="servico">
                                    <option value="">Serviço</option>
                                    <option value="1" <?= $servico == '1' ? 'selected' : '' ?>>UCI</option>
                                    <option value="2" <?= $servico == '2' ? 'selected' : '' ?>>Urgência</option>
                                    <option value="3" <?= $servico == '3' ? 'selected' : '' ?>>Bloco Operatório</option>
                                    <option value="4" <?= $servico == '4' ? 'selected' : '' ?>>Medicina</option>
                                    <option value="5" <?= $servico == '5' ? 'selected' : '' ?>>Pediatria</option>
                                    <option value="6" <?= $servico == '6' ? 'selected' : '' ?>>Ortopedia</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select class="form-select" name="fornecedor">
                                    <option value="">Fornecedor</option>
                                    <option value="1" <?= $fornecedor == '1' ? 'selected' : '' ?>>Philips Healthcare Portugal</option>
                                    <option value="2" <?= $fornecedor == '2' ? 'selected' : '' ?>>Dräger Medical Portugal</option>
                                    <option value="3" <?= $fornecedor == '3' ? 'selected' : '' ?>>GE Healthcare Portugal</option>
                                    <option value="4" <?= $fornecedor == '4' ? 'selected' : '' ?>>Siemens Healthineers Portugal</option>
                                    <option value="5" <?= $fornecedor == '5' ? 'selected' : '' ?>>B. Braun Medical Portugal</option>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select
                                    class="form-select"
                                    name="ordenar"
                                    onchange="this.form.submit()">

                                    <option value="codigo_asc" <?= $ordenar == 'codigo_asc' ? 'selected' : '' ?>>Código ↑</option>
                                    <option value="codigo_desc" <?= $ordenar == 'codigo_desc' ? 'selected' : '' ?>>Código ↓</option>
                                    <option value="designacao_asc" <?= $ordenar == 'designacao_asc' ? 'selected' : '' ?>>Designação ↑</option>
                                    <option value="designacao_desc" <?= $ordenar == 'designacao_desc' ? 'selected' : '' ?>>Designação ↓</option>
                                    <option value="estado" <?= $ordenar == 'estado' ? 'selected' : '' ?>>Estado</option>
                                    <option value="criticidade" <?= $ordenar == 'criticidade' ? 'selected' : '' ?>>Criticidade</option>
                                </select>
                            </div>

                        </div>

                    </form>

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
                                                    <td><?= htmlspecialchars($eq->categoria_nome ?? '—') ?></td>
                                                    <td><?= htmlspecialchars($eq->servico_nome ?? '—') ?></td>
                                                    <td>
                                                        <?php
                                                        $badgeEstado = match ($eq->estado) {
                                                            'ativo' => 'success',
                                                            'em_manutencao' => 'warning',
                                                            'inativo', 'abatido' => 'secondary',
                                                            'em_calibracao', 'em_quarentena' => 'info',
                                                            default => 'secondary'
                                                        };

                                                        $textoEstado = match ($eq->estado) {
                                                            'ativo' => 'Ativo',
                                                            'em_manutencao' => 'Em manutenção',
                                                            'inativo' => 'Inativo',
                                                            'em_calibracao' => 'Em calibração',
                                                            'em_quarentena' => 'Em quarentena',
                                                            'abatido' => 'Abatido',
                                                            default => $eq->estado
                                                        };
                                                        ?>

                                                        <span class="badge bg-<?= $badgeEstado ?>">
                                                            <?= htmlspecialchars($textoEstado) ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $badgeCrit = match ($eq->criticidade) {
                                                            'suporte_de_vida' => 'danger',
                                                            'alta' => 'warning',
                                                            'media' => 'info',
                                                            'baixa' => 'secondary',
                                                            default => 'secondary'
                                                        };

                                                        $textoCrit = match ($eq->criticidade) {
                                                            'suporte_de_vida' => 'Suporte de vida',
                                                            'alta' => 'Alta',
                                                            'media' => 'Média',
                                                            'baixa' => 'Baixa',
                                                            default => $eq->criticidade
                                                        };
                                                        ?>

                                                        <span class="badge bg-<?= $badgeCrit ?>">
                                                            <?= htmlspecialchars($textoCrit) ?>
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