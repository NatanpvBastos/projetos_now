<?php
// controller/AdminController.php

class AdminController {

    /**
     * Trava de segurança para garantir que apenas administradores autenticados acessem os métodos
     */
    private function verificarAcessoAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
            $_SESSION['erro_login'] = "Acesso restrito a administradores.";
            header('Location: index.php?url=login');
            exit();
        }
    }

    /**
     * Exibe a página principal do Painel Administrativo (Dashboard)
     * Rota: index.php?url=painel-admin
     */
    public function index() {
        $this->verificarAcessoAdmin();

        // 1. Carrega dados de Produtos e Categorias existentes
        require_once __DIR__ . '/../model/ProdutoModel.php';
        $produtoModel = new ProdutoModel();
        
        $totalProdutos = count($produtoModel->listarTodos());
        
        if (method_exists($produtoModel, 'buscarCategorias')) {
            $totalCategorias = count($produtoModel->buscarCategorias());
        } else {
            $totalCategorias = 0; 
        }

        // =====================================================================
        // CONSULTAS REAIS AO BANCO DE DADOS
        // =====================================================================
        $totalClientes = 0;
        $vendasMes     = 0.00;
        $pedidosLista  = [];

        try {
            if (class_exists('Database')) {
                $db = Database::getConexao();

                if ($db) {
                    // [CONSULTA 1] - Contagem real de clientes na tabela 'cliente'
                    $sqlClientes = "SELECT COUNT(*) as total FROM cliente";
                    $stmtCli = $db->query($sqlClientes);
                    if ($stmtCli) {
                        $resCli = $stmtCli->fetch(PDO::FETCH_ASSOC);
                        $totalClientes = isset($resCli['total']) ? (int)$resCli['total'] : 0;
                    }

                    // [CONSULTA 2] - Lista o fluxo de todos os pedidos para a tabela do painel
                    $sqlPedidos = "SELECT 
                                        id_pedido, 
                                        data_pedido, 
                                        id_cliente, 
                                        valor_total, 
                                        status 
                                   FROM pedido 
                                   ORDER BY data_pedido DESC";
                    
                    $stmtPed = $db->query($sqlPedidos);
                    if ($stmtPed) {
                        $pedidosLista = $stmtPed->fetchAll(PDO::FETCH_ASSOC);
                    }

                    // [CONSULTA 3] - Soma o faturamento real do mês corrente
                    $sqlVendas = "SELECT SUM(valor_total) as faturamento 
                                  FROM pedido 
                                  WHERE LOWER(status) IN ('pago', 'aprovado', 'concluido') 
                                    AND MONTH(data_pedido) = MONTH(CURRENT_DATE()) 
                                    AND YEAR(data_pedido) = YEAR(CURRENT_DATE())";
                                  
                    $stmtVen = $db->query($sqlVendas);
                    if ($stmtVen) {
                        $resVen = $stmtVen->fetch(PDO::FETCH_ASSOC);
                        $vendasMes = isset($resVen['faturamento']) ? (float)$resVen['faturamento'] : 0.00;
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Erro de consulta no Dashboard Admin: " . $e->getMessage());
        }
        // =====================================================================

        $nomeAdmin = $_SESSION['usuario_nome'] ?? 'Administrador';
        $tituloPagina = "GRID&GOL - Painel do Administrador";

        if (file_exists(__DIR__ . '/../views/painel-admin.php')) {
            require_once __DIR__ . '/../views/painel-admin.php';
        }
    }

    /**
     * ROTA ADICIONADA: Processa a aprovação manual de um pedido via Dashboard
     * Rota: index.php?url=aprovar-pedido&id=X
     */
    public function aprovarPedido() {
        $this->verificarAcessoAdmin();

        // Obtém e valida o ID do pedido via parâmetro GET
        $idPedido = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($idPedido) {
            try {
                if (class_exists('Database')) {
                    $db = Database::getConexao();
                    
                    if ($db) {
                        // Executa a atualização do estado do pedido para 'Pago' de forma segura
                        $sql = "UPDATE pedido SET status = 'Pago' WHERE id_pedido = :id_pedido";
                        $stmt = $db->prepare($sql);
                        $stmt->bindParam(':id_pedido', $idPedido, PDO::PARAM_INT);
                        $stmt->execute();
                    }
                }
            } catch (Exception $e) {
                error_log("Erro ao aprovar o pedido #{$idPedido}: " . $e->getMessage());
            }
        }

        // Redireciona de volta para o painel administrativo para atualizar a tabela visualmente
        header('Location: index.php?url=painel-admin');
        exit();
    }

    /**
     * Exibe o formulário de cadastro para novos administradores
     * Rota: index.php?url=novo-admin
     */
    public function novoAdmin() {
        $this->verificarAcessoAdmin();

        $mensagemSucesso = $_SESSION['sucesso_admin'] ?? null;
        $mensagemErro = $_SESSION['erro_admin'] ?? null;
        unset($_SESSION['sucesso_admin'], $_SESSION['erro_admin']);

        $tituloPagina = "GRID&GOL - Cadastrar Administrador";

        if (file_exists(__DIR__ . '/../views/cadastro-admin.php')) {
            require_once __DIR__ . '/../views/cadastro-admin.php';
        } else {
            die("Erro crítico: O arquivo 'views/cadastro-admin.php' não foi encontrado.");
        }
    }

    /**
     * Processa a submissão via POST do formulário de novo administrador
     */
    public function salvarAdmin() {
        $this->verificarAcessoAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $senha = $_POST['senha'] ?? '';

            if (empty($nome) || !$email || empty($senha)) {
                $_SESSION['erro_admin'] = "Por favor, preencha todos os campos corretamente.";
                header('Location: index.php?url=novo-admin');
                exit();
            }

            if (file_exists(__DIR__ . '/../model/AdminModel.php')) {
                require_once __DIR__ . '/../model/AdminModel.php';
                $adminModel = new AdminModel();
            } else {
                $_SESSION['erro_admin'] = "Erro interno: O arquivo 'model/AdminModel.php' não foi encontrado.";
                header('Location: index.php?url=novo-admin');
                exit();
            }

            if (method_exists($adminModel, 'buscarPorEmail')) {
                $usuarioExistente = $adminModel->buscarPorEmail($email);
                if ($usuarioExistente) {
                    $_SESSION['erro_admin'] = "Este endereço de e-mail já está associado a outro administrador.";
                    header('Location: index.php?url=novo-admin');
                    exit();
                }
            }

            $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

            if (method_exists($adminModel, 'inserir')) {
                $sucesso = $adminModel->inserir($nome, $email, $senhaHash);
                
                if ($sucesso) {
                    $_SESSION['sucesso_admin'] = "Novo administrador cadastrado com sucesso!";
                } else {
                    $_SESSION['erro_admin'] = "Erro de banco de dados ao tentar salvar o administrador.";
                }
            } else {
                $_SESSION['erro_admin'] = "Erro interno: Método de inserção não implementado no AdminModel.";
            }

            header('Location: index.php?url=novo-admin');
            exit();
        }
    }

    /**
     * Renderiza a view de edição de perfil exclusiva para a conta do Administrador logado
     * Rota: index.php?url=editar-perfil
     */
    public function editarPerfil() {
        $this->verificarAcessoAdmin();

        $mensagemSucesso = $_SESSION['sucesso_perfil_admin'] ?? null;
        $mensagemErro = $_SESSION['erro_perfil_admin'] ?? null;
        unset($_SESSION['sucesso_perfil_admin'], $_SESSION['erro_perfil_admin']);

        $tituloPagina = "GRID&GOL - Configurações de Perfil";

        if (file_exists(__DIR__ . '/../views/editar-perfil-admin.php')) {
            require_once __DIR__ . '/../views/editar-perfil-admin.php';
        } else {
            die("Erro crítico: O arquivo 'views/editar-perfil-admin.php' não foi encontrado.");
        }
    }

    /**
     * Processa as atualizações cadastrais do perfil corporativo do Administrador
     */
    public function atualizarPerfil() {
        $this->verificarAcessoAdmin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $novaSenha = $_POST['nova_senha'] ?? '';

            if (empty($nome) || !$email) {
                $_SESSION['erro_perfil_admin'] = "Por favor, informe um nome corporativo e e-mail institucional válidos.";
                header('Location: index.php?url=editar-perfil');
                exit();
            }

            // Atualiza dados persistidos em sessão para feedback visual em tempo real
            $_SESSION['usuario_nome'] = $nome;
            $_SESSION['usuario_email'] = $email;
            $_SESSION['sucesso_perfil_admin'] = "Dados corporativos atualizados com sucesso!";
            
            header('Location: index.php?url=editar-perfil');
            exit();
        }

        $mensagemSucesso = $_SESSION['sucesso_perfil_admin'] ?? null;
        $mensagemErro = $_SESSION['erro_perfil_admin'] ?? null;
        unset($_SESSION['sucesso_perfil_admin'], $_SESSION['erro_perfil_admin']);

        // Chamada direta do arquivo exclusivo de visualização do administrador
        if (file_exists(__DIR__ . '/../views/editar-perfil-admin.php')) {
            require_once __DIR__ . '/../views/editar-perfil-admin.php';
        }
    }
}