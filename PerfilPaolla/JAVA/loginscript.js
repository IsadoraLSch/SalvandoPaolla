// js/login.js

function abrirPopUpRedefinir(e) {
    e.preventDefault();
    document.getElementById('popUpRedefinir').showModal();
}

async function validarEAvancar(e) {
    e.preventDefault();
    const email = document.getElementById('email-redefinir').value.trim();
    const codigo = document.getElementById('codigo-verificacao').value.trim();
    const btnAvancar = document.getElementById('btnAvancar');

    if (codigo !== '123456') {
        exibirAlert('Código Incorreto', 'O código de verificação digitado é inválido!');
        return;
    }

    btnAvancar.innerText = 'Verificando...';
    btnAvancar.disabled = true;

    try {
        const formData = new FormData();
        formData.append('email', email);

        const resposta = await fetch('PHP/verificarEmail.php', {
            method: 'POST',
            body: formData
        });
        const resultado = await resposta.json();

        if (!resultado.existe) {
            exibirAlert('E-mail Não Encontrado', resultado.mensagem || 'Este e-mail não possui cadastro.');
            return;
        }

        document.getElementById('email-final').value = email;
        document.getElementById('popUpRedefinir').close();
        document.getElementById('popUpNovaSenha').showModal();

    } catch (err) {
        exibirAlert('Erro', 'Ocorreu um erro ao validar o e-mail.');
    } finally {
        btnAvancar.innerText = 'Avançar';
        btnAvancar.disabled = false;
    }
}

function validarSenhasFinais(e) {
    const novaSenha = document.getElementById('nova-senha').value;
    const confirmarNovaSenha = document.getElementById('confirmar-nova-senha').value;

    if (novaSenha !== confirmarNovaSenha) {
        e.preventDefault();
        exibirAlert('Atenção', 'As senhas digitadas não coincidem!');
    }
}

function exibirAlert(titulo, mensagem) {
    document.getElementById('alertTitulo').innerText = titulo;
    document.getElementById('alertMensagem').innerText = mensagem;
    document.getElementById('popUpAlert').showModal();
}

function fecharEAntilimparURL() {
    document.getElementById('popUpAlert').close();
    window.history.replaceState({}, document.title, window.location.pathname);
}

window.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    
    if (params.has('email')) {
        document.getElementById('email').value = params.get('email');
    }

    if (params.has('erro')) {
        const erro = params.get('erro');
        if (erro === 'invalido') {
            exibirAlert('Acesso Negado', 'E-mail ou senha incorretos! Verifique suas credenciais.');
        } else if (erro === 'vazio') {
            exibirAlert('Atenção', 'Por favor, preencha todos os campos!');
        } else if (erro === 'email_nao_encontrado') {
            exibirAlert('Erro', 'Este e-mail não foi encontrado no banco de dados.');
        } else if (erro === 'senhas_diferentes') {
            exibirAlert('Atenção', 'As senhas digitadas não coincidem!');
        } else if (erro === 'acesso_negado') {
            exibirAlert('Acesso Restrito', 'Você precisa estar logado para acessar essa página!');
        }
    }

    if (params.has('msg')) {
        const msg = params.get('msg');
        if (msg === 'cadastrado') {
            exibirAlert('Sucesso!', 'Conta criada com sucesso! Faça seu login para acessar.');
        } else if (msg === 'senha_alterada') {
            exibirAlert('Sucesso!', 'Sua senha foi redefinida com sucesso!');
        }
    }
});