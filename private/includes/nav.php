<?php
// Verifica se a sessão ainda não foi iniciada
if (session_status() == PHP_SESSION_NONE) {
 session_start(); // Inicia a sessão
}
// Verifica se o utilizador está autenticado
if (!isset($_SESSION['utilizador'])) {
 // Se não estiver autenticado, redireciona para o formulário de login
 header('Location: ../public/login.php');
 exit; // Encerra o script
}
// A partir daqui, o utilizador está autenticado
// Podemos usar livremente os dados da sessão
$nome = $_SESSION['utilizador'];
?>
<!-- navbar -->
<header class="navbar-admin">
        <div class="row align-items-center w-100 m-0">
            <div class="col-6 d-flex align-items-center p-3">
                <button class="btn btn-outline-light me-3" type="button" data-bs-toggle="offcanvas"
                    data-bs-target="#sidebarOffcanvas" data-bs-backdrop="false">
                    <i class="fas fa-bars"></i>
                </button>
                <a href="/MEDINV/private/home.php" class="text-decoration-none">
                    <img src="/MEDINV/assets/img/logo_medinv.svg" alt="Logo da empresa">
                </a>
            </div>
            <div class="col-6 text-end p-3">
                <div class="dropdown">
                    <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fa-regular fa-user me-2"></i> <?= htmlspecialchars($nome) ?>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">
                            <i class="fa-solid fa-key me-2"></i>Alterar password
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/MEDINV/public/logout.php">
                            <i class="fa-solid fa-right-from-bracket me-2"></i>Sair
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
</header>