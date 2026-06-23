<?php
// views/carrinho.php

// views/carrinho.php

// Apenas uma segurança caso as variáveis não tenham sido definidas pelo Controlador
$itensCarrinho = $itensCarrinho ?? [];
$subtotal = $subtotal ?? 0;
$portesEnvio = $portesEnvio ?? 0;
$totalGeral = $totalGeral ?? 0;
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tituloPagina ?? 'GRID&GOL - O Seu Carrinho'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/carrinho.css">
    <link rel="stylesheet" href="assets/css/metodo.css">
</head>
<body>

    <div class="cart-wrapper">
        
        <div class="cart-header">
            <h1>O Seu Carrinho<span>.</span></h1>
            <a href="index.php?url=home" class="btn-continue">
                <i class="fas fa-arrow-left"></i> Voltar à Loja
            </a>
        </div>

        <?php if (empty($itensCarrinho)): ?>
            <div class="cart-items-panel" style="padding: 20px;">
                <div class="empty-state">
                    <i class="fas fa-shopping-cart"></i>
                    <p>Não existem produtos adicionados ao seu carrinho de momento.</p>
                    <a href="index.php?url=home" class="btn-browse">Explorar Vitrine</a>
                </div>
            </div>
        <?php else: ?>
            
            <div class="cart-layout">
                
                <div class="cart-items-panel">
                    <?php foreach ($itensCarrinho as $id => $item): ?>
                        <div class="cart-row">
                            
                            <div class="prod-thumb">
                                <?php if (!empty($item['imagem'])): ?>
                                    <img src="assets/img/produtos/<?php echo htmlspecialchars($item['imagem']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>">
                                <?php else: ?>
                                    <i class="fas fa-image" style="color: #dee2e6; font-size: 24px;"></i>
                                <?php endif; ?>
                            </div>

                            <div class="prod-details">
                                <h3><?php echo htmlspecialchars($item['nome']); ?></h3>
                                <p>Preço Unitário: R$ <?php echo number_format($item['preco'], 2, ',', '.'); ?></p>
                            </div>

                            <div class="quantity-selector">
                                <a href="index.php?url=atualizar-carrinho&acao=diminuir&id=<?php echo $id; ?>" class="quantity-btn">-</a>
                                <span class="quantity-value"><?php echo (int)$item['quantidade']; ?></span>
                                <a href="index.php?url=atualizar-carrinho&acao=aumentar&id=<?php echo $id; ?>" class="quantity-btn">+</a>
                            </div>

                            <div class="prod-total-price">
                                R$ <?php echo number_format($item['preco'] * $item['quantidade'], 2, ',', '.'); ?>
                            </div>

                            <div>
                                <a href="index.php?url=remover-do-carrinho&id=<?php echo $id; ?>" class="btn-delete" title="Remover Artigo">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>

                        </div>
                    <?php endforeach; ?>
                </div>

                <aside class="summary-panel">
                    <h2>Sumário do Pedido</h2>
                    
                    <div class="summary-row">
                        <span>Subtotal de artigos</span>
                        <span>R$ <?php echo number_format($subtotal, 2, ',', '.'); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Portes de envio</span>
                        <span><?php echo $portesEnvio > 0 ? 'R$ ' . number_format($portesEnvio, 2, ',', '.') : 'Grátis'; ?></span>
                    </div>

                    <div class="summary-row total-row">
                        <span>Total</span>
                        <span>R$ <?php echo number_format($totalGeral, 2, ',', '.'); ?></span>
                    </div>

                    <form action="index.php?url=finalizar-pedido" method="POST" id="formFinalizarPedido">
                        
                        <div class="payment-selection-box">
                            <h3>Meio de Pagamento</h3>
                            
                            <label class="payment-option-label selected">
                                <input type="radio" name="forma_pagamento_selecionada" value="cartao" checked onclick="atualizarEstiloRadio(this)">
                                <i class="fas fa-credit-card"></i> Cartão Crédito / Débito
                            </label>

                            <label class="payment-option-label">
                                <input type="radio" name="forma_pagamento_selecionada" value="pix" onclick="atualizarEstiloRadio(this)">
                                <i class="fas fa-qrcode"></i> Pagamento via PIX
                            </label>

                            <label class="payment-option-label">
                                <input type="radio" name="forma_pagamento_selecionada" value="boleto" onclick="onclick="atualizarEstiloRadio(this)">
                                <i class="fas fa-barcode"></i> Boleto Bancário
                            </label>
                        </div>

                        <button type="submit" class="btn-checkout">
                            <i class="fas fa-lock"></i> Concluir Compra Seguro
                        </button>
                    </form>
                </aside>

            </div>

        <?php endif; ?>

    </div>

    <script src="assets/js/carrinho.js"></script>
</body>
</html>