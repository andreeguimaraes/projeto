<?php
// --------------------------------------------------------------------
// SEGURANÇA: Proteção de acesso à página de edição
// Este ficheiro deve ser acedido apenas por utilizadores autenticados.
// Caso não exista sessão iniciada, o utilizador será redirecionado para o login.
// --------------------------------------------------------------------
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();

$erros        = [];
$erro_sistema = '';
$sucesso      = false;
$tipos_fornecedor = [];
$codigo = ''; // necessário porque é gerado fora do bloco POST


try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Carrega tipos de fornecedor da BD
    $tipos_fornecedor = $ligacao->query("SELECT id, nome FROM tipos_fornecedor ORDER BY nome")
                                ->fetchAll(PDO::FETCH_OBJ);

    // Gera o próximo código (FOR001, FOR002, ...)
    $maxCodigo = $ligacao->query("SELECT COALESCE(MAX(CAST(SUBSTRING(codigo, 4) AS UNSIGNED)), 0) FROM fornecedores")->fetchColumn();
    $codigo = 'FOR' . str_pad($maxCodigo + 1, 3, '0', STR_PAD_LEFT);

} catch (PDOException $e) {
    $erro_sistema = "Erro ao ligar à base de dados.";
}

// --------------------------------------------------------------------
// PROCESSAMENTO DO FORMULÁRIO
// --------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && empty($erro_sistema)) {

    // 1. Recolher dados
    $nome               = trim($_POST['nome_empresa']       ?? '');
    $nif                = trim($_POST['nif']                ?? '');
    $tipo_id            = trim($_POST['tipo_id']            ?? '');
    $morada             = trim($_POST['morada']             ?? '');
    $website            = trim($_POST['website']            ?? '');
    $telefone           = trim($_POST['telefone']           ?? '');
    $email              = trim($_POST['email']              ?? '');
    $pessoa_contacto    = trim($_POST['pessoa_contacto']    ?? '');
    $telefone_contacto  = trim($_POST['telefone_contacto']  ?? '');
    $email_contacto     = trim($_POST['email_contacto']     ?? '');
    $observacoes        = trim($_POST['observacoes']        ?? '');

    // 2. Validar dados
    if (empty($nome)) {
    $erros[] = "O nome da empresa é obrigatório.";
    } elseif (strlen($nome) < 2) {
        $erros[] = "O nome da empresa deve ter pelo menos 2 caracteres.";
    } elseif (!preg_match('/[a-zA-ZÀ-ÿ]/', $nome)) {
        $erros[] = "O nome da empresa deve conter pelo menos uma letra.";
    } elseif (strlen($nome) > 150) {
        $erros[] = "O nome da empresa não pode ter mais de 150 caracteres.";
    }

    if (empty($nif))               $erros[] = "O NIF é obrigatório.";
    if (empty($tipo_id))           $erros[] = "O tipo de fornecedor é obrigatório.";
    
    if (empty($morada)) {
    $erros[] = "A morada é obrigatória.";
    } elseif (strlen($morada) < 5) {
        $erros[] = "A morada deve ter pelo menos 5 caracteres.";
    } elseif (strlen($morada) > 255) {
        $erros[] = "A morada não pode ter mais de 255 caracteres.";
    }

    if (empty($telefone))          $erros[] = "O telefone é obrigatório.";
    if (empty($email))             $erros[] = "O email é obrigatório.";
    
    if (empty($pessoa_contacto)) {
    $erros[] = "O nome da pessoa de contacto é obrigatório.";
    } elseif (strlen($pessoa_contacto) < 2) {
        $erros[] = "O nome da pessoa de contacto deve ter pelo menos 2 caracteres.";
    } elseif (!preg_match('/[a-zA-ZÀ-ÿ]/', $pessoa_contacto)) {
        $erros[] = "O nome da pessoa de contacto deve conter pelo menos uma letra.";
    } elseif (strlen($pessoa_contacto) > 100) {
        $erros[] = "O nome não pode ter mais de 100 caracteres.";
    }

    if (empty($telefone_contacto)) $erros[] = "O telefone direto é obrigatório.";

    // NIF
    if (empty($nif)) {
        $erros[] = "O NIF é obrigatório.";
    } elseif (!preg_match('/^[0-9]{9}$/', $nif)) {
        $erros[] = "O NIF deve ter exatamente 9 dígitos.";
    }
    // Verifica NIF duplicado
    if (empty($erros)) {
        try {
            $stmtNif = $ligacao->prepare("SELECT id FROM fornecedores WHERE nif = :nif");
            $stmtNif->execute([':nif' => $nif]);
            if ($stmtNif->fetch()) $erros[] = "Já existe um fornecedor com este NIF.";
        } catch (PDOException $e) {
            $erro_sistema = "Erro ao verificar duplicados.";
        }
    }
    
    // Tipo de fornecedor
    if (!empty($tipo_id) && !ctype_digit($tipo_id)) {
        $erros[] = "O tipo de fornecedor selecionado não é válido.";
    }

    // Telefone geral
    if (empty($telefone)) {
    $erros[] = "O telefone é obrigatório.";
    } elseif (!preg_match('/^\+?[0-9\s]{9,20}$/', $telefone)) {
        $erros[] = "O telefone não é válido.";
    }

    // Telefone direto
    if (empty($telefone_contacto)) {
        $erros[] = "O telefone direto é obrigatório.";
    } elseif (!preg_match('/^\+?[0-9\s]{9,20}$/', $telefone_contacto)) {
        $erros[] = "O telefone direto não é válido.";
    }

    // Email geral
    if (empty($email)) {
        $erros[] = "O email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email geral não é válido.";
    }

    // Email direto
    if (!empty($email_contacto) && !filter_var($email_contacto, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email direto não é válido.";
    }

    // Website
    if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $erros[] = "O website não é válido.";
    }



    // 3. Se não houver erros, guardar na base de dados
    if (empty($erros) && empty($erro_sistema)) {
        try {
            $maxCodigo = $ligacao->query("SELECT COALESCE(MAX(CAST(SUBSTRING(codigo, 4) AS UNSIGNED)), 0) FROM fornecedores")->fetchColumn();
            $codigo = 'FOR' . str_pad($maxCodigo + 1, 3, '0', STR_PAD_LEFT);

            $stmt = $ligacao->prepare("
                INSERT INTO fornecedores
                    (codigo, nome, nif, tipo_id, morada, website,
                     telefone, email,
                     pessoa_contacto, telefone_contacto, email_contacto,
                     observacoes)
                VALUES
                    (:codigo, :nome, :nif, :tipo_id, :morada, :website,
                     :telefone, :email,
                     :pessoa_contacto, :telefone_contacto, :email_contacto,
                     :observacoes)
            ");

            $stmt->execute([
                ':codigo'            => $codigo,
                ':nome'              => $nome,
                ':nif'               => $nif,
                ':tipo_id'           => (int) $tipo_id,
                ':morada'            => $morada,
                ':website'           => $website    ?: null,
                ':telefone'          => $telefone,
                ':email'             => $email,
                ':pessoa_contacto'   => $pessoa_contacto,
                ':telefone_contacto' => $telefone_contacto,
                ':email_contacto'    => $email_contacto ?: null,
                ':observacoes'       => $observacoes    ?: null,
            ]);

            $sucesso = true;

            // Limpa variáveis e actualiza código
            $nome = $nif = $tipo_id = $morada = $website = '';
            $telefone = $email = $pessoa_contacto = '';
            $telefone_contacto = $email_contacto = $observacoes = '';

            $maxCodigo = $ligacao->query("SELECT COALESCE(MAX(CAST(SUBSTRING(codigo, 4) AS UNSIGNED)), 0) FROM fornecedores")->fetchColumn();
            $codigo = 'FOR' . str_pad($maxCodigo + 1, 3, '0', STR_PAD_LEFT);

        } catch (PDOException $e) {
            $erro_sistema = "Erro ao guardar o fornecedor. Por favor tente novamente.";
        }
    }
}

