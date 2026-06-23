<?php
// controller/ContatoController.php

class ContatoController {
    
    /**
     * Exibe a página de contato
     */
    public function index() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $tituloPagina = "GRID&GOL - Contato";

        // O controlador carrega a view com segurança
        if (file_exists(__DIR__ . '/../views/contato.php')) {
            require_once __DIR__ . '/../views/contato.php';
        } else {
            die("Erro crítico: A view 'contato.php' não foi encontrada.");
        }
    }
}