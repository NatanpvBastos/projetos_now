<?php
// views/login.php
// OBSERVAÇÃO MVC: As variáveis $mensagemErro, $mensagemSucesso e as sessões
// são preparadas e injetadas aqui exclusivamente pelo LoginController.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : "GRID&GOL - Login"; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/login.css">
</head>
<body>

<header>
    <div class="logo">GRID&GOL</div>
    <nav>
        <a href="index.php">Início</a>
        <a href="index.php?url=futebol">Futebol</a>
        <a href="index.php?url=formula1">Fórmula 1</a>
        <a href="index.php?url=contato">Contato</a>
    </nav>
    <div class="user-actions">
        <a href="index.php?url=login" style="color: var(--primary);"><i class="fas fa-user"></i> Login</a>
        <a href="index.php?url=cadastre-se" class="icon"><i class="fas fa-user-plus"></i> Cadastro</a>
        <a href="#" class="icon"><i class="fas fa-shopping-cart"></i></a>
    </div>
</header>

<main class="login-wrapper">
    <div class="login-card">
        <h2>Acesse sua Conta</h2>
        <p class="subtitle">Insira suas credenciais para entrar no GRID&GOL</p>

        <?php if (!empty($mensagemErro)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($mensagemErro); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['sucesso_cadastro'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php 
                    echo htmlspecialchars($_SESSION['sucesso_cadastro']); 
                    unset($_SESSION['sucesso_cadastro']); // Limpa para não repetir
                ?>
            </div>
        <?php endif; ?>

        <form action="index.php?url=autenticar" method="POST">
            <div class="form-group">
                <label for="email">E-mail</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                </div>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="senha" name="senha" placeholder="••••••••" required>
                </div>
            </div>

            <button type="submit" class="btn-login">Entrar</button>
        </form>

        <div class="login-footer">
            Não tem uma conta? <a href="index.php?url=cadastre-se">Cadastre-se aqui</a>
        </div>
    </div>
</main>

<footer>
    <p>© <?php echo date('Y'); ?> GRID&GOL. Todos os direitos reservados.</p>
</footer>

</body>
</html>