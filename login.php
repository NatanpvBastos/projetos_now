<?php
require_once("conexao.php");

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

<header>
    <div class="logo">GRID&GOL</div>
    <nav>
        <a href="index.php">Início</a>
        <a href="futebol.php">Futebol</a>
        <a href="formula1.php">Formula 1</a>
        <a href="contato.php">Contato</a>
    </nav>
</header>

<section class="login">

    <div class="form-container">

        <h1>Login 🔐</h1>
        <p>Entre com seu email e senha</p>

        <form> 
            <form action="tratativo.php" method="post">
                
            <input type="email" placeholder="Email" required>
            <input type="password" placeholder="Senha" required>

            <button type="submit">Entrar</button>
        </form>

        <p>
            Não tem uma conta? 
            <a href="cadastre-se.php">Cadastre-se</a>
        </p>

    </div>

</section>

<footer>
    <p>© 2026 GRID&GOL - Todos os direitos reservados</p>
</footer>

</body>
</html>