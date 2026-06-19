```php
<?php

include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $senha = $_POST['senha'];

    if (empty($nome) || empty($email) || empty($senha)) {
        die("Por favor, preencha todos os campos.");
    }

    // Verifica se já existe
    $sql_check = "SELECT id FROM usuarios WHERE email = ?";
    $stmt_check = $mysqli->prepare($sql_check);

    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {

        echo "
        <h2>E-mail já cadastrado!</h2>
        <a href='cadastre-se.php'>Voltar</a>
        ";

        $stmt_check->close();

    } else {

        $stmt_check->close();

        // Criptografa a senha
        $senha_hash = password_hash(
            $senha,
            PASSWORD_DEFAULT
        );

        $sql_insert =
        "INSERT INTO usuarios
        (nome,email,senha)
        VALUES (?,?,?)";

        $stmt_insert =
        $mysqli->prepare($sql_insert);

        $stmt_insert->bind_param(
            "sss",
            $nome,
            $email,
            $senha_hash
        );

        if ($stmt_insert->execute()) {

            echo "
            <h2>Cadastro realizado com sucesso! ✅</h2>

            <p>
                Bem-vindo à GRID GOL.
            </p>

            <a href='login.php'>
                Fazer Login
            </a>
            ";

        } else {

            echo
            "Erro ao cadastrar: "
            . $mysqli->error;
        }

        $stmt_insert->close();
    }

    $mysqli->close();
}
?>
```