$ligacao = null;
?>
<?php include '../../includes/header.php'; ?>
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <?php include '../../includes/sidebar.php'; ?>

        <main class="col-12 p-4">

            <!-- Cabeçalho -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="mb-0">Novo fornecedor</h2>
                    <p class="text-muted mb-0">Preencha os campos para registar um novo fornecedor</p>
                </div>
                <a href="fornecedor.php" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Voltar
                </a>
            </div>

            <!-- Erro de sistema -->
            <?php if (!empty($erro_sistema)) : ?>
                <div class="alert alert-danger">
                    <i class="fas fa-triangle-exclamation me-2"></i><?= htmlspecialchars($erro_sistema) ?>
                </div>
            <?php endif; ?>

            <!-- Sucesso -->
            <?php if ($sucesso) : ?>
                <div class="alert alert-success">
                    <i class="fas fa-circle-check me-2"></i>Fornecedor registado com sucesso!
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

            <form action="novo-fornecedor.php" method="post" novalidate>
                <div class="card shadow rounded">
                    <div class="card-body">

                        <!-- INFORMAÇÃO GERAL -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-info-circle me-2"></i>Informação geral
                        </h5>

                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Código</label>
                                <input type="text" class="form-control"
                                    value="<?= htmlspecialchars($codigo) ?>" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Nome da empresa <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nome_empresa"
                                    placeholder="Ex: Philips Healthcare Portugal" required
                                    autocomplete="new-password"
                                    value="<?= htmlspecialchars($nome ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">NIF <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nif"
                                    placeholder="Ex: 500123456" required
                                    value="<?= htmlspecialchars($nif ?? '') ?>">
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Tipo de fornecedor <span class="text-danger">*</span></label>
                                <select class="form-select" name="tipo_id" required>
                                    <option value="">Selecione...</option>
                                    <?php foreach ($tipos_fornecedor as $t) : ?>
                                        <option value="<?= $t->id ?>"
                                            <?= (isset($tipo_id) && $tipo_id == $t->id) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($t->nome) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Morada <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="morada"
                                    placeholder="Ex: Av. da Liberdade, 110, Lisboa" required
                                    value="<?= htmlspecialchars($morada ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Website</label>
                                <input type="url" class="form-control" name="website"
                                    placeholder="Ex: https://www.empresa.pt"
                                    value="<?= htmlspecialchars($website ?? '') ?>">
                            </div>
                        </div>

                        <hr>

                        <!-- CONTACTOS GERAIS -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-address-book me-2"></i>Contactos gerais
                        </h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Telefone <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="telefone"
                                    placeholder="Ex: +351 210 000 000" required
                                    value="<?= htmlspecialchars($telefone ?? '') ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" name="email"
                                    placeholder="Ex: geral@empresa.pt" required
                                    value="<?= htmlspecialchars($email ?? '') ?>">
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
                                    placeholder="Ex: João Ferreira" required
                                    value="<?= htmlspecialchars($pessoa_contacto ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Telefone direto <span class="text-danger">*</span></label>
                                <input type="tel" class="form-control" name="telefone_contacto"
                                    placeholder="Ex: +351 962 000 000" required
                                    value="<?= htmlspecialchars($telefone_contacto ?? '') ?>">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Email direto</label>
                                <input type="email" class="form-control" name="email_contacto"
                                    placeholder="Ex: joao.ferreira@empresa.pt"
                                    value="<?= htmlspecialchars($email_contacto ?? '') ?>">
                            </div>
                        </div>

                        <hr>

                        <!-- OBSERVAÇÕES -->
                        <h5 class="text-muted mb-3">
                            <i class="fas fa-note-sticky me-2"></i>Observações
                        </h5>
                        <div class="mb-4">
                            <textarea class="form-control" name="observacoes" rows="4"
                                placeholder="Informações adicionais sobre o fornecedor..."><?= htmlspecialchars($observacoes ?? '') ?></textarea>
                        </div>

                        <p class="text-muted small mb-3">
                            <span class="text-danger">*</span> Campos obrigatórios
                        </p>

                        <!-- Botões -->
                        <div class="d-flex justify-content-between">
                            <a href="fornecedor.php" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-floppy-disk me-1"></i>Registar fornecedor
                            </button>
                        </div>

                    </div>
                </div>
            </form>

        </main>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>