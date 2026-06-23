<?php
// controller/ProdutoController.php

class ProdutoController {

    private function verificarAcessoAdmin() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] !== 'admin') {
            http_response_code(403);
            die("<div style='text-align:center;margin-top:100px;font-family:Arial;'><h2>403 - Acesso Proibido</h2><p>Esta área é restrita estritamente para administradores do sistema.</p></div>");
        }
    }

    public function index() {
        $this->verificarAcessoAdmin();

        require_once __DIR__ . '/../model/ProdutoModel.php';
        $produtoModel = new ProdutoModel();
        
        $categorias = $produtoModel->buscarCategorias();
        $produtos = $produtoModel->listarTodos();

        $mensagemErro = $_SESSION['erro_produto'] ?? null;
        $mensagemSucesso = $_SESSION['sucesso_produto'] ?? null;
        unset($_SESSION['erro_produto'], $_SESSION['sucesso_produto']);

        $tituloPagina = "GRID&GOL - Gerenciar Produtos";

        if (file_exists(__DIR__ . '/../views/cadastro-produto.php')) {
            require_once __DIR__ . '/../views/cadastro-produto.php';
        } else {
            die("Erro crítico: A view 'cadastro-produto.php' não foi encontrada.");
        }
    }

    public function salvar() {
        $this->verificarAcessoAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=cadastro-produto');
            exit();
        }

        $nome         = isset($_POST['nome']) ? trim($_POST['nome']) : '';
        $id_categoria = isset($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : 0;
        $preco        = isset($_POST['preco']) ? (float)$_POST['preco'] : 0.00;
        $estoque      = isset($_POST['estoque']) ? (int)$_POST['estoque'] : 0;
        $descricao    = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
        $nomeImagem   = null;

        if (empty($nome) || $id_categoria <= 0 || $preco <= 0) {
            $_SESSION['erro_produto'] = "Por favor, preencha todos os campos obrigatórios (Nome, Categoria e Preço).";
            header('Location: index.php?url=cadastro-produto');
            exit();
        }

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

            if (in_array($extensao, $extensoesPermitidas)) {
                $nomeImagem = md5(uniqid(rand(), true)) . '.' . $extensao;
                $diretorioDestino = __DIR__ . '/../assets/img/produtos/';

                if (!is_dir($diretorioDestino)) {
                    mkdir($diretorioDestino, 0755, true);
                }

                move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorioDestino . $nomeImagem);
            } else {
                $_SESSION['erro_produto'] = "Formato de imagem inválido. Use JPG, PNG ou WEBP.";
                header('Location: index.php?url=cadastro-produto');
                exit();
            }
        }

        require_once __DIR__ . '/../model/ProdutoModel.php';
        $produtoModel = new ProdutoModel();

        $sucesso = $produtoModel->inserir($nome, $descricao, $preco, $estoque, $nomeImagem, $id_categoria);

        if ($sucesso) {
            $_SESSION['sucesso_produto'] = "Produto cadastrado com sucesso!";
        } else {
            $_SESSION['erro_produto'] = "Ocorreu um erro ao salvar o produto no banco de dados. Verifique a estrutura da tabela.";
        }

        header('Location: index.php?url=cadastro-produto');
        exit();
    }

    public function editar() {
        $this->verificarAcessoAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_GET['id_produto']) ? (int)$_GET['id_produto'] : 0);

        if ($id <= 0) {
            $_SESSION['erro_produto'] = "ID do produto inválido para edição.";
            header('Location: index.php?url=cadastro-produto');
            exit();
        }

        require_once __DIR__ . '/../model/ProdutoModel.php';
        $produtoModel = new ProdutoModel();

        $produto = $produtoModel->buscarPorId($id);
        $categorias = $produtoModel->buscarCategorias();

        if (!$produto) {
            $_SESSION['erro_produto'] = "O produto solicitado não foi encontrado.";
            header('Location: index.php?url=cadastro-produto');
            exit();
        }

        $tituloPagina = "GRID&GOL - Editar Produto";

        if (file_exists(__DIR__ . '/../views/editar-produto.php')) {
            require_once __DIR__ . '/../views/editar-produto.php';
        } else {
            require_once __DIR__ . '/../views/cadastro-produto.php';
        }
    }

    public function atualizar() {
        $this->verificarAcessoAdmin();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=cadastro-produto');
            exit();
        }

        // Mapeamento inteligente para capturar o id vindo do formulário oculto
        $id           = isset($_POST['id_produto']) ? (int)$_POST['id_produto'] : (isset($_POST['id']) ? (int)$_POST['id'] : 0);
        $nome         = isset($_POST['nome']) ? trim($_POST['nome']) : '';
        $id_categoria = isset($_POST['id_categoria']) ? (int)$_POST['id_categoria'] : 0;
        $preco        = isset($_POST['preco']) ? (float)$_POST['preco'] : 0.00;
        $estoque      = isset($_POST['estoque']) ? (int)$_POST['estoque'] : 0;
        $descricao    = isset($_POST['descricao']) ? trim($_POST['descricao']) : '';
        
        // Corrigido: captura 'imagem_atual' que é o name real vindo do HTML do editar-produto.php
        $imagemAntiga = $_POST['imagem_atual'] ?? ''; 
        $nomeImagem   = !empty($imagemAntiga) ? $imagemAntiga : null;

        if ($id <= 0 || empty($nome) || $id_categoria <= 0) {
            $_SESSION['erro_produto'] = "Dados inválidos para a atualização do produto.";
            header('Location: index.php?url=cadastro-produto');
            exit();
        }

        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $extensao = strtolower(pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION));
            if (in_array($extensao, ['jpg', 'jpeg', 'png', 'webp'])) {
                $nomeImagem = md5(uniqid(rand(), true)) . '.' . $extensao;
                $diretorioDestino = __DIR__ . '/../assets/img/produtos/';

                if (move_uploaded_file($_FILES['imagem']['tmp_name'], $diretorioDestino . $nomeImagem)) {
                    if (!empty($imagemAntiga) && file_exists($diretorioDestino . $imagemAntiga)) {
                        @unlink($diretorioDestino . $imagemAntiga);
                    }
                }
            }
        }

        require_once __DIR__ . '/../model/ProdutoModel.php';
        $produtoModel = new ProdutoModel();

        $sucesso = $produtoModel->atualizar($id, $nome, $descricao, $preco, $estoque, $nomeImagem, $id_categoria);

        if ($sucesso) {
            $_SESSION['sucesso_produto'] = "Produto atualizado com sucesso!";
        } else {
            $_SESSION['erro_produto'] = "Nenhuma alteração foi realizada ou ocorreu um erro no banco de dados.";
        }

        header('Location: index.php?url=cadastro-produto');
        exit();
    }

    public function deletar() {
        $this->verificarAcessoAdmin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : (isset($_GET['id_produto']) ? (int)$_GET['id_produto'] : 0);

        if ($id <= 0) {
            $_SESSION['erro_produto'] = "ID inválido para exclusão.";
            header('Location: index.php?url=cadastro-produto');
            exit();
        }

        require_once __DIR__ . '/../model/ProdutoModel.php';
        $produtoModel = new ProdutoModel();

        $produto = $produtoModel->buscarPorId($id);
        if ($produto && !empty($produto['imagem'])) {
            $caminhoImg = __DIR__ . '/../assets/img/produtos/' . $produto['imagem'];
            if (file_exists($caminhoImg)) {
                @unlink($caminhoImg);
            }
        }

        $sucesso = $produtoModel->deletar($id);

        if ($sucesso) {
            $_SESSION['sucesso_produto'] = "Produto removido com sucesso!";
        } else {
            $_SESSION['erro_produto'] = "Não foi possível remover o produto do banco de dados.";
        }

        header('Location: index.php?url=cadastro-produto');
        exit();
    }
}