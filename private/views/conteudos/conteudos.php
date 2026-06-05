<?php include '../../includes/header.php'; ?>
    <!-- Navbar -->
    <?php include '../../includes/nav.php'; ?>

    <div class="container-fluid">
        <div class="row">

            <!-- Sidebar -->
            <?php include '../../includes/sidebar.php'; ?>

            <!-- Conteúdo principal -->
            <main class="col-12 p-4">

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

                    <button class="btn btn-primary">

                        <i class="fas fa-floppy-disk me-2"></i>
                        Guardar alterações

                    </button>

                </div>

                <!-- Conteúdo principal -->
                <div class="row g-4">

                    <!-- Conteúdo institucional -->
                    <div class="col-lg-6">

                        <div class="card shadow-sm h-100">

                            <div class="card-header">

                                <h5 class="mb-0">

                                    <i class="fas fa-circle-info me-2"></i>
                                    Informações institucionais

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Título principal
                                    </label>

                                    <input type="text" class="form-control" value="Sistema de Gestão MEDINV">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Quem Somos
                                    </label>

                                    <textarea class="form-control"
                                        rows="6">A MEDINV é uma empresa portuguesa especializada no desenvolvimento de software para a área da saúde. Nascemos da necessidade real que os hospitais enfrentam diariamente: gerir centenas de equipamentos médicos de forma eficaz, segura e centralizada.</textarea>

                                </div>

                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        O problema
                                    </label>

                                    <textarea class="form-control"
                                        rows="4">Em muitas instituições de saúde, a gestão do inventário ainda depende de folhas de Excel dispersas, registos em papel e documentação sem estrutura. Esta realidade compromete a rastreabilidade dos equipamentos, dificulta auditorias e fragiliza a tomada de decisão clínica e administrativa.</textarea>

                                </div>
                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        A resposta
                                    </label>

                                    <textarea class="form-control"
                                        rows="4">Desenvolvemos soluções web intuitivas e centralizadas, pensadas para a realidade hospitalar portuguesa. A nossa plataforma permite organizar, consultar e atualizar toda a informação relativa ao parque tecnológico de uma instituição de saúde — desde a aquisição de um equipamento até ao seu abate.</textarea>

                                </div>

                            </div>

                        </div>

                    </div>

                    <!-- Contactos -->
                    <div class="col-lg-6">

                        <div class="card shadow-sm h-100">

                            <div class="card-header">

                                <h5 class="mb-0">

                                    <i class="fas fa-address-book me-2"></i>
                                    Contactos

                                </h5>

                            </div>

                            <div class="card-body">

                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Telefone
                                    </label>

                                    <input type="text" class="form-control" value="222 333 444">

                                </div>

                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Email
                                    </label>

                                    <input type="email" class="form-control" value="geral@medinv.pt">

                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Morada
                                    </label>

                                    <textarea class="form-control" rows="3">Rua da Tecnologia Médica</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            Código postal
                                        </label>

                                        <input type="text" class="form-control" value="4200-072"
                                            placeholder="Ex.: 4200-072">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label fw-bold">
                                            Localidade
                                        </label>

                                        <input type="text" class="form-control" value="Porto" placeholder="Ex.: Porto">
                                    </div>
                                </div>

                                <div class="mb-3">

                                    <label class="form-label fw-bold">
                                        Horário de atendimento
                                    </label>

                                    <input type="text" class="form-control"
                                        value="2ª–6ª: 7h — 21h Sáb: 9h — 15h Dom: Encerrado">

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </main>

        </div>

    </div>

<!-- rodapé -->
<?php include '../../includes/footer.php'; ?>