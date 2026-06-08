<?php
// Inicia a sessão para poder usar a variável $_SESSION
require_once 'includes/funcoes.php';
start_session();

// ---------------------------------------------------------------------------
// SEGURANÇA: Impede que o utilizador aceda diretamente a este script.
// Este ficheiro deve ser acedido apenas através de submissão de formulário (POST).
// Se for acedido diretamente (por URL) recebe a informação de Acesso Inválido
// ----------------------------------------------------------------------------
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
 // Redireciona para o formulário de login (interface pública)
 header('Location: ../public/login.php');
 // Encerra a execução do script imediatamente após o redirecionamento
 return;
}
?>
<?php
// Mostrar os dados recebidos pelo formulário através do método POST
// O nome dos campos (entre aspas) deve ser igual ao atributo "name" no login.php
// --------------------------------------------------------------------
// RECOLHA DE DADOS DO FORMULÁRIO
// --------------------------------------------------------------------
// Verifica se o campo 'text_email' foi enviado via POST.
// Se sim, guarda-o na variável $username. Caso contrário, usa string vazia.
$username = isset($_POST['text_email']) ? $_POST['text_email'] : '';
// O mesmo para o campo da password.
$password = isset($_POST['text_password']) ? $_POST['text_password'] : '';
// --------------------------------------------------------------------
// APRESENTAÇÃO DE DADOS ENVIADOS
// --------------------------------------------------------------------
echo "Utilizador: " . $username . "<br>";
echo "Password: " . $password;
// Em produção, **nunca** se deve mostrar a password assim — isto é apenas para testes!

// --------------------------------------------------------------------
// VALIDAÇÃO DOS DADOS
// --------------------------------------------------------------------
// Inicializa um array vazio para guardar mensagens de erro de validação
$validation_errors = [];
// Verifica se o nome de utilizador (username) é um endereço de email válido
// Se não for, adiciona uma mensagem de erro ao array
if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
 $validation_errors[] = 'O username tem que ser um email válido.';
}
// Verifica se o nome de utilizador tem um comprimento entre 5 e 50 caracteres
// Isto evita usernames demasiado curtos ou excessivamente longos
if (strlen($username) < 5 || strlen($username) > 50) {
 $validation_errors[] = 'O username deve ter entre 5 e 50 caracteres.';
}
// Verifica se a password tem um comprimento entre 6 e 12 caracteres
// Garante uma password minimamente segura, mas fácil de recordar
if (strlen($password) < 6 || strlen($password) > 12) {
 $validation_errors[] = 'A password deve ter entre 6 e 12 caracteres.';
} 
// Se existirem erros de validação, guarda-os na sessão
// Depois, redireciona o utilizador de volta para o formulário de login
if (!empty($validation_errors)) {
 $_SESSION['validation_errors'] = $validation_errors;
 // Redireciona para a página de login (ou outro formulário)
 header('Location: ../public/login.php'); // ou 'login_form.php'

 // Encerra o script para impedir execução posterior
 return;
} 
// --------------------------------------------------------------------
// SIMULAÇÃO DE RESULTADO DE LOGIN (antes da ligação real à base de dados)
// --------------------------------------------------------------------
// Simula o resultado que viria de uma verificação à base de dados
// Neste caso, assume-se que o login é válido (status = 1)
// Mais tarde, esta variável será substituída por um resultado real vindo da BD
$result['status'] = 1; // 1 = login válido, 0 = inválido
// Verifica se o status retornado indica login inválido
if (!$result['status']) {
 // Se o login for inválido, guarda uma mensagem de erro na sessão
 $_SESSION['server_error'] = 'Login inválido';

 // Redireciona o utilizador novamente para o formulário de login
 header('Location: ../public/login.php'); // ou 'login_form.php'

 // Encerra o script para não continuar o processamento
 return;
}
// Se o status for 1 (válido), o código continuará — aqui será futuramente criada a sessão
// do utilizador e o redirecionamento para a área privada

// -------------------------------------------------------------------
// LOGIN BEM-SUCEDIDO: Guardar o utilizador na sessão
// --------------------------------------------------------------------
// Guarda o nome de utilizador na sessão para identificar o utilizador autenticado
$_SESSION['utilizador'] = $username;
// Agora código da área privada  
?>
<!-- cabeçalho -->
<?php include 'includes/header.php'; ?> 
    <!-- navbar -->
    <?php include 'includes/nav.php'; ?> 

    <div class="container-fluid">
        <div class="row">

            <!-- sidebar -->
            <?php include 'includes/sidebar.php'; ?> 


            <!-- Conteúdo Principal -->
            <main class="col-12 p-4">

                <!-- Boas vindas -->
                <div class="boas-vindas-card mb-4">
                    <div class="d-flex align-items-center gap-4">
                            <div>
                            <h4 class="mb-1">Bem-vindo, Administrador(a)</h4>
                            <p class="mb-1 text-muted">
                                <i class="fas fa-envelope me-1"></i>admin@medinv.pt
                            </p>
                            <p class="mb-0 text-muted">
                                <i class="fas fa-clock me-1"></i>
                                Último acesso: 17/05/2026 às 14:32
                            </p>
                        </div>
                        <div class="ms-auto text-end">
                            <span class="badge bg-success px-3 py-2">
                                <i class="fas fa-circle me-1" style="font-size: 0.6rem;"></i>Online
                            </span>
                            <p class="text-muted mt-2 mb-0" style="font-size: 0.85rem;">
                                17 de maio de 2026
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Indicadores -->
                <h5 class="mb-3 text-muted">
                    <i class="fas fa-chart-bar me-2"></i>Resumo do inventário
                </h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-hospital-user fa-2x mb-2 text-primary"></i>
                                <h3 class="mb-0">47</h3>
                                <p class="text-muted mb-0">Total de equipamentos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-circle-check fa-2x mb-2 text-success"></i>
                                <h3 class="mb-0">38</h3>
                                <p class="text-muted mb-0">Ativos</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-wrench fa-2x mb-2 text-warning"></i>
                                <h3 class="mb-0">5</h3>
                                <p class="text-muted mb-0">Em manutenção</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card indicador-card">
                            <div class="card-body text-center">
                                <i class="fas fa-triangle-exclamation fa-2x mb-2 text-danger"></i>
                                <h3 class="mb-0">4</h3>
                                <p class="text-muted mb-0">Garantias expiradas</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Alertas -->
                <h5 class="mb-3 text-muted">
                    <i class="fas fa-bell me-2"></i>Alertas
                </h5>
                <div class="alert alert-danger d-flex align-items-center gap-2">
                    <i class="fas fa-triangle-exclamation"></i>
                    <span>4 equipamentos com garantia expirada — 
                        <a href="views/equipamentos/equipamentos.html" class="alert-link">ver lista</a>
                    </span>
                </div>
                <div class="alert alert-warning d-flex align-items-center gap-2">
                    <i class="fas fa-clock"></i>
                    <span>3 garantias a expirar nos próximos 30 dias — 
                        <a href="views/garantias/garantias.html" class="alert-link">ver lista</a>
                    </span>
                </div>
                <div class="alert alert-info d-flex align-items-center gap-2">
                    <i class="fas fa-file-circle-exclamation"></i>
                    <span>2 equipamentos sem documentação associada — 
                        <a href="views/documentacao/documentacao.html" class="alert-link">ver lista</a>
                    </span>
                </div>
            </main>
        </div> 
    </div>
<!-- rodapé -->
<?php include 'includes/footer.php'; ?> 