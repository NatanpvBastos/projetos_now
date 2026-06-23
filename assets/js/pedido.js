// assets/js/pedido.js

/**
 * Seleciona e copia o texto de um input para a área de transferência.
 * Funciona nativamente tanto em computadores como em dispositivos móveis.
 * @param {string} inputId - O ID do elemento input (ex: 'pix-string' ou 'boleto-string').
 */
function copiarTexto(inputId) {
    const inputTexto = document.getElementById(inputId);
    if (!inputTexto) {
        console.warn(`Elemento com o ID "${inputId}" não foi encontrado na página.`);
        return;
    }

    // Seleciona o conteúdo do campo de entrada
    inputTexto.select();
    inputTexto.setSelectionRange(0, 99999); // Suporte de compatibilidade para ecrãs móveis

    try {
        // Executa a API de cópia moderna ou adota o fallback clássico
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(inputTexto.value)
                .then(() => {
                    notificarSucessoCopia(inputId);
                })
                .catch(err => {
                    console.error('Erro na API Clipboard, tentando fallback: ', err);
                    executarFallbackCopia(inputTexto, inputId);
                });
        } else {
            executarFallbackCopia(inputTexto, inputId);
        }
    } catch (err) {
        console.error('Erro crítico ao executar a cópia do código: ', err);
    }
}

/**
 * Fallback clássico para navegadores antigos ou ambientes sem HTTPS (localhost)
 */
function ejecutarFallbackCopia(elemento, inputId) {
    try {
        document.execCommand('copy');
        notificarSucessoCopia(inputId);
    } catch (err) {
        console.error('Fallback de cópia também falhou: ', err);
    }
}

/**
 * Altera visualmente o botão de cópia para dar um feedback instantâneo ao utilizador.
 * Transforma o ícone num check verde por 2 segundos.
 * @param {string} inputId - O ID do input associado ao clique.
 */
function notificarSucessoCopia(inputId) {
    // Procura o botão dinamicamente com base no atributo onclick exato configurado no HTML
    const botao = document.querySelector(`button[onclick="copiarTexto('${inputId}')"]`);
    
    if (botao) {
        const iconeOriginal = botao.innerHTML;
        
        // Aplica o estilo de sucesso (ícone de check verde e fundo verde claro)
        botao.innerHTML = '<i class="fas fa-check" style="color: #28a745;"></i>';
        botao.style.backgroundColor = '#e8fff0';
        botao.style.borderColor = '#28a745';
        
        // Remove o feedback visual após 2 segundos (2000 milissegundos)
        setTimeout(() => {
            botao.innerHTML = iconeOriginal;
            botao.style.backgroundColor = '';
            botao.style.borderColor = '';
        }, 2000);
    }
}