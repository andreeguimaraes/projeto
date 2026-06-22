<?php
require_once __DIR__ . '/../../config/config.php';

// ============================================================
// Sessão e autenticação
// ============================================================

// Inicia a sessão PHP, mas só se ainda não tiver sido iniciada noutro
// sítio do código (evita o erro "session already started" quando esta
// função é chamada várias vezes na mesma página).
function start_session()
{
 if (session_status() == PHP_SESSION_NONE) {
 session_start();
 }
}

// Verifica se existe um utilizador autenticado na sessão atual.
// Devolve true se $_SESSION['utilizador'] estiver definido (ou seja,
// se o login já foi feito com sucesso em processa_login.php),
// false caso contrário.
function check_session()
{
 return isset($_SESSION['utilizador']);
} 

// Protege uma página exigindo que o utilizador esteja autenticado.
// Deve ser chamada no topo de qualquer página privada, logo após os
// require_once. Garante que a sessão está iniciada e, se não houver
// utilizador autenticado, redireciona para o login e interrompe a
// execução do script com exit (nada do resto da página corre).
function redirect_if_not_logged($redirect_to = '/public/login.php')
{
 start_session();
 if (!check_session()) {
 header("Location: " . BASE_URL . $redirect_to);
 exit;
 }
}

// Termina a sessão do utilizador (logout) e redireciona para o login.
// session_unset() apaga todas as variáveis guardadas na sessão
// (utilizador, perfil, etc.) e session_destroy() destrói a sessão em
// si no servidor. Usada no link/botão "Sair" da interface.
function logout_and_redirect($redirect_to = '/public/login.php')
{
 start_session(); // Garante que a sessão foi iniciada
 session_unset(); // Remove todas as variáveis da sessão
 session_destroy(); // Destrói completamente a sessão
 // Redireciona para a página de login com caminho absoluto
 header("Location: " . BASE_URL . $redirect_to);
 exit; 

}

// ============================================================
// Encriptação e desencriptação de valores com OpenSSL
// Usadas para esconder os IDs reais (ex: id do equipamento) quando
// são enviados na URL (?id_equipamento=...), evitando que um
// utilizador veja ou adivinhe facilmente o ID de outro registo.
// ============================================================

// Encripta um valor (normalmente um ID numérico) usando AES-256-CBC.
// openssl_encrypt() devolve dados binários; bin2hex() converte esse
// resultado para uma string em hexadecimal, segura para colocar
// diretamente numa URL (sem caracteres especiais problemáticos).
function aes_encrypt($value) {
 return bin2hex(openssl_encrypt(
    $value,
    OPENSSL_METHOD,
    OPENSSL_KEY,
    OPENSSL_RAW_DATA,
    OPENSSL_IV
 ));
} 

// Faz o processo inverso de aes_encrypt(): recebe a string em
// hexadecimal vinda da URL, converte de volta para binário com
// hex2bin(), e desencripta para obter o ID original.
// Antes disso, faz uma validação básica: confirma que o valor
// recebido é mesmo uma string e que tem um número par de caracteres
// (hex2bin exige isso), evitando erros ou tentativas de manipulação
// da URL com valores inválidos.
function aes_decrypt($value) {
    if (!is_string($value) || strlen($value) % 2 !== 0) return false; // proteção básica 
    return openssl_decrypt(
        hex2bin($value),
        OPENSSL_METHOD,
        OPENSSL_KEY,
        OPENSSL_RAW_DATA,
        OPENSSL_IV
    );
} 

// ============================================================
// Controlo de acesso por perfil
// ============================================================

// Protege uma página (ou parte de uma página, ex: o bloco de
// eliminar) exigindo que o perfil do utilizador autenticado esteja
// dentro da lista $perfis_permitidos.
// Exemplo de uso: redirect_if_not_allowed(['administrador', 'tecnico']);
// Se o perfil guardado em sessão não existir ou não estiver na lista
// permitida, redireciona para a home da área privada (por defeito) e
// interrompe a execução do script com exit.
// Esta função deve ser chamada SEMPRE depois de redirect_if_not_logged(),
// já que pressupõe que o utilizador já está autenticado.
function redirect_if_not_allowed($perfis_permitidos, $redirect_to = '/private/home.php')
{
    start_session();
    if (!isset($_SESSION['perfil']) || !in_array($_SESSION['perfil'], $perfis_permitidos)) {
        header("Location: " . BASE_URL . $redirect_to);
        exit;
    }
}
function registar_log($ligacao, $acao, $modulo, $descricao = '', $sucesso = true) {
    $utilizador_id = $_SESSION['utilizador_id'] ?? null;
    $utilizador_email = $_SESSION['utilizador_email'] ?? null;
    $ip               = $_SERVER['REMOTE_ADDR'] ?? null;

    $stmt = $ligacao->prepare("
        INSERT INTO logs (utilizador_id, utilizador_email, acao, modulo, descricao, ip, sucesso)
        VALUES (:utilizador_id, :utilizador_email, :acao, :modulo, :descricao, :ip, :sucesso)
    ");
    $stmt->bindParam(':utilizador_id',    $utilizador_id,    PDO::PARAM_INT);
    $stmt->bindParam(':utilizador_email', $utilizador_email, PDO::PARAM_STR);
    $stmt->bindParam(':acao',             $acao,             PDO::PARAM_STR);
    $stmt->bindParam(':modulo',           $modulo,           PDO::PARAM_STR);
    $stmt->bindParam(':descricao',        $descricao,        PDO::PARAM_STR);
    $stmt->bindParam(':ip',               $ip,               PDO::PARAM_STR);
    $stmt->bindParam(':sucesso',          $sucesso,          PDO::PARAM_BOOL);
    $stmt->execute();
}
?>