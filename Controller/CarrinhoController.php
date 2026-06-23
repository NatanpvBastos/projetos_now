<?php
// controller/CarrinhoController.php

class CarrinhoController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        // Inicializa o carrinho na sessão caso não exista
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }

    /**
     * Exibe a página do carrinho (Acessado por GET)
     * Centraliza os cálculos de totais e regras antes de carregar a interface visual
     */
    public function index() {
        // 1. Recupera os itens do carrinho que estão salvos na sessão
        $itensCarrinho = $_SESSION['carrinho'] ?? [];

        // 2. Realiza os cálculos de negócio referentes aos valores dos artigos
        $subtotal = 0;
        if (!empty($itensCarrinho)) {
            foreach ($itensCarrinho as $item) {
                $subtotal += $item['preco'] * $item['quantidade'];
            }
        }

        // 3. Define as regras de taxas e portes de envio
        $portesEnvio = $subtotal > 0 ? 4.99 : 0.00;
        $totalGeral = $subtotal + $portesEnvio;

        // 4. Define metadados visuais para a estrutura da página
        $tituloPagina = "GRID&GOL - Seu Carrinho";
        
        // 5. Carrega a View. Como as variáveis acima foram criadas aqui, 
        // a view 'carrinho.php' herdará e poderá usá-las diretamente sem recalcular nada.
        if (file_exists(__DIR__ . '/../views/carrinho.php')) {
            require_once __DIR__ . '/../views/carrinho.php';
        } else {
            die("Erro crítico: O arquivo visual 'views/carrinho.php' não foi encontrado.");
        }
    }

    /**
     * Adiciona um produto ao carrinho
     */
    public function adicionar() {
        $idProduto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        
        if ($idProduto) {
            require_once __DIR__ . '/../model/ProdutoModel.php';
            $produtoModel = new ProdutoModel();
            $produto = $produtoModel->buscarPorId($idProduto); 

            if ($produto) {
                // Se o produto já está no carrinho, apenas aumenta a quantidade
                if (isset($_SESSION['carrinho'][$idProduto])) {
                    $_SESSION['carrinho'][$idProduto]['quantidade']++;
                } else {
                    // Se não está, adiciona as informações vindas do Banco de Dados
                    $_SESSION['carrinho'][$idProduto] = [
                        'nome' => $produto['nome'],
                        'preco' => $produto['preco'],
                        'imagem' => $produto['imagem'],
                        'categoria' => $produto['categoria'] ?? 'Geral',
                        'quantidade' => 1
                    ];
                }
            }
        }
        
        header('Location: index.php?url=carrinho');
        exit();
    }

    /**
     * Atualiza as quantidades (+ ou -) a partir dos botões da View
     */
    public function atualizar() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $acao = $_GET['acao'] ?? '';

        if ($id && isset($_SESSION['carrinho'][$id])) {
            if ($acao === 'aumentar') {
                $_SESSION['carrinho'][$id]['quantidade']++;
            } elseif ($acao === 'diminuir') {
                $_SESSION['carrinho'][$id]['quantidade']--;
                // Se a quantidade chegar a 0, remove o item
                if ($_SESSION['carrinho'][$id]['quantidade'] <= 0) {
                    unset($_SESSION['carrinho'][$id]);
                }
            }
        }

        header('Location: index.php?url=carrinho');
        exit();
    }

    /**
     * Remove totalmente um item clicando na lixeira
     */
    public function remover() {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if ($id && isset($_SESSION['carrinho'][$id])) {
            unset($_SESSION['carrinho'][$id]);
        }

        header('Location: index.php?url=carrinho');
        exit();
    }

    /**
     * Finaliza a compra, dá baixa no estoque e limpa o carrinho
     * Acessado por: index.php?url=finalizar-compra
     */
    public function finalizar() {
        // 1. Verifica se o carrinho não está vazio
        if (isset($_SESSION['carrinho']) && !empty($_SESSION['carrinho'])) {
            
            // 2. Importa e instancia o ProdutoModel (onde está a sua query de UPDATE)
            require_once __DIR__ . '/../model/ProdutoModel.php';
            $produtoModel = new ProdutoModel();

            // 3. Percorre cada item do carrinho e reduz o estoque no banco de dados
            foreach ($_SESSION['carrinho'] as $idProduto => $item) {
                $quantidadeComprada = (int)$item['quantidade'];
                
                // Executa a função que já existe no seu ProdutoModel
                $produtoModel->atualizarEstoque($idProduto, $quantidadeComprada);
            }

            // [OPCIONAL] Se tiver um PedidoModel para salvar o histórico no banco, a lógica entraria aqui.

            // 4. Limpa o carrinho da sessão após a compra ser concluída com sucesso
            unset($_SESSION['carrinho']);

            // 5. Define a mensagem de sucesso que a sua página 'principal.php' já sabe exibir
            $_SESSION['sucesso_compra'] = "Compra realizada com sucesso! O estoque foi atualizado.";
        }

        // 6. Redireciona o cliente de volta para a página inicial (vitrine)
        header('Location: index.php');
        exit();
    }

}