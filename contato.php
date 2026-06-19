<?php
$mensagemStatus = "";

// Se veio um pedido de login (ex: login.php?status=login)
if (isset($_GET['status']) && $_GET['status'] === 'login') {
    $mensagemStatus = "🔐 Pedido de login recebido com sucesso!";
}

// Se enviou o formulário de contato
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $mensagem = trim($_POST['mensagem'] ?? '');

    if ($nome && $email && $mensagem) {

        // Aqui você pode salvar no banco ou enviar e-mail
        // Exemplo simples de confirmação:
        $mensagemStatus = "📩 Mensagem enviada com sucesso!";

        // Exemplo de envio por e-mail (opcional):
        // mail("gridgol@gmail.com", "Contato do site", $mensagem, "From: $email");

    } else {
        $mensagemStatus = "⚠️ Preencha todos os campos corretamente.";
    }
}
?>
```php
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato | GRID GOL</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>

    <div class="logo">
        GRID GOL
    </div>

    <nav>
        <a href="index.php">Início</a>
        <a href="futebol.php">Futebol</a>
        <a href="formula1.php">Fórmula 1</a>
        <a href="contato.php">Contato</a>
        <a href="login.php">Login</a>
    </nav>

</header>

<section class="hero">

    <h1>Fale Conosco 📩</h1>
<?php if (!empty($mensagemStatus)): ?>
    <p style="background:#222;color:#fff;padding:10px;border-radius:8px;">
        <?php echo $mensagemStatus; ?>
    </p>
<?php endif; ?>
    <p>
        Tem dúvidas, sugestões ou precisa de ajuda?
        Nossa equipe está pronta para atender você.
    </p>

</section>

<section class="contato">

    <form action="#" method="POST">

        <input
            type="text"
            name="nome"
            placeholder="Seu nome"
            required
        >

        <input
            type="email"
            name="email"
            placeholder="Seu e-mail"
            required
        >

        <textarea
            name="mensagem"
            placeholder="Digite sua mensagem"
            required
        ></textarea>

        <button type="submit">
            Enviar Mensagem
        </button>

    </form>

</section>

<section class="beneficios">

    <h2>Informações de Contato</h2>

    <div class="grid">

        <div class="card">
            <h3>📍 Localização</h3>
            <p>Rio de Janeiro - RJ</p>
        </div>

        <div class="card">
            <h3>📧 E-mail</h3>
            <p>gridgol@gmail.com</p>
        </div>

        <div class="card">
            <h3>📞 Telefone</h3>
            <p>(21) 97503-8416</p>
        </div>

    </div>

</section>

<section class="cta">

    <h2>GRID GOL ARTIGOS ESPORTIVOS</h2>

    <p>
        Futebol ⚽ • Fórmula 1 🏎️ • Qualidade • Segurança
    </p>

</section>

<footer>
    <p>
        GRID GOL ARTIGOS ESPORTIVOS © 2026
    </p>
</footer>

</body>
</html>
```
