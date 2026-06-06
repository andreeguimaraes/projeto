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
                        <form action="../private/index-admin.php" method="post">

                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fa-solid fa-envelope me-1"></i>Email
                                </label>
                                <input type="email" name="text_username" id="email" 
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

                            <div class="alert alert-danger p-2 text-center d-none" id="erroLogin">
                                <i class="fa-solid fa-circle-exclamation me-1"></i>
                                Utilizador ou password incorretos
                            </div>

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
