<?php
// views/pagamento.php
// Nota: Toda a lógica estrutural e validações de sessão foram movidas para o index() do Controller.
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tituloPagina ?? 'GRID&GOL - Pagamento Seguro'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/pagamento.css">
    <link rel="stylesheet" href="assets/css/payout.css">
</head>
<body>

    <div class="checkout-container">
        
        <main class="checkout-main">
            <h1 class="checkout-title">Forma de Pagamento<span>.</span></h1>

            <div class="payment-methods">
                <div class="method-tab active" id="tab-cartao" onclick="switchMethod('cartao')">
                    <i class="fas fa-credit-card"></i>
                    Cartão de Crédito
                </div>
                <div class="method-tab" id="tab-pix" onclick="switchMethod('pix')">
                    <i class="fas fa-qrcode"></i>
                    PIX Instantâneo
                </div>
                <div class="method-tab" id="tab-boleto" onclick="switchMethod('boleto')">
                    <i class="fas fa-barcode"></i>
                    Boleto Bancário
                </div>
            </div>

            <form action="index.php?url=processar-pagamento" method="POST" id="checkout-form">
                
                <input type="hidden" name="forma_pagamento_selecionada" id="forma_pagamento_selecionada" value="cartao">

                <div id="content-cartao" class="payment-content active">
                    <h3>Dados do Cartão de Crédito</h3>
                    
                    <div class="form-group">
                        <label for="card-number">Número do Cartão</label>
                        <input type="text" id="card-number" name="numero_cartao" placeholder="0000 0000 0000 0000" autocomplete="cc-number" maxlength="19" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="card-name">Nome Impresso no Cartão</label>
                        <input type="text" id="card-name" name="nome_cartao" value="<?php echo $nomeSugerido ?? ''; ?>" placeholder="Ex: JOÃO O. SILVA" autocomplete="cc-name" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="card-expiry">Validade</label>
                            <input type="text" id="card-expiry" name="validade_cartao" placeholder="MM/AA" autocomplete="cc-exp" maxlength="5" required>
                        </div>
                        <div class="form-group">
                            <label for="card-cvv">CVV</label>
                            <input type="text" id="card-cvv" name="cvv_cartao" placeholder="123" autocomplete="cc-csc" maxlength="4" required>
                        </div>
                    </div>
                </div>

                <div id="content-pix" class="payment-content">
                    <h3>Pagamento via PIX</h3>
                    <p class="payment-instruction">
                        <i class="fas fa-info-circle" style="color: #ff120a; margin-right: 5px;"></i>
                        Após clicar em concluir, um código Copia e Cola e o QR Code serão gerados para que efetue o pagamento no aplicativo do seu banco.
                    </p>
                </div>

                <div id="content-boleto" class="payment-content">
                    <h3>Boleto Bancário</h3>
                    <p class="payment-instruction">
                        <i class="fas fa-info-circle" style="color: #ff120a; margin-right: 5px;"></i>
                        O boleto será gerado após a confirmação. Poderá ser pago em qualquer agência bancária, internet banking ou casa lotérica até a data de vencimento.
                    </p>
                </div>

                <button type="submit" class="btn-submit-order">Concluir Compra Seguro</button>
            </form>
        </main>

        <aside class="summary-aside">
            <h2>Resumo da Compra</h2>
            <div class="summary-row">
                <span>Subtotal</span>
                <span>R$ <?php echo number_format($subtotal ?? 0, 2, ',', '.'); ?></span>
            </div>
            <div class="summary-row">
                <span>Portes / Frete</span>
                <span>R$ <?php echo number_format($portesEnvio ?? 0, 2, ',', '.'); ?></span>
            </div>
            <div class="summary-row total">
                <span>Total Geral</span>
                <span>R$ <?php echo number_format($totalGeral ?? 0, 2, ',', '.'); ?></span>
            </div>
        </aside>
    </div>

   
    <script src="assets/js/pagamento.js"></script>
    <script src="assets/js/mascaras-pagamento.js"></script>
</body>
</html>