<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// --------------------------------------------------------------------
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged(); // Inicia a sessão (se necessário) e verifica se o utilizador está autenticado
redirect_if_not_allowed(['administrador', 'tecnico']);


$erros        = [];
$erro_sistema = '';
$servicos     = [];
$codigo       = ''; // gerado fora do bloco POST

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Carregar serviços da BD
    $servicos = $ligacao->query("SELECT id, nome FROM servicos ORDER BY nome")
        ->fetchAll(PDO::FETCH_OBJ);

    // Gerar próximo código (LOC001, LOC002, ...)
    $maxCodigo = $ligacao->query("SELECT COALESCE(MAX(CAST(SUBSTRING(codigo, 4) AS UNSIGNED)), 0) FROM localizacoes")->fetchColumn();
    $codigo = 'LOC' . str_pad($maxCodigo + 1, 3, '0', STR_PAD_LEFT);
} catch (PDOException $e) {
    $erro_sistema = "Erro ao ligar à base de dados.";
}

// --------------------------------------------------------------------
// PROCESSAMENTO DO FORMULÁRIO
// --------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($erro_sistema)) {

    // 1. RECOLHER E FAZER TRIM
    $edificio    = trim($_POST['edificio']    ?? '');
    $piso        = trim($_POST['piso']        ?? '');
    $sala        = trim($_POST['sala']        ?? '');
    $servico_id  = trim($_POST['servico_id']  ?? '');

    // 2. VALIDAR
    $edificios_validos = ['Principal', 'Bloco B', 'Bloco C'];
    if (empty($edificio)) {
        $erros[] = "O edifício é obrigatório.";
    } elseif (!in_array($edificio, $edificios_validos)) {
        $erros[] = "O edifício selecionado não é válido.";
    }

    $pisos_validos = ['0', '1', '2', '3'];
    if (empty($piso)) {
        $erros[] = "O piso é obrigatório.";
    } elseif (!in_array($piso, $pisos_validos)) {
        $erros[] = "O piso selecionado não é válido.";
    }

    if (empty($sala)) {
        $erros[] = "A sala/gabinete é obrigatória.";
    } elseif (strlen($sala) < 2) {
        $erros[] = "A sala/gabinete deve ter pelo menos 2 caracteres.";
    } elseif (strlen($sala) > 50) {
        $erros[] = "A sala/gabinete não pode ter mais de 50 caracteres.";
    }

    if (empty($servico_id)) {
        $erros[] = "O serviço é obrigatório.";
    } elseif (!filter_var($servico_id, FILTER_VALIDATE_INT) || (int)$servico_id <= 0) {
        $erros[] = "O serviço selecionado não é válido.";
    }


    // 3. NORMALIZAR
    $sala = preg_replace('/\s+/', ' ', trim($sala));  // remove espaços a mais no meio
    $sala = strtoupper($sala);                         // sala  201 → SALA 201


    // 3. GRAVAR NA BASE DE DADOS
    if (empty($erros)) {
        try {
            // Verificar duplicado
            $stmtDup = $ligacao->prepare("
                SELECT id FROM localizacoes 
                WHERE edificio = :edificio AND piso = :piso AND sala = :sala
            ");
            $stmtDup->execute([
                ':edificio' => $edificio,
                ':piso'     => $piso,
                ':sala'     => $sala,
            ]);
            if ($stmtDup->fetch()) {
                $erros[] = "Já existe uma localização com este edifício, piso e sala.";
            } else {
                // Recalcular código para evitar race conditions
                $maxCodigo = $ligacao->query("SELECT COALESCE(MAX(CAST(SUBSTRING(codigo, 4) AS UNSIGNED)), 0) FROM localizacoes")->fetchColumn();
                $codigo = 'LOC' . str_pad($maxCodigo + 1, 3, '0', STR_PAD_LEFT);

                $stmt = $ligacao->prepare("
                    INSERT INTO localizacoes (codigo, edificio, piso, servico_id, sala)
                    VALUES (:codigo, :edificio, :piso, :servico_id, :sala)
                ");
                $stmt->execute([
                    ':codigo'      => $codigo,
                    ':edificio'    => $edificio,
                    ':piso'        => $piso,
                    ':servico_id'  => (int)$servico_id,
                    ':sala'        => $sala,
                ]);

                $ligacao = null;
                header("Location: localizacoes.php");
                exit;
            }
        } catch (PDOException $e) {
            $erro_sistema = "Erro ao guardar a localização. Por favor tente novamente.";
        }
    }
}

$ligacao = null;
?>
<?php require_once '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>

        <main class="col-12 p-4">

            <!-- Cabeçalho -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Nova localização</h2>
                    <p class="text-muted mb-0">Registo de localização física de equipamentos</p>
                </div>
                <a href="localizacoes.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <!-- Erro de sistema -->
            <?php if (!empty($erro_sistema)) : ?>
                <div class="alert alert-danger">
                    <i class="fas fa-triangle-exclamation me-2"></i><?= htmlspecialchars($erro_sistema) ?>
                </div>
            <?php endif; ?>

            <!-- Erros de validação -->
            <?php if (!empty($erros)) : ?>
                <div class="alert alert-danger">
                    <strong><i class="fas fa-triangle-exclamation me-2"></i>Por favor corrija os seguintes erros:</strong>
                    <ul class="mb-0 mt-2">
                        <?php foreach ($erros as $erro) : ?>
                            <li><?= htmlspecialchars($erro) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="novo-localizacoes.php" method="post" novalidate>
                <div class="card shadow rounded">
                    <div class="card-body">

                        <!-- DADOS DA LOCALIZAÇÃO -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-location-dot me-2"></i>Dados da localização
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Código</label>
                                <input type="text" class="form-control"
                                    value="<?= htmlspecialchars($codigo) ?>" readonly>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Edifício <span class="text-danger">*</span></label>
                                <select class="form-select" name="edificio" required>
                                    <option value="" disabled <?= empty($edificio) ? 'selected' : '' ?>>Selecionar edifício</option>
                                    <option value="Principal" <?= ($edificio ?? '') == 'Principal' ? 'selected' : '' ?>>Principal</option>
                                    <option value="Bloco B" <?= ($edificio ?? '') == 'Bloco B'   ? 'selected' : '' ?>>Bloco B</option>
                                    <option value="Bloco C" <?= ($edificio ?? '') == 'Bloco C'   ? 'selected' : '' ?>>Bloco C</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Piso <span class="text-danger">*</span></label>
                                <select class="form-select" name="piso" required>
                                    <option value="" disabled <?= empty($piso) ? 'selected' : '' ?>>Selecionar piso</option>
                                    <option value="0" <?= ($piso ?? '') === '0' ? 'selected' : '' ?>>Piso 0</option>
                                    <option value="1" <?= ($piso ?? '') === '1' ? 'selected' : '' ?>>Piso 1</option>
                                    <option value="2" <?= ($piso ?? '') === '2' ? 'selected' : '' ?>>Piso 2</option>
                                    <option value="3" <?= ($piso ?? '') === '3' ? 'selected' : '' ?>>Piso 3</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label fw-bold">Sala / Gabinete <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="sala"
                                    placeholder="Ex.: Sala 201" required
                                    value="<?= htmlspecialchars($sala ?? '') ?>">
                            </div>
                        </div>

                        <hr>

                        <!-- SERVIÇO -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-hospital me-2"></i>Serviço / Departamento
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Serviço <span class="text-danger">*</span></label>
                                <select class="form-select" name="servico_id" required>
                                    <option value="" disabled <?= empty($servico_id) ? 'selected' : '' ?>>Selecionar serviço</option>
                                    <?php foreach ($servicos as $s) : ?>
                                        <option value="<?= $s->id ?>"
                                            <?= (isset($servico_id) && $servico_id == $s->id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($s->nome) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>




                        <p class="text-muted small mb-3">
                            <span class="text-danger">*</span> Campos obrigatórios
                        </p>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between">
                            <a href="localizacoes.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-floppy-disk me-1"></i>Guardar localização
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </main>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>