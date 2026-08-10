function exibirAlert(titulo, mensagem) {
    document.getElementById('alertTituloCad').innerText = titulo;
    document.getElementById('alertMensagemCad').innerText = mensagem;
    document.getElementById('popUpAlertCad').showModal();
}

function fecharEAntilimparURL() {
    document.getElementById('popUpAlertCad').close();
    // Mantém os dados na tela limpando apenas os parâmetros da URL
    window.history.replaceState({}, document.title, window.location.pathname);
}

window.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);

    // 1. Restaura o Nome e E-mail nos inputs se existirem na URL
    if (params.has('nome')) {
        document.getElementById('nome').value = params.get('nome');
    }
    if (params.has('email')) {
        document.getElementById('email').value = params.get('email');
    }

    // 2. Exibe os alertas de erro correspondentes
    if (params.has('erro')) {
        const erro = params.get('erro');
        if (erro === 'senhas_diferentes') {
            exibirAlert('Atenção', 'As senhas digitadas não coincidem!');
        } else if (erro === 'email_existente') {
            exibirAlert('Erro no Cadastro', 'Este e-mail já está cadastrado no sistema.');
        } else if (erro === 'vazio') {
            exibirAlert('Atenção', 'Por favor, preencha todos os campos!');
        }
    }
});