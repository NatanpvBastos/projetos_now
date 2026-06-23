<?php
// views/pedido-concluido.php
// NOTA ARCHITECTURAL: Toda a lógica de higienização do $_GET, geração da string PIX
// e cálculo da linha digitável do boleto foi externalizada para o PagamentoController.php.
// Este ficheiro atua estritamente como a View (Apresentação Visual).
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $tituloPagina ?? 'GRID&GOL - Pedido Concluído'; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/pedido.css">
</head>
<body>

    <div class="success-card">
        <div class="icon-box">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Pedido Recebido!</h1>
        <div class="order-number">Código do pedido: <strong>#<?php echo $idPedido; ?></strong></div>

        <div class="amount-box">
            Valor a Pagar: <span>R$ <?php echo number_format($total, 2, ',', '.'); ?></span>
        </div>

        <?php if ($metodo === 'pix'): ?>
            <div class="method-section">
                <h3><i class="fas fa-qrcode" style="color: #00bfa5;"></i> Pague via PIX Instantâneo</h3>
                <p style="font-size: 14px; color: #555; margin: 5px 0;">Abra o app do seu banco, escolha "Pagar com QR Code" e aponte a câmera para a imagem abaixo:</p>
                
                <div style="text-align: center; margin: 20px 0;">
                    <img class="qr-placeholder" 
                         src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=<?php echo urlencode($pixCopiaCola); ?>" 
                         alt="QR Code PIX GRID&GOL"
                         style="border: 1px solid #ddd; padding: 10px; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.05);">
                </div>
                
                <p style="font-size: 14px; color: #555; margin: 15px 0 5px 0;">Ou utilize o código <strong>PIX Copia e Cola</strong>:</p>
                <div class="input-copy-group">
                    <input type="text" id="pix-string" value="<?php echo $pixCopiaCola; ?>" readonly>
                    <button class="btn-copy" onclick="copiarTexto('pix-string')" title="Copiar código PIX">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
            </div>

        <?php elseif ($metodo === 'boleto'): ?>
            <div class="method-section">
                <h3><i class="fas fa-barcode" style="color: #ff9100;"></i> Código de Barras do Boleto</h3>
                <p style="font-size: 14px; color: #555; margin: 5px 0;">Copia a linha digitável abaixo para pagar no Internet Banking ou App do seu banco:</p>
                
                <div class="input-copy-group">
                    <input type="text" id="boleto-string" value="<?php echo $boletoLinhaDigitavel; ?>" readonly>
                    <button class="btn-copy" onclick="copiarTexto('boleto-string')" title="Copiar linha digitável">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>

                <div class="barcode-mock"></div>
                <small style="color: #777; display: block; text-align: center; margin-top: 10px;">
                    Vencimento: <?php echo date('d/m/Y', strtotime('+3 days')); ?> (Próximos 3 dias úteis)
                </small>
            </div>

        <?php else: ?>
            <div class="method-section" style="text-align: center; border-style: solid; border-color: #28a745; background: #f4fff7; padding: 20px; border-radius: 8px;">
                <p style="color: #28a745; font-weight: bold; margin: 0; font-size: 16px;">
                    <i class="fas fa-credit-card"></i> Pagamento aprovado via Cartão de Crédito!
                </p>
                <p style="font-size: 14px; color: #555; margin: 8px 0 0 0;">A sua operadora autorizou a transação e o pedido já está em preparação.</p>
            </div>
        <?php endif; ?>

        <a href="index.php?url=home" class="btn-home">Voltar para a Página Inicial</a>
    </div>
    <script src="assets/js/pedido.js"></script>
</body>
</html>