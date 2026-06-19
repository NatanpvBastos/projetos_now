<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<header>
    <div class="logo">GRID&GOL</div>
    <nav>
        <a href="index.html">Início</a>
        <a href="futebol.html">Futebol</a>
        <a href="formula1.html">Formula 1</a>
        <a href="contato.html">Contato</a>
    </nav>
</header>

<section class="login">

    <div class="form-container">

        <h1>Cadastro 🔐</h1>
        <p>Crie sua conta</p>

       <form action="cadastrar.php" method="POST">
        <input type="text" name="nome" placeholder="Nome" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="senha" placeholder="Senha" required>
        <button type="submit">Cadastrar</button>
        </form>

        <p>Já tem conta? <a href="login.php">Login</a></p>
    </div>
</section>
</body>
</html>