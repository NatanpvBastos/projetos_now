<?php
// controller/UsuarioController.php

class UsuarioController {

    public function __construct() {
        // Garante que a sessão está ativa para conseguirmos ler os dados do utilizador logado
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Método responsável por carregar a rota 'index.php?url=minhas-compras'
     */
    public function minhasCompras() {
        // 1. Proteção de Rota: Se o utilizador não estiver autenticado, manda-o para o login
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=login');
            exit();
        }

        // Valores padrão caso as consultas ao banco falhem
        $usuarioLogado = [
            'nome'          => isset($_SESSION['usuario_nome']) ? $_SESSION['usuario_nome'] : 'Cliente GRID&GOL',
            'data_cadastro' => isset($_SESSION['usuario_data_cadastro']) ? $_SESSION['usuario_data_cadastro'] : date('Y-m-d'),
            'foto'          => ''
        ];
        $compras = [];

        if (class_exists('Database')) {
            try {
                $db = Database::getConexao();
                
                // === LOGICA CORRIGIDA: BUSCA OS DADOS REAIS DO PERFIL (INCLUINDO A FOTO) ===
                try {
                    // Tentativa A: Considerando a tabela como 'cliente' (baseado no id_cliente de pedidos)
                    $sqlUser = "SELECT nome, email, telefone, endereco, data_cadastro, foto FROM cliente WHERE id_cliente = :id_cliente";
                    $stmtUser = $db->prepare($sqlUser);
                    $stmtUser->bindParam(':id_cliente', $_SESSION['usuario_id'], PDO::PARAM_INT);
                    $stmtUser->execute();
                    $dadosUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
                    if ($dadosUser) {
                        $usuarioLogado = $dadosUser;
                    }
                } catch (PDOException $e) {
                    try {
                        // Tentativa B: Caso a tabela se chame 'usuario' e a chave seja 'id'
                        $sqlUser = "SELECT nome, email, telefone, endereco, data_cadastro, foto FROM usuario WHERE id = :id_cliente";
                        $stmtUser = $db->prepare($sqlUser);
                        $stmtUser->bindParam(':id_cliente', $_SESSION['usuario_id'], PDO::PARAM_INT);
                        $stmtUser->execute();
                        $dadosUser = $stmtUser->fetch(PDO::FETCH_ASSOC);
                        if ($dadosUser) {
                            $usuarioLogado = $dadosUser;
                        }
                    } catch (PDOException $ex) {
                        // Fallback de segurança: Se ambas falharem, tenta usar a sessão
                        $usuarioLogado['foto'] = isset($_SESSION['usuario_foto']) ? $_SESSION['usuario_foto'] : '';
                    }
                }
                // =========================================================================

                // 3. Busca o histórico de compras do cliente diretamente na tabela 'pedido'
                $sql = "SELECT id_pedido, data_pedido, valor_total, status 
                        FROM pedido 
                        WHERE id_cliente = :id_cliente 
                        ORDER BY data_pedido DESC";
                
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id_cliente', $_SESSION['usuario_id'], PDO::PARAM_INT);
                $stmt->execute();
                
                $compras = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                $compras = [];
            }
        }

        // Isola as variáveis para sumir com os Warnings de "Undefined variable" nas linhas 23 e 24 da View
        $nomeUsuario  = htmlspecialchars($usuarioLogado['nome'] ?? 'Cliente GRID&GOL');
        $dataCadastro = date('d/m/Y', strtotime($usuarioLogado['data_cadastro'] ?? date('Y-m-d')));
        
        if (!is_array($compras)) {
            $compras = [];
        }

        // 4. Carrega a View física
        if (file_exists(__DIR__ . '/../views/minhas-compras.php')) {
            require_once __DIR__ . '/../views/minhas-compras.php';
        } else {
            die("Erro crítico: O arquivo visual 'views/minhas-compras.php' não foi encontrado.");
        }
    }
}