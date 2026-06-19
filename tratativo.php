<?php
include("conexao.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $mysqli->real_escape_string($_POST['email']);
    $senha = $_POST['senha'];

    // Busca o usuário no banco
    $sql = "SELECT id, nome FROM usuarios WHERE email = ? AND senha = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("ss", $email, $senha);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();

        // Inicia a sessão para o sistema saber que o usuário está logado
        if(!isset($_SESSION)) {
            session_start();
        }
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nome'] = $usuario['nome'];

        // REDIRECIONAMENTO para a página principal/restrita
        header("Location: painel.php");
        exit(); 

    } else {
        echo "Email ou senha incorretos! <a href='login.php'>Tentar novamente</a>";
    }
}
?>