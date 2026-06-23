<?php
// controller/LoginController.php

class LoginController {
    
    /**
     * Exibe a tela de login (Método GET)
     */
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); //
        }

        // Se já houver login ativo, redireciona de forma inteligente baseado no perfil
        if (isset($_SESSION['usuario_id']) && isset($_SESSION['usuario_tipo'])) {
            if ($_SESSION['usuario_tipo'] === 'admin') {
                header('Location: index.php?url=painel-admin'); //
            } else {
                header('Location: index.php?url=painel'); //
            }
            exit(); //
        }

        $mensagemErro = isset($_SESSION['erro_login']) ? $_SESSION['erro_login'] : ''; //
        unset($_SESSION['erro_login']); //

        $tituloPagina = "GRID&GOL - Entrar"; //

        if (file_exists(__DIR__ . '/../views/login.php')) {
            require_once __DIR__ . '/../views/login.php'; //
        } else {
            die("Erro crítico: A view 'login.php' não foi encontrada."); //
        }
    }

    /**
     * Exibe o formulário de Registo/Cadastro de Clientes (Método GET)
     */
    public function cadastro() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Se o utilizador já estiver autenticado, não faz sentido ver o formulário de registo
        if (isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=home');
            exit();
        }

        $mensagemErro = isset($_SESSION['erro_cadastro']) ? $_SESSION['erro_cadastro'] : '';
        $mensagemSucesso = isset($_SESSION['sucesso_cadastro']) ? $_SESSION['sucesso_cadastro'] : '';
        
        unset($_SESSION['erro_cadastro']);
        unset($_SESSION['sucesso_cadastro']);

        $tituloPagina = "GRID&GOL - Cadastre-se";

        if (file_exists(__DIR__ . '/../views/cadastre-se.php')) {
            require_once __DIR__ . '/../views/cadastre-se.php';
        } else {
            die("Erro crítico: A view 'cadastro.php' não foi encontrada no diretório de visualizações.");
        }
    }

    /**
     * Processa a criação e persistência de uma nova conta de Cliente (Método POST)
     */
    public function salvarCadastro() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=cadastre-se');
            exit();
        }

        // Higienização e captura das informações enviadas via formulário
        $nome  = isset($_POST['nome']) ? trim($_POST['nome']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $senha = isset($_POST['senha']) ? trim($_POST['senha']) : '';
        $cpf   = isset($_POST['cpf']) ? trim($_POST['cpf']) : ''; // Se utilizares validação de CPF ou BI

        // Validação de campos vazios obrigatórios
        if (empty($nome) || empty($email) || empty($senha)) {
            $_SESSION['erro_cadastro'] = "Por favor, preencha todos os campos obrigatórios.";
            header('Location: index.php?url=cadastre-se');
            exit();
        }

        // Injeção do modelo de Clientes para verificação e persistência
        if (file_exists(__DIR__ . '/../model/ClienteModel.php')) {
            require_once __DIR__ . '/../model/ClienteModel.php';
        } else {
            die("Erro crítico: O arquivo 'model/ClienteModel.php' não foi encontrado.");
        }

        $clienteModel = new ClienteModel();

        // Evita a duplicidade de contas verificando se o e-mail já existe
        if ($clienteModel->buscarPorEmail($email)) {
            $_SESSION['erro_cadastro'] = "Este endereço de e-mail já se encontra registado no sistema.";
            header('Location: index.php?url=cadastre-se');
            exit();
        }

        // Geração da Hash de Criptografia Segura nativa do PHP
        $senhaCriptografada = password_hash($senha, PASSWORD_DEFAULT);

        // Array estruturado para passagem de parâmetros seguros para o Model
        $dadosCliente = [
            'nome'  => $nome,
            'email' => $email,
            'senha' => $senhaCriptografada,
            'cpf'   => $cpf
        ];

        // Executa o método de inserção na base de dados (certifica-te que o teu ClienteModel possui a função correspondente)
        // Se o teu método no model se chamar apenas 'salvar' ou 'inserir', ajusta a linha abaixo:
        $cadastroRealizado = $clienteModel->salvar($dadosCliente);

        if ($cadastroRealizado) {
            $_SESSION['sucesso_cadastro'] = "Conta criada com sucesso! Faça o seu login.";
            header('Location: index.php?url=login');
        } else {
            $_SESSION['erro_cadastro'] = "Ocorreu um erro interno ao processar o seu registo. Tente novamente.";
            header('Location: index.php?url=cadastre-se');
        }
        exit();
    }

    /**
     * Processa a autenticação híbrida das duas tabelas (Método POST)
     */
    public function autenticar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); //
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=login'); //
            exit(); //
        }

        $email = isset($_POST['email']) ? trim($_POST['email']) : ''; //
        $senha = isset($_POST['senha']) ? trim($_POST['senha']) : ''; //

        if (empty($email) || empty($senha)) {
            $_SESSION['erro_login'] = "Por favor, preencha todos os campos."; //
            header('Location: index.php?url=login'); //
            exit(); //
        }

        // --- 1ª TENTATIVA: CLIENTE ---
        if (file_exists(__DIR__ . '/../model/ClienteModel.php')) {
            require_once __DIR__ . '/../model/ClienteModel.php'; //
        }
        
        $clienteModel = new ClienteModel(); //
        $usuarioCliente = $clienteModel->buscarPorEmail($email); //

        if ($usuarioCliente) {
            $senhaValida = false; //
            $precisaAtualizarHash = false; //

            // Se o valor começar com '$2y$', é uma senha devidamente criptografada via password_hash
            if (strpos($usuarioCliente['senha'], '$2y$') === 0) { //
                if (password_verify($senha, $usuarioCliente['senha'])) { //
                    $senhaValida = true; //
                    if (password_needs_rehash($usuarioCliente['senha'], PASSWORD_DEFAULT)) { //
                        $precisaAtualizarHash = true; //
                    }
                }
            } else {
                // Senha legível/antiga legada encontrada no banco
                if ($senha === $usuarioCliente['senha']) { //
                    $senhaValida = true; //
                    $precisaAtualizarHash = true; //
                }
            }

            if ($senhaValida) { //
                // MIGRAÇÃO EM TEMPO DE EXECUÇÃO: Salva o hash seguro automaticamente
                if ($precisaAtualizarHash) { //
                    $novoHash = password_hash($senha, PASSWORD_DEFAULT); //
                    $clienteModel->atualizarSenha($usuarioCliente['id_cliente'], $novoHash); //
                }

                session_regenerate_id(true); //
                $_SESSION['usuario_id']    = $usuarioCliente['id_cliente']; //
                $_SESSION['usuario_nome']  = $usuarioCliente['nome']; //
                $_SESSION['usuario_email'] = $usuarioCliente['email']; //
                $_SESSION['usuario_tipo']  = 'cliente'; //

                header('Location: index.php?url=painel'); //
                exit(); //
            }
        }

        // --- 2ª TENTATIVA: ADMINISTRADOR ---
        if (file_exists(__DIR__ . '/../model/AdminModel.php')) {
            require_once __DIR__ . '/../model/AdminModel.php'; //
        }

        $adminModel = new AdminModel(); //
        $usuarioAdmin = $adminModel->buscarPorEmail($email); //

        if ($usuarioAdmin) {
            $senhaValida = false; //
            $precisaAtualizarHash = false; //

            if (strpos($usuarioAdmin['senha'], '$2y$') === 0) { //
                if (password_verify($senha, $usuarioAdmin['senha'])) { //
                    $senhaValida = true; //
                    if (password_needs_rehash($usuarioAdmin['senha'], PASSWORD_DEFAULT)) { //
                        $precisaAtualizarHash = true; //
                    }
                }
            } else {
                if ($senha === $usuarioAdmin['senha']) { //
                    $senhaValida = true; //
                    $precisaAtualizarHash = true; //
                }
            }

            if ($senhaValida) { //
                // MIGRAÇÃO EM TEMPO DE EXECUÇÃO DO ADMINISTRADOR
                if ($precisaAtualizarHash) { //
                    $novoHash = password_hash($senha, PASSWORD_DEFAULT); //
                    
                    // Conexão direta rápida para atualização preventiva na tabela de administradores
                    $db = Database::getConexao(); //
                    $stmt = $db->prepare("UPDATE administrador SET senha = :senha WHERE id_admin = :id"); //
                    $stmt->execute([':senha' => $novoHash, ':id' => $usuarioAdmin['id_admin']]); //
                }

                session_regenerate_id(true); //
                $_SESSION['usuario_id']    = $usuarioAdmin['id_admin']; //
                $_SESSION['usuario_nome']  = $usuarioAdmin['nome']; //
                $_SESSION['usuario_email'] = $usuarioAdmin['email']; //
                $_SESSION['usuario_tipo']  = 'admin'; //

                header('Location: index.php?url=painel-admin'); //
                exit(); //
            }
        }

        // Se ambos falharem
        $_SESSION['erro_login'] = "E-mail ou senha inválidos."; //
        header('Location: index.php?url=login'); //
        exit(); //
    }

    /**
     * Limpa de forma segura os dados da sessão corrente (Destrói Login)
     */
    public function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start(); //
        }
        session_unset(); //
        session_destroy(); //
        header('Location: index.php?url=home'); //
        exit(); //
    }
}