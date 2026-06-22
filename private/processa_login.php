<?php
require_once 'includes/funcoes.php';
start_session();

// --------------------------------------------------------------------
// SEGURANÇA: Impede acesso direto a este script.
// --------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: ../public/login.php');
    return;
}

// --------------------------------------------------------------------
// RECOLHA DE DADOS DO FORMULÁRIO
// --------------------------------------------------------------------
$username = isset($_POST['text_email'])    ? $_POST['text_email']    : '';
$password = isset($_POST['text_password']) ? $_POST['text_password'] : '';

// --------------------------------------------------------------------
// VALIDAÇÃO DOS DADOS
// --------------------------------------------------------------------
$validation_errors = [];

if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    $validation_errors[] = 'O username tem que ser um email válido.';
}
if (strlen($username) < 5 || strlen($username) > 50) {
    $validation_errors[] = 'O username deve ter entre 5 e 50 caracteres.';
}
if (strlen($password) < 6 || strlen($password) > 12) {
    $validation_errors[] = 'A password deve ter entre 6 e 12 caracteres.';
}

if (!empty($validation_errors)) {
    $_SESSION['validation_errors'] = $validation_errors;
    header('Location: ../public/login.php');
    return;
}

// --------------------------------------------------------------------
// LIGAÇÃO À BASE DE DADOS E VERIFICAÇÃO DE CREDENCIAIS
// --------------------------------------------------------------------
try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $ligacao->prepare("SELECT * FROM utilizadores WHERE email = :email AND ativo = 1");
    $stmt->bindParam(':email', $username, PDO::PARAM_STR);
    $stmt->execute();
    $utilizador = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$utilizador || !password_verify($password, $utilizador->password)) {
        // LOG: tentativa de login falhada
        registar_log(
            $ligacao,
            'login_falhado',
            'autenticacao',
            'Tentativa de login falhada para o email: ' . $username,
            false
        );

        $_SESSION['server_error'] = 'Login inválido';
        header('Location: ../public/login.php');
        return;
    }

} catch (PDOException $e) {
    // LOG: erro de ligação à BD (sem ligação disponível, regista só na sessão)
    $_SESSION['server_error'] = 'Erro ao ligar à base de dados.';
    header('Location: ../public/login.php');
    return;
}

// --------------------------------------------------------------------
// LOGIN BEM-SUCEDIDO
// --------------------------------------------------------------------
$_SESSION['utilizador']       = $utilizador->nome;
$_SESSION['utilizador_id']    = $utilizador->id;
$_SESSION['utilizador_email'] = $utilizador->email;
$_SESSION['perfil']           = $utilizador->perfil;

// LOG: login bem-sucedido
registar_log(
    $ligacao,
    'login',
    'autenticacao',
    'Login bem-sucedido: ' . $utilizador->email . ' (perfil: ' . $utilizador->perfil . ')'
);

header('Location: home.php');
exit;