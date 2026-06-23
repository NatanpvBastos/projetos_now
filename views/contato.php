<?php
// views/contato.php
// As variáveis $tituloPagina e o controle de sessão vêm do ContatoController.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : "GRID&GOL - Contato"; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/contato.css">

</head>
<body>

<header>
    <div class="logo">GRID&GOL</div>
    <nav>
        <a href="index.php">Início</a>
        <a href="index.php?url=futebol">Futebol</a>
        <a href="index.php?url=formula1">Fórmula 1</a>
        <a href="index.php?url=contato" style="color: var(--primary);">Contato</a>
    </nav>
    <div class="user-actions">
        <?php if (isset($_SESSION['usuario_nome'])): ?>
            <a href="index.php?url=painel" class="icon"><i class="fas fa-user"></i> Olá, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?></a>
            <a href="index.php?url=logout" class="icon"><i class="fas fa-sign-out-alt"></i> Sair</a>
        <?php else: ?>
            <a href="index.php?url=login" class="icon"><i class="fas fa-user"></i> Login</a>
            <a href="index.php?url=cadastre-se" class="icon"><i class="fas fa-user-plus"></i> Cadastro</a>
        <?php endif; ?>
        <a href="#" class="icon"><i class="fas fa-shopping-cart"></i></a>
    </div>
</header>

<main class="contact-container">
    <div class="contact-info">
        <h2>Fale Conosco</h2>
        <p>Tem alguma dúvida sobre um produto, pedido ou quer fazer uma sugestão? Nossa equipe de suporte está pronta para ajudar você.</p>
        
        <div class="info-item">
            <i class="fa-solid fa-map-marker-alt"></i>
            <div>
                <h4>Endereço</h4>
                <p>Av. do Desporto, 123 - Centro, São Paulo - SP</p>
            </div>
        </div>

        <div class="info-item">
            <i class="fa-solid fa-phone"></i>
            <div>
                <h4>Telefone / WhatsApp</h4>
                <p>(11) 99999-8888</p>
            </div>
        </div>

        <div class="info-item">
            <i class="fa-solid fa-envelope"></i>
            <div>
                <h4>E-mail Oficial</h4>
                <p>suporte@gridandgol.com</p>
            </div>
        </div>

        <div class="info-item">
            <i class="fa-solid fa-clock"></i>
            <div>
                <h4>Horário de Funcionamento</h4>
                <p>Segunda à Sexta: 9h às 18h</p>
            </div>
        </div>
    </div>

    <div class="contact-form">
        <h2>Envie uma Mensagem</h2>
        <form action="#" method="POST">
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <input type="text" id="nome" name="nome" placeholder="Ex: João Silva" required>
            </div>

            <div class="form-group">
                <label for="email">Seu E-mail</label>
                <input type="email" id="email" name="email" placeholder="Ex: joao@email.com" required>
            </div>

            <div class="form-group">
                <label for="assunto">Assunto</label>
                <input type="text" id="assunto" name="assunto" placeholder="Ex: Dúvida sobre tamanho" required>
            </div>

            <div class="form-group">
                <label for="mensagem">Mensagem</label>
                <textarea id="mensagem" name="mensagem" rows="5" placeholder="Digite detalhadamente o que precisa..." required></textarea>
            </div>

            <button type="submit" class="btn-submit">Enviar Mensagem</button>
        </form>
    </div>
</main>

<footer>
    <p>© <?php echo date('Y'); ?> GRID&GOL. Todos os direitos reservados.</p>
</footer>

</body>
</html>