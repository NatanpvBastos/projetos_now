// assets/js/pagamento.js

/**
 * Altera a aba de pagamento ativa, atualiza o input oculto para o formulário
 * e gerencia os campos obrigatórios do cartão de crédito.
 * @param {string} method - O método de pagamento ('cartao', 'pix' ou 'boleto')
 */
function switchMethod(method) {
    const hiddenInput = document.getElementById("forma_pagamento_selecionada");
    
    // 1. Atualiza o input hidden para enviar o valor correto via POST
    if (hiddenInput) {
        hiddenInput.value = method;
    }

    // 2. Controla o estado visual ativo das abas (CSS)
    const tabs = document.querySelectorAll(".method-tab");
    tabs.forEach(tab => tab.classList.remove("active"));

    // Encontra a aba correspondente com base no texto ou na chamada inline
    const activeTab = [...tabs].find(tab => {
        const onClickAttr = tab.getAttribute("onclick");
        return onClickAttr && onClickAttr.includes(`'${method}'`);
    });
    if (activeTab) {
        activeTab.classList.add("active");
    }

    // 3. Controla a visibilidade dos blocos de conteúdo informativos
    const contents = document.querySelectorAll(".payment-content");
    contents.forEach(content => content.classList.remove("active"));
    
    const targetContent = document.getElementById("content-" + method);
    if (targetContent) {
        targetContent.classList.add("active");
    }

    // 4. Validação Dinâmica: Remove o required se for BOLETO ou PIX
    const cardInputs = document.querySelectorAll("#content-cartao input");
    cardInputs.forEach(input => {
        if (method === "cartao") {
            input.setAttribute("required", "required");
        } else {
            input.removeAttribute("required");
        }
    });
}

// Inicialização segura quando o DOM carregar
document.addEventListener("DOMContentLoaded", function() {
    const hiddenInput = document.getElementById("forma_pagamento_selecionada");
    if (hiddenInput) {
        // Força o estado do formulário baseado no valor padrão inicial ('cartao')
        switchMethod(hiddenInput.value);
    }
});