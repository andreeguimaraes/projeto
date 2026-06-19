<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
require_once __DIR__ . '/../../includes/validacoes.php';

// Só aceita GET e POST
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

// Desencriptar e validar o ID
$idEncriptado = $_GET['id_localizacao'] ?? null;
$id = aes_decrypt($idEncriptado);

if (!$id || !is_numeric($id)) {
    header('Location: localizacoes.php');
    exit;
}

$localizacao = null;
$servicos_bd = [];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $edificio = trim($_POST['edificio'] ?? '');
    $piso = trim($_POST['piso'] ?? '');
    $sala = trim($_POST['sala'] ?? '');
    $servico_id = trim($_POST['servico_id'] ?? '');
    $observacoes = trim($_POST['observacoes'] ?? '');

    // Validações
    if ($edificio === '') {
        $erros[] = "O edifício é obrigatório.";
    }

    if (!in_array($edificio, ['Principal', 'Bloco B', 'Bloco C'])) {
        $erros[] = "O edifício selecionado não é válido.";
    }

    if ($piso === '') {
        $erros[] = "O piso é obrigatório.";
    }

    if (!in_array($piso, ['0', '1', '2', '3'])) {
        $erros[] = "O piso selecionado não é válido.";
    }

    if ($sala === '') {
        $erros[] = "A sala é obrigatória.";
    }

    if ($servico_id === '') {
        $erros[] = "O serviço é obrigatório.";
    }

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $ligacao->prepare("
                UPDATE localizacoes
                SET edificio = :edificio,
                    piso = :piso,
                    servico_id = :servico_id,
                    sala = :sala,
                    observacoes = :observacoes
                WHERE id = :id
            ");

            $stmt->bindParam(':edificio', $edificio, PDO::PARAM_STR);
            $stmt->bindParam(':piso', $piso, PDO::PARAM_STR);
            $stmt->bindParam(':servico_id', $servico_id, PDO::PARAM_INT);
            $stmt->bindParam(':sala', $sala, PDO::PARAM_STR);
            $stmt->bindParam(':observacoes', $observacoes, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            $stmt->execute();

            header('Location: localizacoes.php');
            exit;

        } catch (PDOException $err) {
            $erros[] = "Erro ao atualizar localização: " . $err->getMessage();
        }
    }
}

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Buscar localização atual
    $stmt = $ligacao->prepare("
        SELECT 
            l.*,
            s.nome AS servico_nome
        FROM localizacoes l
        JOIN servicos s ON s.id = l.servico_id
        WHERE l.id = :id
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $localizacao = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$localizacao) {
        header('Location: localizacoes.php');
        exit;
    }

    // Buscar serviços para o select
    $stmtServicos = $ligacao->prepare("
        SELECT *
        FROM servicos
        ORDER BY nome
    ");
    $stmtServicos->execute();
    $servicos_bd = $stmtServicos->fetchAll(PDO::FETCH_OBJ);

} catch (PDOException $err) {
    $erros[] = "Erro na ligação à base de dados.";
    $localizacao = null;
}

$ligacao = null;
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <?php include __DIR__ . '/../../includes/sidebar.php'; ?>

        <main class="col-12 p-4">

            <!-- Cabeçalho -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Editar localização</h2>
                    <p class="text-muted mb-0">
                        <?= htmlspecialchars($localizacao->codigo ?? '') ?>
                        —
                        <?= htmlspecialchars($localizacao->servico_nome ?? '') ?>
                        Piso <?= htmlspecialchars($localizacao->piso ?? '') ?>
                        Sala <?= htmlspecialchars($localizacao->sala ?? '') ?>
                    </p>
                </div>

                <a href="localizacoes.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <form action="editar-localizacoes.php?id_localizacao=<?= htmlspecialchars($idEncriptado) ?>" method="post" novalidate>

                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger mb-3">
                        <?php foreach ($erros as $erro): ?>
                            <div><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="card shadow rounded">
                    <div class="card-body">

                        <!-- LOCALIZAÇÃO -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-location-dot me-2"></i>
                            Dados da localização
                        </h5>

                        <div class="row mb-4">

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Código</label>
                                <input type="text"
                                    class="form-control"
                                    value="<?= htmlspecialchars($localizacao->codigo ?? '') ?>"
                                    readonly>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Edifício <span class="text-danger">*</span></label>
                                <select class="form-select" name="edificio" required>
                                    <option value="">Selecione...</option>
                                    <option value="Principal" <?= (($_POST['edificio'] ?? $localizacao->edificio ?? '') == 'Principal') ? 'selected' : '' ?>>
                                        Principal
                                    </option>
                                    <option value="Bloco B" <?= (($_POST['edificio'] ?? $localizacao->edificio ?? '') == 'Bloco B') ? 'selected' : '' ?>>
                                        Bloco B
                                    </option>
                                    <option value="Bloco C" <?= (($_POST['edificio'] ?? $localizacao->edificio ?? '') == 'Bloco C') ? 'selected' : '' ?>>
                                        Bloco C
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Piso <span class="text-danger">*</span></label>
                                <select class="form-select" name="piso" required>
                                    <option value="">Selecione...</option>
                                    <option value="0" <?= (($_POST['piso'] ?? $localizacao->piso ?? '') == '0') ? 'selected' : '' ?>>
                                        Piso 0
                                    </option>
                                    <option value="1" <?= (($_POST['piso'] ?? $localizacao->piso ?? '') == '1') ? 'selected' : '' ?>>
                                        Piso 1
                                    </option>
                                    <option value="2" <?= (($_POST['piso'] ?? $localizacao->piso ?? '') == '2') ? 'selected' : '' ?>>
                                        Piso 2
                                    </option>
                                    <option value="3" <?= (($_POST['piso'] ?? $localizacao->piso ?? '') == '3') ? 'selected' : '' ?>>
                                        Piso 3
                                    </option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold">Sala / Gabinete <span class="text-danger">*</span></label>
                                <input type="text"
                                    class="form-control"
                                    name="sala"
                                    value="<?= htmlspecialchars($_POST['sala'] ?? $localizacao->sala ?? '') ?>"
                                    required>
                            </div>

                        </div>

                        <hr>

                        <!-- SERVIÇO -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-hospital me-2"></i>
                            Serviço / Departamento
                        </h5>

                        <div class="row mb-4">

                            <div class="col-md-6">
                                <label class="form-label fw-bold">Serviço <span class="text-danger">*</span></label>
                                <select class="form-select" name="servico_id" required>
                                    <option value="">Selecione...</option>

                                    <?php foreach ($servicos_bd as $servico): ?>
                                        <option value="<?= $servico->id ?>"
                                            <?= (($_POST['servico_id'] ?? $localizacao->servico_id ?? '') == $servico->id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($servico->nome) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                        </div>

                        <hr>

                        <!-- OBSERVAÇÕES -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-note-sticky me-2"></i>
                            Observações
                        </h5>

                        <div class="mb-4">
                            <textarea class="form-control"
                                name="observacoes"
                                rows="4"><?= htmlspecialchars($_POST['observacoes'] ?? $localizacao->observacoes ?? '') ?></textarea>
                        </div>

                        <p class="text-muted small mb-3">
                            <span class="text-danger">*</span> Campos obrigatórios
                        </p>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between">
                            <a href="localizacoes.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Cancelar
                            </a>

                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-floppy-disk me-1"></i>
                                Guardar alterações
                            </button>
                        </div>

                    </div>
                </div>

            </form>

        </main>

    </div>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>