// assets/js/mascaras-pagamento.js

document.addEventListener('DOMContentLoaded', function() {
    const cardNumber = document.getElementById('card-number');
    const cardExpiry = document.getElementById('card-expiry');
    const cardCvv = document.getElementById('card-cvv');

    // Máscara do Número do Cartão (Agrupa de 4 em 4 dígitos)
    if(cardNumber) {
        cardNumber.addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '');
            v = v.replace(/(\d{4})(?=\d)/g, '$1 ');
            e.target.value = v;
        });
    }

    // Máscara da Validade (Adiciona a barra automaticamente: MM/AA)
    if(cardExpiry) {
        cardExpiry.addEventListener('input', function(e) {
            let v = e.target.value.replace(/\D/g, '');
            if (v.length >= 2) {
                v = v.substring(0, 2) + '/' + v.substring(2, 4);
            }
            e.target.value = v;
        });
    }

    // Máscara do CVV (Permite apenas números de controle)
    if(cardCvv) {
        cardCvv.addEventListener('input', function(e) {
            e.target.value = e.target.value.replace(/\D/g, '');
        });
    }
});