<?php
// controller/CategoriaController.php

class CategoriaController {
    
    /**
     * Gerencia a exibição da página de Futebol
     * Rota: index.php?url=futebol
     */
    public function futebol() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Garante o carregamento seguro do Model de produtos
        if (file_exists(__DIR__ . '/../model/ProdutoModel.php')) {
            require_once __DIR__ . '/../model/ProdutoModel.php';
        } else {
            die("Erro crítico: O arquivo 'model/ProdutoModel.php' não foi encontrado.");
        }
        
        $produtoModel = new ProdutoModel();
        
        // CORRIGIDO: Injeta os dados na variável genérica '$produtos' exigida pela View principal
        // Passa o ID 1 correspondente à categoria Futebol no banco de dados
        $produtos = $produtoModel->listarPorCategoria(1);
        $tituloPagina = "GRID&GOL - Futebol ⚽";

        // CORRIGIDO: Redireciona para o 'principal.php' para herdar a Grid simétrica e responsiva do CSS
        if (file_exists(__DIR__ . '/../views/principal.php')) {
            require_once __DIR__ . '/../views/principal.php';
        } else {
            die("Erro crítico: A view 'views/principal.php' não foi encontrada.");
        }
    }

    /**
     * Gerencia a exibição da página de Fórmula 1
     * Rota: index.php?url=formula1
     */
    public function formula1() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Garante o carregamento seguro do Model de produtos
        if (file_exists(__DIR__ . '/../model/ProdutoModel.php')) {
            require_once __DIR__ . '/../model/ProdutoModel.php';
        } else {
            die("Erro crítico: O arquivo 'model/ProdutoModel.php' não foi encontrado.");
        }
        
        $produtoModel = new ProdutoModel();
        
        // CORRIGIDO: Injeta os dados na mesma variável '$produtos'
        // Passa o ID 2 correspondente à categoria Fórmula 1 no banco de dados
        $produtos = $produtoModel->listarPorCategoria(2);
        $tituloPagina = "GRID&GOL - Fórmula 1 🏎️";

        // CORRIGIDO: Redireciona para o 'principal.php' mantendo a consistência visual do site
        if (file_exists(__DIR__ . '/../views/principal.php')) {
            require_once __DIR__ . '/../views/principal.php';
        } else {
            die("Erro crítico: A view 'views/principal.php' não foi encontrada.");
        }
    }
}