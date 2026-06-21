<?php
require_once __DIR__ . '/../../includes/funcoes.php';
redirect_if_not_logged();
redirect_if_not_allowed(['administrador']);
require_once __DIR__ . '/../../includes/validacoes.php';

$erros = [];
$sucesso = '';
$conteudos = [];

// Lista de chaves esperadas (e valor por defeito caso não exista na BD)
$chaves_default = [
    'titulo_principal' => '',
    'telefone' => '',
    'email' => '',
    'morada' => '',
    'codigo_postal' => '',
    'localidade' => '',
    'horario' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ligacao = new PDO(
            "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
            MYSQL_USERNAME,
            MYSQL_PASSWORD
        );
        $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email_post = trim($_POST['email'] ?? '');
        $codigo_postal_post = trim($_POST['codigo_postal'] ?? '');
        $telefone_post = trim($_POST['telefone'] ?? '');

        $erros = array_merge(
            $erros,
            validar_email_conteudo($email_post),
            validar_codigo_postal($codigo_postal_post),
            validar_telefone_conteudo($telefone_post)
        );

        if (empty($erros)) {
            $stmt = $ligacao->prepare("
                INSERT INTO conteudos_site (chave, valor)
                VALUES (:chave, :valor)
                ON DUPLICATE KEY UPDATE valor = :valor2
            ");

            foreach (array_keys($chaves_default) as $chave) {
                $valor = trim($_POST[$chave] ?? '');
                $stmt->bindParam(':chave', $chave, PDO::PARAM_STR);
                $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
                $stmt->bindParam(':valor2', $valor, PDO::PARAM_STR);
                $stmt->execute();
            }

            $sucesso = 'Conteúdos atualizados com sucesso.';
        }
    } catch (PDOException $e) {
        $erros[] = "Erro ao guardar alterações: " . $e->getMessage();
    }

    $ligacao = null;
}

// Buscar conteúdos atuais (sempre, para mostrar no formulário)
try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->query("SELECT chave, valor FROM conteudos_site");
    $linhas = $stmt->fetchAll(PDO::FETCH_OBJ);

    foreach ($linhas as $linha) {
        $conteudos[$linha->chave] = $linha->valor;
    }

    $conteudos = array_merge($chaves_default, $conteudos);
} catch (PDOException $e) {
    $erros[] = "Erro na ligação à base de dados.";
    $conteudos = $chaves_default;
}

$ligacao = null;
?>
<?php include '../../includes/header.php'; ?>
<!-- Navbar -->
<?php include '../../includes/nav.php'; ?>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <?php include '../../includes/sidebar.php'; ?>

        <!-- Conteúdo principal -->
        <main class="col-12 p-4">

            <form method="post" action="conteudos.php">

                <!-- Cabeçalho -->
                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h2 class="mb-0">
                            Gestão de conteúdos
                        </h2>

                        <p class="text-muted mb-0">
                            Atualize os conteúdos apresentados na área pública
                        </p>

                    </div>

                    <button type="submit" class="btn btn-primary">

                        <i class="fas fa-floppy-disk me-2"></i>
                        Guardar alterações

                    </button>

                </div>

                <?php if (!empty($sucesso)): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-circle-check me-2"></i><?= htmlspecialchars($sucesso) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($erros)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($erros as $erro): ?>
                            <div><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- Conteúdo principal -->
                <div class="row g-4">

                    <div class="col-12">

                        <div class="card shadow-sm">

                            <div class="card-header">

                                <h5 class="mb-0">

                                    <i class="fas fa-circle-info me-2"></i>
                                    Informações do site

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="row">

                                    <div class="col-md-6">

                                        <div class="mb-3">

                                            <label class="form-label fw-bold">
                                                Título principal
                                            </label>

                                            <input type="text" class="form-control" name="titulo_principal"
                                                value="<?= htmlspecialchars($conteudos['titulo_principal']) ?>">

                                        </div>

                                        <div class="mb-3">

                                            <label class="form-label fw-bold">
                                                Telefone
                                            </label>

                                            <input type="text" class="form-control" name="telefone"
                                                value="<?= htmlspecialchars($conteudos['telefone']) ?>">

                                        </div>

                                        <div class="mb-3">

                                            <label class="form-label fw-bold">
                                                Email
                                            </label>

                                            <input type="email" class="form-control" name="email"
                                                value="<?= htmlspecialchars($conteudos['email']) ?>">

                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label fw-bold">
                                                Morada
                                            </label>

                                            <textarea class="form-control" name="morada"
                                                rows="3"><?= htmlspecialchars($conteudos['morada']) ?></textarea>
                                        </div>

                                    </div>

                                    <div class="col-md-6">

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">
                                                    Código postal
                                                </label>

                                                <input type="text" class="form-control" name="codigo_postal"
                                                    value="<?= htmlspecialchars($conteudos['codigo_postal']) ?>"
                                                    placeholder="Ex.: 4200-072">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label class="form-label fw-bold">
                                                    Localidade
                                                </label>

                                                <input type="text" class="form-control" name="localidade"
                                                    value="<?= htmlspecialchars($conteudos['localidade']) ?>"
                                                    placeholder="Ex.: Porto">
                                            </div>
                                        </div>

                                        <div class="mb-3">

                                            <label class="form-label fw-bold">
                                                Horário de atendimento
                                            </label>

                                            <input type="text" class="form-control" name="horario"
                                                value="<?= htmlspecialchars($conteudos['horario']) ?>">

                                        </div>
                                    
                                    </div>
                                </div>
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