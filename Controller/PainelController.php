<?php
// controller/PainelController.php

class PainelController {

    /**
     * Validação restrita: Se não for cliente, desloga ou bloqueia
     */
    private function verificarLogin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'cliente') {
            header('Location: index.php?url=login');
            exit();
        }
    }

    /**
     * Procura e retorna os dados do cliente autenticado na base de dados
     */
    private function getUsuarioLogado() {
        if (file_exists(__DIR__ . '/../model/ClienteModel.php')) {
            require_once __DIR__ . '/../model/ClienteModel.php';
        } else {
            die("Erro crítico: O arquivo 'model/ClienteModel.php' não foi encontrado.");
        }

        $clienteModel = new ClienteModel();
        $usuario = $clienteModel->buscarPorId($_SESSION['usuario_id']);

        if (!$usuario) {
            header('Location: index.php?url=logout');
            exit();
        }
        return $usuario;
    }

    /**
     * Renderiza o painel principal com os dados cadastrais e os contadores em tempo real
     */
    public function index() {
        $this->verificarLogin();
        $usuarioLogado = $this->getUsuarioLogado();

        // Tratamento centralizado de variáveis locais exclusivas para a view painel.php
        $nomeUsuario     = isset($usuarioLogado['nome']) ? htmlspecialchars($usuarioLogado['nome']) : 'Utilizador';
        $emailUsuario    = isset($usuarioLogado['email']) ? htmlspecialchars($usuarioLogado['email']) : '';
        $telefoneUsuario = isset($usuarioLogado['telefone']) ? htmlspecialchars($usuarioLogado['telefone']) : 'Não informado';
        $enderecoUsuario = isset($usuarioLogado['endereco']) ? htmlspecialchars($usuarioLogado['endereco']) : 'Não informado';
        $dataCadastro    = isset($usuarioLogado['data_cadastro']) ? date('d/m/Y', strtotime($usuarioLogado['data_cadastro'])) : '';

        // Contador do Carrinho: Soma as quantidades da sessão
        $quantidadeCarrinho = 0;
        if (!empty($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
                $quantidadeCarrinho += (int)$item['quantidade'];
            }
        }

        // =====================================================================
        // CORREÇÃO CIRÚRGICA: CONTADOR DE PEDIDOS SEGURO DIRETO DA TABELA
        // =====================================================================
        $totalPedidos = 0;
        if (class_exists('Database')) {
            try {
                $db = Database::getConexao();
                // Query baseada na estrutura de sucesso mapeada no UsuarioController
                $sql = "SELECT COUNT(*) as total FROM pedido WHERE id_cliente = :id_cliente";
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id_cliente', $_SESSION['usuario_id'], PDO::PARAM_INT);
                $stmt->execute();
                
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $totalPedidos = isset($resultado['total']) ? (int)$resultado['total'] : 0;
            } catch (PDOException $e) {
                error_log("Erro ao contar pedidos no Painel: " . $e->getMessage());
                $totalPedidos = 0;
            }
        }

        if (file_exists(__DIR__ . '/../views/painel.php')) {
            require_once __DIR__ . '/../views/painel.php';
        } else {
            die("Erro crítico: A visão 'views/painel.php' não foi encontrada.");
        }
    }

    /**
     * Exibe o histórico completo de compras efetuadas pelo utilizador
     */
    public function compras() {
        $this->verificarLogin();
        $usuarioLogado = $this->getUsuarioLogado();
        
        // =========================================================================
        // TRATAMENTO EXTERNALIZADO: Variáveis locais geradas e prontas para a View
        // =========================================================================
        $nomeUsuario  = isset($usuarioLogado['nome']) ? htmlspecialchars($usuarioLogado['nome']) : 'Utilizador';
        $dataCadastro = isset($usuarioLogado['data_cadastro']) ? date('d/m/Y', strtotime($usuarioLogado['data_cadastro'])) : '';
        
        $listaCompras = []; 

        // Carrega o Modelo de Pedidos para buscar o histórico no banco de dados
        if (file_exists(__DIR__ . '/../model/PedidoModel.php')) {
            require_once __DIR__ . '/../model/PedidoModel.php';
            $pedidoModel = new PedidoModel();
            
            if (method_exists($pedidoModel, 'listarPorUsuario')) {
                $listaCompras = $pedidoModel->listarPorUsuario($_SESSION['usuario_id']);
            }
        }
        
        // Garante o fallback seguro para que $compras seja sempre um array iterável
        $compras = is_array($listaCompras) ? $listaCompras : [];

        // Carrega a View purificada (que herdará $nomeUsuario, $dataCadastro e $compras)
        if (file_exists(__DIR__ . '/../views/minhas-compras.php')) {
            require_once __DIR__ . '/../views/minhas-compras.php';
        } else {
            die("Erro crítico: A visão 'views/minhas-compras.php' não foi encontrada.");
        }
    }

    /**
     * Processa a exibição e a submissão do formulário de atualização cadastral do perfil
     */
    public function editar() {
        $this->verificarLogin();
        $usuarioLogado = $this->getUsuarioLogado();

        $mensagemSucesso = $_SESSION['sucesso_perfil'] ?? null;
        $mensagemErro = $_SESSION['erro_perfil'] ?? null;
        unset($_SESSION['sucesso_perfil'], $_SESSION['erro_perfil']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
            $telefone = filter_input(INPUT_POST, 'telefone', FILTER_SANITIZE_SPECIAL_CHARS);
            $endereco = filter_input(INPUT_POST, 'endereco', FILTER_SANITIZE_SPECIAL_CHARS);

            if (empty($nome) || empty($telefone) || empty($endereco)) {
                $_SESSION['erro_perfil'] = "Por favor, preencha todos os campos obrigatórios.";
                header('Location: index.php?url=editar-perfil');
                exit();
            }

            $clienteModel = new ClienteModel();
            $atualizou = $clienteModel->atualizarPerfil($_SESSION['usuario_id'], $nome, $telefone, $endereco);

            if ($atualizou) {
                $_SESSION['sucesso_perfil'] = "Perfil atualizado com sucesso!";
            } else {
                $_SESSION['erro_perfil'] = "Nenhuma alteração foi realizada ou ocorreu um erro.";
            }

            header('Location: index.php?url=editar-perfil');
            exit();
        }

        if (file_exists(__DIR__ . '/../views/editar-perfil.php')) {
            require_once __DIR__ . '/../views/editar-perfil.php';
        } else {
            die("Erro crítico: A visão 'views/editar-perfil.php' não foi encontrada.");
        }
    }
    /**
     * Processa o upload da imagem de perfil sem interferir no layout do painel
     */
    public function alterarAvatar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Valida se está logado
        if (!isset($_SESSION['usuario_id']) || $_SESSION['usuario_tipo'] !== 'cliente') {
            header('Location: index.php?url=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {
            $arquivo = $_FILES['avatar'];
            $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif'];
            $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

            if (!in_array($extensao, $extensoesPermitidas)) {
                header('Location: index.php?url=painel&erro=extensao');
                exit();
            }

            if ($arquivo['size'] > 2 * 1024 * 1024) { // Max 2MB
                header('Location: index.php?url=painel&erro=tamanho');
                exit();
            }

            // Garante a existência da pasta de destino
            $diretorioDestino = __DIR__ . '/../assets/img/avatars/';
            if (!is_dir($diretorioDestino)) {
                mkdir($diretorioDestino, 0755, true);
            }

            $novoNome = 'avatar_' . $_SESSION['usuario_id'] . '_' . time() . '.' . $extensao;
            $caminhoCompleto = $diretorioDestino . $novoNome;
            $caminhoBanco = 'assets/img/avatars/' . $novoNome;

            if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
                if (file_exists(__DIR__ . '/../model/ClienteModel.php')) {
                    require_once __DIR__ . '/../model/ClienteModel.php';
                    $clienteModel = new ClienteModel();
                    $clienteModel->atualizarFoto($_SESSION['usuario_id'], $caminhoBanco);
                }
            }
        }

        header('Location: index.php?url=painel');
        exit();
    }

}