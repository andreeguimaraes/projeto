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
        $stmt = $ligacao->prepare("DELETE FROM fornecedores WHERE id = :id");
        $stmt->execute([':id' => $id]);
    } catch (PDOException $e) {
        // silencia o erro
    }
    header("Location: fornecedor.php");
    exit;
}
?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

<?php
$erro = '';
$fornecedores = [];
$tipos = [];

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Carrega os tipos de fornecedor para o select do filtro
    $tipos = $ligacao->query("SELECT id, nome FROM tipos_fornecedor ORDER BY nome")->fetchAll(PDO::FETCH_OBJ);

    $pesquisa = $_GET['pesquisa'] ?? '';
    $tipo     = $_GET['tipo']     ?? '';
    $ordenar  = $_GET['ordenar']  ?? 'codigo_asc';

    $sql = "
        SELECT
            f.*,
            tf.nome AS tipo_nome,
            COUNT(DISTINCT ef.equipamento_id) AS total_equipamentos
        FROM fornecedores f
        LEFT JOIN tipos_fornecedor tf ON f.tipo_id = tf.id
        LEFT JOIN equipamento_fornecedor ef ON f.id = ef.fornecedor_id
        WHERE 1 = 1
    ";

    $params = [];

    /* Pesquisa geral */
    if (!empty($pesquisa)) {
        $sql .= " AND (
            f.codigo           LIKE :pesquisa OR
            f.nome             LIKE :pesquisa OR
            f.nif              LIKE :pesquisa OR
            f.email            LIKE :pesquisa OR
            f.telefone         LIKE :pesquisa OR
            f.pessoa_contacto  LIKE :pesquisa
        )";
        $params[':pesquisa'] = '%' . $pesquisa . '%';
    }

    /* Filtro por tipo */
    if (!empty($tipo)) {
        $sql .= " AND f.tipo_id = :tipo";
        $params[':tipo'] = $tipo;
    }

    $sql .= " GROUP BY f.id";

    /* Ordenação */
    switch ($ordenar) {
        case 'codigo_desc':
            $sql .= " ORDER BY f.codigo DESC";
            break;
        case 'nome_asc':
            $sql .= " ORDER BY f.nome ASC";
            break;
        case 'nome_desc':
            $sql .= " ORDER BY f.nome DESC";
            break;
        case 'codigo_asc':
        default:
            $sql .= " ORDER BY f.codigo ASC";
            break;
    }

    $stmt = $ligacao->prepare($sql);
    $stmt->execute($params);
    $fornecedores = $stmt->fetchAll(PDO::FETCH_OBJ);

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
                    <h2 class="mb-0">Fornecedores</h2>
                    <p class="text-muted mb-0">Gere o inventário de fornecedores</p>
                </div>
                <a href="novo-fornecedor.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Novo fornecedor
                </a>
            </div>

            <!-- Filtros -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="fornecedor.php">

                        <!-- Pesquisa -->
                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        name="pesquisa"
                                        value="<?= htmlspecialchars($pesquisa) ?>"
                                        class="form-control"
                                        placeholder="Pesquisar fornecedor...">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <a href="fornecedor.php"
                                        class="btn btn-outline-secondary"
                                        title="Limpar filtros">
                                        <i class="fas fa-filter-circle-xmark"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Filtros adicionais -->
                        <div class="row g-2 align-items-center">

                            <div class="col-md-3">
                                <select class="form-select" name="tipo">
                                    <option value="">Tipo de fornecedor</option>
                                    <?php foreach ($tipos as $t) : ?>
                                        <option value="<?= $t->id ?>" <?= $tipo == $t->id ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($t->nome) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select class="form-select" name="ordenar" onchange="this.form.submit()">
                                    <option value="codigo_asc"  <?= $ordenar == 'codigo_asc'  ? 'selected' : '' ?>>Código ↑</option>
                                    <option value="codigo_desc" <?= $ordenar == 'codigo_desc' ? 'selected' : '' ?>>Código ↓</option>
                                    <option value="nome_asc"    <?= $ordenar == 'nome_asc'    ? 'selected' : '' ?>>Nome ↑</option>
                                    <option value="nome_desc"   <?= $ordenar == 'nome_desc'   ? 'selected' : '' ?>>Nome ↓</option>
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
                <?php if (count($fornecedores) == 0) : ?>
                    <p class="text-muted">Nenhum fornecedor registado.</p>
                <?php else : ?>

                    <!-- Barra de resultados -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="alert alert-info d-inline-block" role="alert">
                            <i class="fas fa-circle-info me-2"></i>
                            <strong><?= count($fornecedores) ?> resultados encontrados</strong>
                        </div>
                    </div>

                    <!-- Tabela -->
                    <div class="card">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0" id="tabela-fornecedores">
                                    <thead style="background:#f8f9fa;">
                                        <tr>
                                            <th>Código</th>
                                            <th>Empresa</th>
                                            <th>Tipo</th>
                                            <th>Pessoa de contacto</th>
                                            <th>Telefone</th>
                                            <th>Equipamentos associados</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($fornecedores as $f) : ?>
                                            <tr>
                                                <td><?= htmlspecialchars($f->codigo) ?></td>
                                                <td><?= htmlspecialchars($f->nome) ?></td>
                                                <td><?= htmlspecialchars($f->tipo_nome ?? '—') ?></td>
                                                <td><?= htmlspecialchars($f->pessoa_contacto) ?></td>
                                                <td><?= htmlspecialchars($f->telefone) ?></td>
                                                <td><?= (int) $f->total_equipamentos ?></td>
                                                <td>
                                                    <div style="white-space: nowrap;">
                                                        <a href="detalhes-fornecedor.php?id_fornecedor=<?= htmlspecialchars(aes_encrypt($f->id)) ?>"
                                                            class="btn btn-sm btn-outline-primary" title="Ver detalhes">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="editar-fornecedor.php?id_fornecedor=<?= htmlspecialchars(aes_encrypt($f->id)) ?>"
                                                            class="btn btn-sm btn-outline-warning" title="Editar">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <button class="btn btn-sm btn-outline-danger" title="Eliminar"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEliminar"
                                                            data-id="<?= $f->id ?>"
                                                            data-codigo="<?= htmlspecialchars($f->codigo) ?>"
                                                            data-nome="<?= htmlspecialchars($f->nome) ?>">
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
                     <!-- Controlos DataTables fora do card -->
                    <div id="dt-controlos" class="d-flex justify-content-between align-items-center mt-3 px-1">
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
                <p>Tem a certeza que pretende eliminar o seguinte fornecedor?</p>
                <strong id="modalFornecedorInfo"></strong>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-arrow-left me-1"></i>Cancelar
                </button>
                <a id="btnConfirmarEliminar" href="#" class="btn btn-danger">
                    <i class="fas fa-trash me-1"></i>Eliminar fornecedor
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
        const nome   = btn.dataset.nome;

        document.getElementById('modalFornecedorInfo').textContent =
            codigo + ' — ' + nome;

        document.getElementById('btnConfirmarEliminar').href =
            'fornecedor.php?eliminar=' + id;
    });
</script>
<script>
    $(document).ready(function() {
        var tabela = $('#tabela-fornecedores').DataTable({
            language: {
                lengthMenu: "Mostrar _MENU_ registos por página",
                paginate: {
                    next: "Seguinte",
                    previous: "Anterior"
                }
            },
            paging: true,
            lengthChange: true,
            searching: false,
            ordering: false,
            info: false,
            lengthMenu: [10, 25, 50, 100],
            pageLength: 10
        });

        // Move os controlos para o div fora do card
        var wrapper = tabela.table().container();
        $('#dt-controlos')
            .append($(wrapper).find('.dataTables_length'))
            .append($(wrapper).find('.dataTables_paginate'));
    });
</script>
<?php include '../../includes/footer.php'; ?>