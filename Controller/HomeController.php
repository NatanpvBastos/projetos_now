<?php
// controller/HomeController.php

class HomeController {
    
    /**
     * Método principal que renderiza a Home Page (principal.php)
     */
    public function index() {
        // 1. Inicializa a sessão se ainda não foi iniciada (útil para login/carrinho)
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2. Carregamento Seguro do Model (Volta um nível e entra na pasta 'model' no singular)
        if (file_exists(__DIR__ . '/../model/ProdutoModel.php')) {
            require_once __DIR__ . '/../model/ProdutoModel.php';
        } else {
            die("Erro crítico: O arquivo 'model/ProdutoModel.php' não foi encontrado no servidor.");
        }

        // Instancia o modelo de produtos
        $produtoModel = new ProdutoModel();
        
        // Busca todos os produtos cadastrados com JOINs (marcas e categorias) para a vitrine
        $produtos = $produtoModel->listarTodos();

        // 3. Define variáveis que serão injetadas dentro do HTML da View
        $tituloPagina = "GRID&GOL - Home";
        
        // Verifica se existe um usuário logado na sessão (opcional para saudações no topo)
        $usuarioLogado = isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : null;

        // 4. Carrega a View 'principal.php' (Volta um nível e entra em 'views')
        if (file_exists(__DIR__ . '/../views/principal.php')) {
            require_once __DIR__ . '/../views/principal.php';
        } else {
            die("Erro crítico: A view 'principal.php' não foi encontrada na pasta 'views/'.");
        }
    }
}