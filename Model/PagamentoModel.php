<?php
// model/PagamentoModel.php

class PagamentoModel {
    private $db;

    public function __construct() {
        if (class_exists('Database')) {
            $this->db = Database::getConexao();
        }
    }

    /**
     * Salva o pedido, os itens do carrinho com subtotal calculado e o pagamento
     */
    public function registrarPedidoEPagamento($idUsuario, $totalGeral, $formaPagamento, $statusPagamento, $cartaoNumero = null, $cartaoNome = null, $cartaoValidade = null, $cartaoCvv = null) {
        if (!$this->db) {
            die("Erro crítico: A conexão com o Banco de Dados não foi estabelecida no Model.");
        }

        try {
            // Inicia uma transação segura (Tudo ou Nada)
            $this->db->beginTransaction();

            // =========================================================================
            // 1. GERAR UM ID DE PEDIDO ÚNICO MANUALMENTE COM VALIDAÇÃO DE DUPLICIDADE
            // =========================================================================
            do {
                $idPedidoGerado = rand(100000, 999999);
                
                // Verifica na base de dados se este ID gerado aleatoriamente já existe na tabela pedido
                $stmtCheck = $this->db->prepare("SELECT COUNT(*) FROM pedido WHERE id_pedido = :id_pedido");
                $stmtCheck->execute([':id_pedido' => $idPedidoGerado]);
                $idExiste = $stmtCheck->fetchColumn();
                
            } while ($idExiste > 0); // Repete o ciclo caso o ID gerado já pertença a outra compra

            // 2. RECUPERAR OU DEFINIR O ID DO CLIENTE
            $idCliente = !empty($idUsuario) ? (int)$idUsuario : 1;

            // =========================================================================
            // PASSO 1: INSERIR NA TABELA PEDIDO
            // =========================================================================
            $sqlPedido = "INSERT INTO pedido (id_pedido, data_pedido, valor_total, status, id_cliente) 
                          VALUES (:id_pedido, NOW(), :valor_total, 'Processando', :id_cliente)";
            
            $stmtPedido = $this->db->prepare($sqlPedido);
            $stmtPedido->execute([
                ':id_pedido'    => $idPedidoGerado,
                ':valor_total'  => $totalGeral,
                ':id_cliente'   => $idCliente
            ]);

            // =========================================================================
            // PASSO 2: INSERIR OS ITENS DO CARRINHO (Contemplando a coluna 'subtotal')
            // =========================================================================
            $itensCarrinho = isset($_SESSION['carrinho']) ? $_SESSION['carrinho'] : [];

            if (!empty($itensCarrinho)) {
                // Adicionado a coluna 'subtotal' exigida pela estrutura da sua tabela
                $sqlItem = "INSERT INTO item_pedido (id_pedido, id_produto, quantidade, preco_unitario, subtotal) 
                            VALUES (:id_pedido, :id_produto, :quantidade, :preco_unitario, :subtotal)";
                
                $stmtItem = $this->db->prepare($sqlItem);

                foreach ($itensCarrinho as $idProduto => $item) {
                    $qtd = (int)$item['quantidade'];
                    $preco = (float)$item['preco'];
                    $subtotalItem = $qtd * $preco; // Calcula dinamicamente o subtotal individual do item

                    $stmtItem->execute([
                        ':id_pedido'       => $idPedidoGerado,
                        ':id_produto'      => (int)$idProduto,
                        ':quantidade'      => $qtd,
                        ':preco_unitario'  => $preco,
                        ':subtotal'        => $subtotalItem
                    ]);
                }
            }

            // =========================================================================
            // PASSO 3: INSERIR NA TABELA PAGAMENTO
            // =========================================================================
            $sqlPagamento = "INSERT INTO pagamento (forma_pagamento, status_pagamento, valor_pago, data_pagamento, id_pedido, cartao_numero, cartao_nome, cartao_validade, cartao_cvv) 
                             VALUES (:forma_pagamento, :status_pagamento, :valor_pago, NOW(), :id_pedido, :cartao_numero, :cartao_nome, :cartao_validade, :cartao_cvv)";
            
            $stmtPagamento = $this->db->prepare($sqlPagamento);
            $stmtPagamento->execute([
                ':forma_pagamento'  => strtoupper($formaPagamento), // 'CARTAO', 'PIX' ou 'BOLETO'
                ':status_pagamento' => $statusPagamento,
                ':valor_pago'       => $totalGeral,
                ':id_pedido'        => $idPedidoGerado,
                ':cartao_numero'    => $cartaoNumero,
                ':cartao_nome'      => $cartaoNome,
                ':cartao_validade'  => $cartaoValidade,
                ':cartao_cvv'       => $cartaoCvv
            ]);

            // Se todas as inserções correrem bem, confirma e consolida a transação
            $this->db->commit();
            return true;

        } catch (Exception $e) {
            // Desfaz todas as alterações caso ocorra algum erro no meio do processo
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            
            die("Erro interno do Banco de Dados ao finalizar compra: " . $e->getMessage());
            return false;
        }
    }
}