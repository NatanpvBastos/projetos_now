<?php
// model/PedidoModel.php

class PedidoModel {
    private $db;

    public function __construct() {
        // Inicializa a conexão utilizando a estrutura exata do seu database.php
        if (class_exists('Database')) {
            $this->db = Database::getConexao();
        }
    }

    /**
     * Busca todos os pedidos de um cliente específico no banco de dados
     */
    public function listarPorUsuario($usuarioId) {
        try {
            // Consulta adaptada para as colunas lidas na sua view minhas-compras.php
            $sql = "SELECT id_pedido, data_pagamento, forma_pagamento, valor_pago, status_pagamento 
                    FROM pedido 
                    WHERE id_usuario = :usuario_id 
                    ORDER BY data_pagamento DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Retorna um array vazio como contingência caso a tabela ainda não exista
            return [];
        }
    }
}