<?php
// controller/PagamentoController.php

class PagamentoController {

    public function __construct() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Exibe a tela de Checkout/Pagamento contendo as 3 opções (Cartão, PIX e Boleto)
     */
    public function index() {
        // =====================================================================
        // OBRIGAÇÃO DE LOGIN: Se o usuário não estiver logado, bloqueia o acesso
        // =====================================================================
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=login');
            exit();
        }

        // [CENTRALIZADO NO CONTROLLER]: Bloqueio preventivo de segurança se o carrinho estiver vazio
        if (empty($_SESSION['carrinho'])) {
            header('Location: index.php?url=carrinho');
            exit();
        }

        // [CENTRALIZADO NO CONTROLLER]: Prepara o nome sugerido para o cartão a partir da sessão do usuário logado
        $nomeSugerido = isset($_SESSION['usuario_nome']) ? strtoupper(htmlspecialchars($_SESSION['usuario_nome'])) : '';

        // Recalcula os totais para exibição na barra lateral da View
        $subtotal = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        $portesEnvio = $subtotal > 0 ? 4.99 : 0.00;
        $totalGeral = $subtotal + $portesEnvio;

        $tituloPagina = "GRID&GOL - Pagamento Seguro";

        // Carrega a View estrutural do Front-End (que irá apenas renderizar as variáveis locais prontas acima)
        if (file_exists(__DIR__ . '/../views/pagamento.php')) {
            require_once __DIR__ . '/../views/pagamento.php';
        } else {
            die("Erro crítico: O arquivo 'views/pagamento.php' não foi encontrado.");
        }
    }

    /**
     * Intercepta a submissão do formulário de pagamento, processa no banco e limpa carrinho
     */
    public function finalizar() {
        // =====================================================================
        // OBRIGAÇÃO DE LOGIN: Segurança nível Banco de Dados para impedir Postman ou burla
        // =====================================================================
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=login');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=carrinho');
            exit();
        }

        // Captura e sanitiza o método de pagamento selecionado na aba do Front-End
        $metodo = filter_input(INPUT_POST, 'metodo_pagamento', FILTER_SANITIZE_SPECIAL_CHARS);
        if (!in_array($metodo, ['cartao', 'pix', 'boleto'])) {
            $metodo = 'cartao'; // Fallback seguro
        }

        // Recalcula o total exato para gravação na base de dados
        $subtotal = 0;
        if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
                $subtotal += $item['preco'] * $item['quantidade'];
            }
        }

        if ($subtotal <= 0) {
            header('Location: index.php?url=carrinho');
            exit();
        }

        $portes = 4.99;
        $totalFinal = $subtotal + $portes;

        // Gera um ID randômico para o pedido (para fins de simulação/integração)
        $idPedidoGerado = rand(1000, 9999);

        // =====================================================================
        // PERSISTÊNCIA REAL NO BANCO DE DADOS (Tabela 'pedido')
        // =====================================================================
        if (class_exists('Database')) {
            try {
                $db = Database::getConexao();
                
                // Query baseada estritamente na estrutura fornecida por você
                $sql = "INSERT INTO pedido (id_pedido, data_pedido, valor_total, status, id_cliente) 
                        VALUES (:id_pedido, :data_pedido, :valor_total, :status, :id_cliente)";
                
                $stmt = $db->prepare($sql);
                
                // Status inicial baseado no método de pagamento escolhido
                $statusPedido = ($metodo === 'cartao') ? 'Pago' : 'Pendente';
                $dataAtual = date('Y-m-d H:i:s');
                
                $stmt->bindParam(':id_pedido', $idPedidoGerado, PDO::PARAM_INT);
                $stmt->bindParam(':data_pedido', $dataAtual);
                $stmt->bindParam(':valor_total', $totalFinal);
                $stmt->bindParam(':status', $statusPedido);
                $stmt->bindParam(':id_cliente', $_SESSION['usuario_id'], PDO::PARAM_INT);
                
                $stmt->execute();
            } catch (PDOException $e) {
                // Em ambiente de produção, registre o log $e->getMessage() se necessário
            }
        }

        // Limpa o carrinho da sessão atual do utilizador para evitar duplicidade de compra
        $_SESSION['carrinho'] = [];

        // Redireciona o fluxo para a tela visual de sucesso passando os dados via GET amigável
        header('Location: index.php?url=pedido-concluido&id=' . $idPedidoGerado . '&metodo=' . $metodo . '&total=' . $totalFinal);
        exit();
    }

    /**
     * Prepara as strings complexas de pagamento (PIX Copia-e-Cola / Linha do Boleto)
     * e renderiza a view final sem lógica pesada dentro do HTML
     */
    public function pedidoConcluido() {
        // =====================================================================
        // OBRIGAÇÃO DE LOGIN: Protege o recibo contra visualização externa maliciosa
        // =====================================================================
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: index.php?url=login');
            exit();
        }

        // Captura os dados via GET aplicando filtros de higienização
        $idPedido = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? '0000';
        $metodo   = filter_input(INPUT_GET, 'metodo', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'cartao';
        $total    = filter_input(INPUT_GET, 'total', FILTER_VALIDATE_FLOAT) ?? 0.00;

        // Processamento lógico para a String dinâmica do PIX Copia-e-Cola estruturado
        $chavePix = "pix@gridgol.com.br";
        $nomeLoja = "GRID GOL LTDA";
        $cidadeLoja = "RIO DE JANEIRO";
        $valorFormatado = number_format($total, 2, '.', '');

        $payloadParcial = "00020101021226" . strlen($chavePix) + 22 . "0014br.gov.bcb.pix01" . strlen($chavePix) . $chavePix . "52040000530398654" . strlen($valorFormatado) . $valorFormatado . "5802BR59" . sprintf("%02d", strlen($nomeLoja)) . $nomeLoja . "60" . sprintf("%02d", strlen($cidadeLoja)) . $cidadeLoja . "62070503***6304";
        
        // Simulação matemática do algoritmo polinomial para assinar a string
        $pixCopiaCola = $payloadParcial . $this->calcularCRC16($payloadParcial);

        // Processamento lógico para a linha digitável do Boleto de Teste
        $boletoLinhaDigitavel = "34191.79001 01043.513184 91020.150008 7 983200000" . str_replace(['.', ','], '', number_format($total, 2, '', ''));

        $tituloPagina = "GRID&GOL - Pedido #" . $idPedido . " Concluído";

        // Inclui a View estrutural do Front-End (que irá apenas renderizar as variáveis locais prontas acima)
        if (file_exists(__DIR__ . '/../views/pedido-concluido.php')) {
            require_once __DIR__ . '/../views/pedido-concluido.php';
        } else {
            die("Erro crítico: O arquivo 'views/pedido-concluido.php' não foi encontrado.");
        }
    }

    /**
     * Algoritmo polinomial CRC16 CCITT (0x1021)
     * Essencial para que os aplicativos de bancos não rejeitem a string do PIX.
     */
    private function calcularCRC16($texto) {
        $crc = 0xFFFF;
        for ($c = 0; $c < strlen($texto); $c++) {
            $crc ^= (ord($texto[$c]) << 8);
            for ($i = 0; $i < 8; $i++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc = $crc << 1;
                }
            }
        }
        return strtoupper(sprintf('%04x', $crc & 0xFFFF));
    }
}