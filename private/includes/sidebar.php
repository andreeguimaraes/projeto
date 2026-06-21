<!-- Offcanvas Sidebar -->
<div class="offcanvas offcanvas-start sidebar-admin" tabindex="-1" id="sidebarOffcanvas">

    <div class="offcanvas-header border-bottom">

        <h5 class="offcanvas-title">
            MEDINV
        </h5>

        <button type="button" class="btn-close" data-bs-dismiss="offcanvas">
        </button>

    </div>

    <div class="offcanvas-body">

        <h6 class="sidebar-title">GESTÃO</h6>

        <nav class="d-flex flex-column">

            <a href="/MEDINV/private/home.php" class="nav-link sidebar-link">
                <i class="fas fa-house me-2"></i>Início
            </a>

            <?php if (in_array($_SESSION['perfil'], ['administrador', 'tecnico'])) : ?>
                <a href="/MEDINV/private/views/dashboard.php" class="nav-link sidebar-link">
                    <i class="fas fa-chart-line me-2"></i>Dashboard
                </a>
            <?php endif; ?>

            <a href="/MEDINV/private/views/equipamentos/equipamentos.php" class="nav-link sidebar-link">
                <i class="fas fa-stethoscope me-2"></i>Equipamentos
            </a>

            <a href="/MEDINV/private/views/fornecedores/fornecedor.php" class="nav-link sidebar-link">
                <i class="fas fa-truck-medical me-2"></i>Fornecedores
            </a>

            <a href="/MEDINV/private/views/localizacoes/localizacoes.php" class="nav-link sidebar-link">
                <i class="fas fa-location-dot me-2"></i>Localizações
            </a>

            <?php if ($_SESSION['perfil'] === 'administrador') : ?>
                <hr>

                <h6 class="sidebar-title">
                    ÁREA PÚBLICA
                </h6>

                <a href="/MEDINV/private/views/conteudos/conteudos.php" class="nav-link sidebar-link">
                    <i class="fas fa-pen-to-square me-2"></i>
                    Gerir Conteúdos
                </a>
            <?php endif; ?>

        </nav>

    </div>
</div>