// assets/js/carrinho.js

/**
 * Pequena melhoria de UI para destacar visualmente o método ativo no carrinho
 */
function atualizarEstiloRadio(elemento) {
    document.querySelectorAll('.payment-option-label').forEach(label => {
        label.classList.remove('selected');
    });
    if (elemento.checked) {
        elemento.parentElement.classList.add('selected');
    }
}