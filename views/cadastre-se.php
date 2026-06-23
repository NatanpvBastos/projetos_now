<?php
// views/cadastre-se.php
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($tituloPagina) ? $tituloPagina : "GRID&GOL - Cadastre-se"; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/cadastro.css">
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
        <a href="index.php?url=login" class="icon"><i class="fas fa-user"></i> Login</a>
        <a href="index.php?url=cadastre-se" style="color: var(--primary);"><i class="fas fa-user-plus"></i> Cadastro</a>
        <a href="#" class="icon"><i class="fas fa-shopping-cart"></i></a>
    </div>
</header>

<main class="cadastro-wrapper">
    <div class="cadastro-card">
        <h2>Crie sua Conta</h2>
        <p class="subtitle">Faça parte do GRID&GOL para realizar suas compras rapidamente</p>

        <?php if (!empty($mensagemErro)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($mensagemErro); ?>
            </div>
        <?php endif; ?>

        <form action="index.php?url=salvar-cadastro" method="POST">
            
            <div class="form-group">
                <label for="nome">Nome Completo</label>
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="nome" name="nome" placeholder="Ex: João da Silva" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">E-mail</label>
                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="seu@email.com" required>
                </div>
            </div>

            <div class="form-group">
                <label for="telefone">Telefone / WhatsApp</label>
                <div class="input-group">
                    <i class="fas fa-phone"></i>
                    <input type="text" id="telefone" name="telefone" placeholder="Ex: (11) 99999-8888" required>
                </div>
            </div>

            <div class="form-group">
                <label for="endereco">Endereço Completo</label>
                <div class="input-group">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" id="endereco" name="endereco" placeholder="Rua, Número, Bairro, Cidade - UF" required>
                </div>
            </div>

            <div class="form-group">
                <label for="senha">Senha</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="senha" name="senha" placeholder="Mínimo 6 caracteres" minlength="6" required>
                </div>
            </div>

            <div class="form-group">
                <label for="confirmar_senha">Confirmar Senha</label>
                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="confirmar_senha" name="confirmar_senha" placeholder="Repita a senha" minlength="6" required>
                </div>
            </div>

            <button type="submit" class="btn-cadastro">Finalizar Cadastro</button>
        </form>

        <div class="cadastro-footer">
            Já possui uma conta? <a href="index.php?url=login">Faça o login aqui</a>
        </div>
    </div>
</main>

<footer>
    <p>© <?php echo date('Y'); ?> GRID&GOL. Todos os direitos reservados.</p>
</footer>

</body>
</html>