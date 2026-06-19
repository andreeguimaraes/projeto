<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../private/includes/validacoes.php';
$sucesso = '';
$erros = [];

$nome = '';
$email = '';
$telefone = '';
$assunto = '';
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $assunto = trim($_POST['assunto'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    $erros = array_merge(
        validar_nome_contacto($nome),
        validar_email_contacto_publico($email),
        validar_telefone_contacto($telefone),
        validar_assunto_contacto($assunto),
        validar_mensagem_contacto($mensagem)
    );

    if (empty($erros)) {
        try {
            $ligacao = new PDO(
                "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
                MYSQL_USERNAME,
                MYSQL_PASSWORD
            );
            $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $ligacao->prepare("
                INSERT INTO mensagens_contacto (nome, email, telefone, assunto, mensagem)
                VALUES (:nome, :email, :telefone, :assunto, :mensagem)
            ");

            $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':telefone', $telefone, PDO::PARAM_STR);
            $stmt->bindParam(':assunto', $assunto, PDO::PARAM_STR);
            $stmt->bindParam(':mensagem', $mensagem, PDO::PARAM_STR);
            $stmt->execute();

            $sucesso = 'Mensagem enviada com sucesso. Entraremos em contacto brevemente.';

            // Limpa os campos depois de enviar com sucesso
            $nome = '';
            $email = '';
            $telefone = '';
            $assunto = '';
            $mensagem = '';

        } catch (PDOException $e) {
            $erros[] = "Erro ao enviar a mensagem. Tente novamente mais tarde.";
        }

        $ligacao = null;
    }
}
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactos - MEDINV</title>

    <!-- favicon -->
    <link rel="icon" type="image/svg+xml" href="../../../assets/img/logo_medinv_icon.svg">

    <link rel="stylesheet" href="../../../assets/fontawesome/all.min.css">
    <!-- estilos de pagina -->
    <link rel="stylesheet" href="../../../assets/css/1240722.css">

</head>
<body>
    <!-- Navegação -->
    <nav class="bng-navbar">
        <!-- Marca -->
        <div class="navbar-brand">
            <div class="brand-text">
                <img src="../../../assets/img/logo_medinv.svg" alt="Logo da empresa">
            </div>
        </div>

        <!-- Links da navegação -->
        <div class="container-navegacao">
            <a href="../../index.php">INÍCIO</a>
            <a href="../quem-somos/quem-somos.php">QUEM SOMOS</a>
            <a href="../servicos/servicos.php">SERVIÇOS</a>
            <a href="../contactos/contactos.php">CONTACTOS</a>
        </div>

        <!-- Área Cliente -->
        <div class="nav-cliente">
            <a href="../../login.php" target="_blank">Área Restrita</a>
        </div>
    </nav>

    <!-- Seção Banner/Hero com Foto -->
    <section class="hero-banner">
        <img src="../../../assets/img/waiting.room.jpg" alt="Banner Contactos - MEDINV" class="hero-image">
        <div class="hero-overlay">
            <h2>Entre em Contacto</h2>
            <p>Estamos aqui para ajudar</p>
        </div>
    </section>

    <!-- Seção "Contacto" -->
    <section id="contacto">
        <div class="contacto-container">
            <!-- Informações de Contacto -->
            <div class="contacto-info">
                <h3>Informações de Contacto</h3>

                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div>
                        <h4>Morada</h4>
                        <p>Rua de António Bernardino, 431, Porto<br>4000-000 Porto<br>Portugal</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <div>
                        <h4>Telefone</h4>
                        <p>+351 912 345 678</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>Email</h4>
                        <p>geral@medinv.pt<br>info@medinv.pt</p>
                    </div>
                </div>

                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div>
                        <h4>Horário</h4>
                        <p>2ª–6ª: 7h — 21h<br>Sáb: 9h — 15h<br>Dom: Encerrado</p>
                    </div>
                </div>

                <div class="social-links">
                    <h4>Siga-nos nas Redes Sociais</h4>
                    <a href="#" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                </div>
            </div>

            <!-- Formulário de Contacto -->
            <div class="contacto-form">
                <h3>Envie-nos uma Mensagem</h3>

                <?php if (!empty($sucesso)): ?>
                    <div class="form-alert form-alert-success">
                        <?= htmlspecialchars($sucesso) ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($erros)): ?>
                    <div class="form-alert form-alert-error">
                        <?php foreach ($erros as $erro): ?>
                            <div><?= htmlspecialchars($erro) ?></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <form id="contactForm" method="post" action="contactos.php" novalidate>
                    <div class="form-group">
                        <label for="nome">Nome *</label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="telefone">Telefone</label>
                        <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($telefone) ?>">
                    </div>

                    <div class="form-group">
                        <label for="assunto">Assunto *</label>
                        <select id="assunto" name="assunto" required>
                            <option value="">Selecione...</option>
                            <option value="duvida" <?= $assunto === 'duvida' ? 'selected' : '' ?>>Dúvida Geral</option>
                            <option value="demonstracao" <?= $assunto === 'demonstracao' ? 'selected' : '' ?>>Pedido de Demonstração</option>
                            <option value="orcamento" <?= $assunto === 'orcamento' ? 'selected' : '' ?>>Pedido de Orçamento</option>
                            <option value="suporte" <?= $assunto === 'suporte' ? 'selected' : '' ?>>Suporte Técnico</option>
                            <option value="parceria" <?= $assunto === 'parceria' ? 'selected' : '' ?>>Parceria</option>
                            <option value="outro" <?= $assunto === 'outro' ? 'selected' : '' ?>>Outro</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="mensagem">Mensagem *</label>
                        <textarea id="mensagem" name="mensagem" rows="5" required><?= htmlspecialchars($mensagem) ?></textarea>
                    </div>

                    <button type="submit" class="button">Enviar Mensagem</button>
                </form>
            </div>
        </div>
    </section>

   <!-- Rodapé -->
    <footer class="footer-container">
        <div class="footer-section">
            <strong>LOCALIZAÇÃO</strong>
            <p>Rua de António Bernardino, 431, Porto<br>4200-009, Porto<br>Portugal</p>
        </div>
        <div class="footer-section">
            <strong>HORÁRIO</strong>
            <p>2ª–6ª: 7h — 21h<br>Sáb: 9h — 15h<br>Dom: Encerrado</p>
        </div>
        <div class="footer-section">
            <strong>CONTACTOS</strong>
            <p>Email: geral@medinv.pt<br>Tel: +351 912 345 678</p>
        </div>
    </footer>

</body>
</html>