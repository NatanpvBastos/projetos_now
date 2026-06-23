<?php
// controller/CadastroController.php

class CadastroController {
    
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=painel');
            exit();
        }

        $mensagemErro = isset($_SESSION['erro_cadastro']) ? $_SESSION['erro_cadastro'] : null;
        unset($_SESSION['erro_cadastro']);

        $tituloPagina = "GRID&GOL - Cadastre-se";

        if (file_exists(__DIR__ . '/../views/cadastre-se.php')) {
            require_once __DIR__ . '/../views/cadastre-se.php';
        } else {
            die("Erro crítico: A view 'cadastre-se.php' não foi encontrada.");
        }
    }

    public function salvar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=cadastre-se');
            exit();
        }

        // Captura e sanitiza todos os dados enviados do formulário
        $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS); // CAPTURADO
        $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_SPECIAL_CHARS); // CAPTURADO
        $senha = $_POST['senha'] ?? '';
        $confirmarSenha = $_POST['confirmar_senha'] ?? '';
        
        $data_cadastro = date('Y-m-d H:i:s');

        // 1. Validação de consistência de campos vazios
        if (empty($nome) || empty($email) || empty($telefone) || empty($endereco) || empty($senha)) {
            $_SESSION['erro_cadastro'] = "Por favor, preencha todos os campos obrigatórios.";
            header('Location: index.php?url=cadastre-se');
            exit();
        }

        if ($senha !== $confirmarSenha) {
            $_SESSION['erro_cadastro'] = "As senhas digitadas não coincidem.";
            header('Location: index.php?url=cadastre-se');
            exit();
        }

        if (file_exists(__DIR__ . '/../model/ClienteModel.php')) {
            require_once __DIR__ . '/../model/ClienteModel.php';
        } else {
            die("Erro crítico: O arquivo 'model/ClienteModel.php' não foi encontrado.");
        }

        $clienteModel = new ClienteModel();

        if ($clienteModel->buscarPorEmail($email)) {
            $_SESSION['erro_cadastro'] = "Este e-mail já está cadastrado em nosso sistema.";
            header('Location: index.php?url=cadastre-se');
            exit();
        }

        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

        // Envia todos os dados reais coletados para o Model persistir
        $sucesso = $clienteModel->inserir($nome, $email, $senhaCriptografada, $telefone, $endereco, $data_cadastro);

        if ($sucesso) {
            $_SESSION['sucesso_cadastro'] = "Conta criada com sucesso! Faça seu login.";
            header('Location: index.php?url=login');
            exit();
        } else {
            $_SESSION['erro_cadastro'] = "Erro ao processar o cadastro no banco de dados. Tente novamente.";
            header('Location: index.php?url=cadastre-se');
            exit();
        }
    }
}