<?php
if(!isset($_SESSION)) {
    session_start();
}

// Verifica se existe uma sessão iniciada
if(!isset($_SESSION['id'])) {
    die("Você não pode acessar esta página porque não está logado. <a href='login.php'>Fazer Login</a>");
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel - GRID&GOL</title>
</head>
<body>
    <h1>Bem-vindo ao Painel, <?php echo $_SESSION['nome']; ?>!</h1>
    <p>Você está logado com sucesso.</p>
    <a href="logout.php">Sair</a>
</body>
</html>