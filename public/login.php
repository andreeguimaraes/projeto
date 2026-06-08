<?php
// Inicia a sessão (necessário para usar $_SESSION)
session_start();
// Inicializa a variável que irá conter os erros de validação
$validation_errors = [];
// --------------------------------------------------------------------
// RECOLHA DE MENSAGENS TEMPORÁRIAS DA SESSÃO
// --------------------------------------------------------------------
// Verifica se existem erros de validação guardados na sessão
if (!empty($_SESSION['validation_errors'])) {
    // Se existirem, copia-os para a variável local
    $validation_errors = $_SESSION['validation_errors'];
    // Remove os erros da sessão para que não apareçam novamente numa recarga de página
    unset($_SESSION['validation_errors']);
}
// Inicializa a variável que irá conter erros de servidor
$server_error = [];
// Verifica se existe algum erro de servidor guardado na sessão
if (!empty($_SESSION['server_error'])) {
    // Se existir, copia-o para a variável local
    $server_error = $_SESSION['server_error'];
    // Remove o erro da sessão após ser lido
    unset($_SESSION['server_error']);
}
?>
<?php
$bodyClass = 'login-page';
include '../private/includes/header.php';
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6 col-sm-8 col-10">

            <div class="card">
                <div class="card-header-custom">
                    <!-- Imagem do ginásio + texto -->
                    <img src="/MEDINV/assets/img/logo_medinv.svg" alt="Logo da empresa">
                </div>
                <div class="card-body-custom">
                    <form action="../private/processa_login.php" method="post"> 
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="fa-solid fa-envelope me-1"></i>Email
                            </label>
                            <input type="email" name="text_email" id="email"
                                class="form-control" placeholder="email@medinv.pt" required>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="fa-solid fa-key me-1"></i>Password
                            </label>
                            <input type="password" name="text_password" id="password"
                                class="form-control" placeholder="••••••••" required>
                        </div>

                        <div class="mb-3 text-center">
                            <button type="submit" class="btn btn-login">
                                Entrar <i class="fa-solid fa-right-to-bracket ms-2"></i>
                            </button>
                        </div>

                        <!-- -------------------------------------------------------------------- -->
                        <!-- APRESENTAÇÃO DE MENSAGENS DE ERRO (VALIDAÇÃO E SERVIDOR) -->
                        <!-- -------------------------------------------------------------------- -->
                        <!-- Verifica se existem erros de validação -->
                        <?php if (!empty($validation_errors)) : ?>
                            <!-- Se existirem, apresenta um alerta de erro (vermelho) usando as classes do Bootstrap -->
                            <div class="alert alert-danger p-2 text-center">
                                <!-- Percorre todos os erros de validação -->
                                <?php foreach ($validation_errors as $error) : ?>
                                    <!-- Mostra cada erro dentro de uma <div>, escapando caracteres especiais para segurança -->
                                    <div><?= htmlspecialchars($error) ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        <!-- Verifica se existe um erro de servidor -->
                        <?php if (!empty($server_error)) : ?>
                            <!-- Apresenta também num alerta de erro (vermelho) -->
                            <div class="alert alert-danger p-2 text-center">
                                <!-- Mostra o erro do servidor, também escapado com htmlspecialchars -->
                                <div><?= htmlspecialchars($server_error) ?></div>
                            </div>
                        <?php endif; ?>

                    </form>

                    <div class="footer-text">
                        <a href="../public/index.html" style="color: #1d5c7f; text-decoration: none;">
                            <i class="fa-solid fa-arrow-left me-1"></i>Voltar ao site
                        </a>
                    </div>

                </div>
            </div>

            <p class="text-center mt-3" style="color: rgba(255,255,255,0.6); font-size: 0.85rem;">
                © 2026 MEDINV. Todos os direitos reservados.
            </p>

        </div>
    </div>
</div>

<!-- rodape  -->
<?php include '../private/includes/footer.php'; ?>