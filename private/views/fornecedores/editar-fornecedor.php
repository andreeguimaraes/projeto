<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
redirect_if_not_allowed(['administrador', 'tecnico']);
require_once __DIR__ . '/../../includes/validacoes.php';

// Só aceita GET e POST
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST'])) {
    header('Location: ' . BASE_URL . '/public/login.php');
    exit;
}

// Desencriptar e validar o ID
$idEncriptado = $_GET['id_fornecedor'] ?? null;
$id = aes_decrypt($idEncriptado);

if (!$id || !is_numeric($id)) {
    header('Location: fornecedor.php');
    exit;
}

$fornecedor = null;
$tipos_fornecedor_bd = [];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nome = trim($_POST['nome'] ?? '');
    $nif = trim($_POST['nif'] ?? '');
    $tipo_id = trim($_POST['tipo_id'] ?? '');
    $morada = trim($_POST['morada'] ?? '');
    $website = trim($_POST['website'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pessoa_contacto = trim($_POST['pessoa_contacto'] ?? '');
    $telefone_contacto = trim($_POST['telefone_contacto'] ?? '');
    $email_contacto = trim($_POST['email_contacto'] ?? '');

    // Validações
    $erros = array_merge(
        $erros,
        validar_nome_empresa($nome),
        validar_nif($nif),
        validar_tipo_fornecedor($tipo_id),
        validar_morada($morada),
        validar_website($website),
        validar_telefone($telefone),
        validar_email_geral($email),
        validar_pessoa_contacto($pessoa_contacto),
        validar_telefone_contacto2($telefone_contacto),
        validar_email_contacto($email_contacto)
    );

    // normalizar
    $nome = preg_replace('/\s+/', ' ', trim($nome));
    $morada = preg_replace('/\s+/', ' ', trim($morada));
    $pessoa_contacto = preg_replace('/\s+/', ' ', trim($pessoa_contacto));

    $email = strtolower(trim($email));
    $email_contacto = !empty($email_contacto) ? strtolower(trim($email_contacto)) : null;
    $website = !empty($website) ? trim($website) : null;


    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Verificar se já existe outro fornecedor com o mesmo NIF
            $stmtNif = $ligacao->prepare("
            SELECT id 
            FROM fornecedores 
            WHERE nif = :nif AND id <> :id
        ");
            $stmtNif->execute([
                ':nif' => $nif,
                ':id' => $id
            ]);

            if ($stmtNif->fetch()) {
                $erros[] = "Já existe outro fornecedor com este NIF.";
            }

            // Só atualiza se não encontrou NIF duplicado
            if (empty($erros)) {
                $stmt = $ligacao->prepare("
                UPDATE fornecedores
                SET nome = :nome,
                    nif = :nif,
                    telefone = :telefone,
                    email = :email,
                    morada = :morada,
                    website = :website,
                    pessoa_contacto = :pessoa_contacto,
                    telefone_contacto = :telefone_contacto,
                    email_contacto = :email_contacto,
                    tipo_id = :tipo_id
                WHERE id = :id
            ");

                $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
                $stmt->bindParam(':nif', $nif, PDO::PARAM_STR);
                $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
                $stmt->bindParam(':email', $email, PDO::PARAM_STR);
                $stmt->bindParam(':morada', $morada, PDO::PARAM_STR);
                $stmt->bindParam(':website', $website, PDO::PARAM_STR);
                $stmt->bindParam(':pessoa_contacto', $pessoa_contacto, PDO::PARAM_STR);
                $stmt->bindParam(':telefone_contacto', $telefone_contacto, PDO::PARAM_STR);
                $stmt->bindParam(':email_contacto', $email_contacto, PDO::PARAM_STR);
                $stmt->bindParam(':tipo_id', $tipo_id, PDO::PARAM_INT);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                $stmt->execute();

                header('Location: fornecedor.php');
                exit;
            }
        } catch (PDOException $err) {
            if ($err->getCode() == 23000) {
                $erros[] = "Já existe um fornecedor com esse NIF ou outro campo único repetido.";
            } else {
                $erros[] = "Erro ao atualizar fornecedor: " . $err->getMessage();
            }
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

    $stmt = $ligacao->prepare("
        SELECT *
        FROM fornecedores
        WHERE id = :id
    ");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $fornecedor = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$fornecedor) {
        header('Location: fornecedor.php');
        exit;
    }

    $stmtTipos = $ligacao->prepare("
        SELECT *
        FROM tipos_fornecedor
        ORDER BY nome
    ");
    $stmtTipos->execute();
    $tipos_fornecedor_bd = $stmtTipos->fetchAll(PDO::FETCH_OBJ);
} catch (PDOException $err) {
    $erros[] = "Erro na ligação à base de dados.";
    $fornecedor = null;
}

$ligacao = null;
?>

<?php require_once __DIR__ . '/../../includes/header.php'; ?>
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
                    <h2 class="mb-0">Editar fornecedor</h2>
                    <p class="text-muted mb-0"><?= htmlspecialchars($fornecedor->nome ?? '') ?></p>
                </div>
                <a href="fornecedor.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <form action="editar-fornecedor.php?id_fornecedor=<?= htmlspecialchars($idEncriptado) ?>" method="post" novalidate>
                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger mb-3">
                        <?php foreach ($erros as $erro): ?>
                            <div><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="card shadow rounded">
                    <div class="card-body">

                        <!-- INFORMAÇÃO GERAL -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-info-circle me-2"></i>Informação geral
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Código</label>
                                <input type="text"
                                    class="form-control"
                                    value="<?= htmlspecialchars($fornecedor->codigo ?? '') ?>"
                                    readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nome da empresa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nome"
                                    value="<?= htmlspecialchars($_POST['nome'] ?? $fornecedor->nome ?? '') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">NIF <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nif"
                                    value="<?= htmlspecialchars($_POST['nif'] ?? $fornecedor->nif ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tipo de fornecedor <span class="text-danger">*</span></label>
                                <select class="form-select" name="tipo_id" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($tipos_fornecedor_bd as $tipo): ?>
                                        <option value="<?= $tipo->id ?>"
                                            <?= (($_POST['tipo_id'] ?? $fornecedor->tipo_id ?? '') == $tipo->id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($tipo->nome) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Morada <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="morada"
                                    value="<?= htmlspecialchars($_POST['morada'] ?? $fornecedor->morada ?? '') ?>" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Website</label>
                                <input type="url" class="form-control" name="website"
                                    value="<?= htmlspecialchars($_POST['website'] ?? $fornecedor->website ?? '') ?>">
                            </div>
                        </div>
                        <hr>

                        <!-- CONTACTOS -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-address-book me-2"></i>Contactos gerais
                        </h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Telefone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="telefone"
                                    value="<?= htmlspecialchars($_POST['telefone'] ?? $fornecedor->telefone ?? '') ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email"
                                    value="<?= htmlspecialchars($_POST['email'] ?? $fornecedor->email ?? '') ?>" required>
                            </div>
                        </div>
                        <hr>

                        <!-- PESSOA DE CONTACTO -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-user me-2"></i>Pessoa de contacto
                        </h5>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nome <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="pessoa_contacto"
                                    value="<?= htmlspecialchars($_POST['pessoa_contacto'] ?? $fornecedor->pessoa_contacto ?? '') ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Telefone direto <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="telefone_contacto"
                                    value="<?= htmlspecialchars($_POST['telefone_contacto'] ?? $fornecedor->telefone_contacto ?? '') ?>" required>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold">Email direto</label>
                                <input type="email" class="form-control" name="email_contacto"
                                    value="<?= htmlspecialchars($_POST['email_contacto'] ?? $fornecedor->email_contacto ?? '') ?>">
                            </div>
                        </div>




                        <!-- Nota campos obrigatórios -->
                        <p class="text-muted small mb-3">
                            <span class="text-danger">*</span> Campos obrigatórios
                        </p>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between">
                            <a href="fornecedor.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Cancelar
                            </a>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-floppy-disk me-1"></i>Guardar alterações
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

        </main>
    </div>
</div>
<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>