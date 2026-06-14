<?php
// --------------------------------------------------------------------
// SEGURANÇA
// --------------------------------------------------------------------
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../includes/funcoes.php';

redirect_if_not_logged();

if (isset($_GET['eliminar'])) {
    $id = (int) $_GET['eliminar'];
    try {
        $ligacao = new PDO(
            "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
            MYSQL_USERNAME,
            MYSQL_PASSWORD
        );
        $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $ligacao->prepare("DELETE FROM localizacoes WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        // silencia o erro
    }
    header("Location: /MEDINV/private/views/localizacoes/localizacoes.php");
    exit;
}
?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

<?php
$erro = '';
$localizacoes = [];
$servicos  = [];
$edificios = [];
$pisos     = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Carrega opções dos selects
    $servicos  = $ligacao->query("SELECT id, nome FROM servicos ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);
    $edificios = $ligacao->query("SELECT DISTINCT edificio FROM localizacoes ORDER BY edificio")->fetchAll(PDO::FETCH_OBJ);
    $pisos     = $ligacao->query("SELECT DISTINCT piso FROM localizacoes ORDER BY piso")->fetchAll(PDO::FETCH_OBJ);

    $pesquisa = $_GET['pesquisa'] ?? '';
    $edificio = $_GET['edificio'] ?? '';
    $piso     = $_GET['piso']     ?? '';
    $servico  = $_GET['servico']  ?? '';
    $ordenar  = $_GET['ordenar']  ?? 'codigo_asc';

    $sql = "
        SELECT
            l.*,
            s.nome AS servico_nome,
            COUNT(DISTINCT e.id) AS total_equipamentos
        FROM localizacoes l
        LEFT JOIN servicos s ON l.servico_id = s.id
        LEFT JOIN equipamentos e ON e.localizacao_id = l.id
        WHERE 1 = 1
    ";

    $params = [];

    /* Pesquisa geral */
    if (!empty($pesquisa)) {
        $sql .= " AND (
            l.codigo   LIKE :pesquisa OR
            l.edificio LIKE :pesquisa OR
            l.piso     LIKE :pesquisa OR
            l.sala     LIKE :pesquisa OR
            s.nome     LIKE :pesquisa
        )";
        $params[':pesquisa'] = '%' . $pesquisa . '%';
    }

    /* Filtro por edifício */
    if (!empty($edificio)) {
        $sql .= " AND l.edificio = :edificio";
        $params[':edificio'] = $edificio;
    }

    /* Filtro por piso */
    if (!empty($piso)) {
        $sql .= " AND l.piso = :piso";
        $params[':piso'] = $piso;
    }

    /* Filtro por serviço */
    if (!empty($servico)) {
        $sql .= " AND l.servico_id = :servico";
        $params[':servico'] = $servico;
    }

    $sql .= " GROUP BY l.id";

    /* Ordenação */
    switch ($ordenar) {
        case 'codigo_desc':
            $sql .= " ORDER BY l.codigo DESC";
            break;
        case 'edificio_asc':
            $sql .= " ORDER BY l.edificio ASC, l.piso ASC";
            break;
        case 'edificio_desc':
            $sql .= " ORDER BY l.edificio DESC, l.piso DESC";
            break;
        case 'servico':
            $sql .= " ORDER BY s.nome ASC";
            break;
        case 'codigo_asc':
        default:
            $sql .= " ORDER BY l.codigo ASC";
            break;
    }

    $stmt = $ligacao->prepare($sql);
    $stmt->execute($params);
    $localizacoes = $stmt->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $e) {
    $erro = "Aconteceu um erro na ligação.";
}

$ligacao = null;
?>

