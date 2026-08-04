// ==========================================
// CONTROLE DE ABRIR E FECHAR OS POP-UPS (MODAIS)
// ==========================================

// Abrir 1º Pop-up (Redefinir senha)
function abrirPopUp(event) {
    event.preventDefault();
    document.getElementById('popUpRedefinir').showModal();
}

// Fechar 1º Pop-up
function fecharPopUp() {
    document.getElementById('popUpRedefinir').close();
}

// Fechar 2º Pop-up (Nova Senha)
function fecharPopUpNovaSenha() {
    document.getElementById('popUpNovaSenha').close();
}