<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>

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
                    <form method="GET" action="/MEDINV/private/views/localizacoes/localizacoes.php">

                        <!-- Pesquisa -->
                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        name="pesquisa"
                                        value="<?= htmlspecialchars($pesquisa) ?>"
                                        class="form-control"
                                        placeholder="Pesquisar localização...">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="/MEDINV/private/views/localizacoes/localizacoes.php"
                                        class="btn btn-outline-secondary"
                                        title="Limpar filtros">
                                        <i class="fas fa-filter-circle-xmark"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros adicionais -->
                        <div class="row g-2 align-items-center">

                            <div class="col-md-2">
                                <select class="form-select" name="edificio">
                                    <option value="">Edifício</option>
                                    <?php foreach ($edificios as $ed) : ?>
                                        <option value="<?= htmlspecialchars($ed->edificio) ?>"
                                            <?= $edificio == $ed->edificio ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($ed->edificio) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-2">
                                <select class="form-select" name="piso">
                                    <option value="">Piso</option>
                                    <?php foreach ($pisos as $p) : ?>
                                        <option value="<?= htmlspecialchars($p->piso) ?>"
                                            <?= $piso == $p->piso ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($p->piso) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select class="form-select" name="servico">
                                    <option value="">Serviço</option>
                                    <?php foreach ($servicos as $s) : ?>
                                        <option value="<?= $s->id ?>"
                                            <?= $servico == $s->id ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($s->nome) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select class="form-select" name="ordenar" onchange="this.form.submit()">
                                    <option value="codigo_asc"   <?= $ordenar == 'codigo_asc'   ? 'selected' : '' ?>>Código ↑</option>
                                    <option value="codigo_desc"  <?= $ordenar == 'codigo_desc'  ? 'selected' : '' ?>>Código ↓</option>
                                    <option value="edificio_asc" <?= $ordenar == 'edificio_asc' ? 'selected' : '' ?>>Edifício ↑</option>
                                    <option value="edificio_desc"<?= $ordenar == 'edificio_desc'? 'selected' : '' ?>>Edifício ↓</option>
                                    <option value="servico"      <?= $ordenar == 'servico'      ? 'selected' : '' ?>>Serviço</option>
                                </select>
                            </div>

                        </div>

                    </form>
                </div>
            </div>

            <!-- Erros / Resultados -->
            <?php if (!empty($erro)) : ?>
                <p class="text-center text-danger"><?= $erro ?></p>
            <?php else : ?>
                <?php if (count($localizacoes) == 0) : ?>
                    <p class="text-muted">Nenhuma localização registada.</p>
                <?php else : ?>

                    <!-- Barra de resultados -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="alert alert-info d-inline-block" role="alert">
                            <i class="fas fa-circle-info me-2"></i>
                            <strong><?= count($localizacoes) ?> resultados encontrados</strong>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead style="background:#f8f9fa;">
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
                                        <?php foreach ($localizacoes as $loc) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($loc->codigo) ?></td>
                                                <td><?= htmlspecialchars($loc->edificio) ?></td>
                                                <td><?= htmlspecialchars($loc->piso) ?></td>
                                                <td><?= htmlspecialchars($loc->servico_nome ?? '—') ?></td>
                                                <td><?= htmlspecialchars($loc->sala) ?></td>
                                                <td><?= (int) $loc->total_equipamentos ?></td>
                                                <td>
                                                    <div style="white-space: nowrap;">
                                                        <a href="detalhes-localizacoes.php?id=<?= $loc->id ?>"
                                                            class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="editar-localizacoes.php?id=<?= $loc->id ?>"
                                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEliminar"
                                                            data-id="<?= $loc->id ?>"
                                                            data-codigo="<?= htmlspecialchars($loc->codigo) ?>"
                                                            data-info="<?= htmlspecialchars('Edifício ' . $loc->edificio . ', Piso ' . $loc->piso . ', Sala ' . $loc->sala) ?>">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>
            <?php endif; ?>

        </main>
    </div>
</div>

<!-- Modal de confirmação -->
<div class="modal fade" id="modalEliminar" tabindex="-1"
     aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEliminarLabel">
                    <i class="fas fa-triangle-exclamation me-2"></i>Confirmar eliminação
                </h5>
                <button type="button" class="btn-close btn-close-white"
                        data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <p>Tem a certeza que pretende eliminar a seguinte localização?</p>
                <strong id="modalLocalizacaoInfo"></strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-1"></i>Cancelar
                </button>
                <a id="btnConfirmarEliminar" href="#" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i>Eliminar localização
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('modalEliminar')
    .addEventListener('show.bs.modal', function (e) {
        const btn    = e.relatedTarget;
        const id     = btn.dataset.id;
        const codigo = btn.dataset.codigo;
        const info   = btn.dataset.info;

        document.getElementById('modalLocalizacaoInfo').textContent =
            codigo + ' — ' + info;

        document.getElementById('btnConfirmarEliminar').href =
            '/MEDINV/private/views/localizacoes/localizacoes.php?eliminar=' + id;
    });
</script>

<?php include '../../includes/footer.php'; ?>