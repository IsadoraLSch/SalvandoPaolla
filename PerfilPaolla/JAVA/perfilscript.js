const indicator = document.getElementById('tab-indicator');
    const navContainer = document.getElementById('nav-container');

    function moveIndicator(element) {
      const navRect = navContainer.getBoundingClientRect();
      const targetRect = element.getBoundingClientRect();

      // Ajusta a largura e a posição X do indicador deslizante
      indicator.style.width = `${targetRect.width}px`;
      indicator.style.transform = `translateX(${targetRect.left - navRect.left}px)`;
    }

    function switchTab(selectedBtn, tabId) {
      // 1. Move a pílula/fundo azul
      moveIndicator(selectedBtn);

      // 2. Ajusta os estilos do texto dos botões
      document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('font-extrabold');
        btn.classList.add('hover:text-azul-claro');
      });
      selectedBtn.classList.add('font-extrabold');
      selectedBtn.classList.remove('hover:text-azul-claro');

      // 3. Alterna o conteúdo visível
      document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
      });
      document.getElementById(`content-${tabId}`).classList.remove('hidden');
    }

    // Inicializa a posição do indicador na aba ativa na primeira renderização
    window.addEventListener('DOMContentLoaded', () => {
      const activeBtn = document.querySelector('.tab-btn');
      moveIndicator(activeBtn);
    });

    // Recalcula o indicador caso a janela mude de tamanho
    window.addEventListener('resize', () => {
      const activeBtn = document.querySelector('.tab-btn.font-extrabold');
      if (activeBtn) moveIndicator(activeBtn);
    });

    document.addEventListener('DOMContentLoaded', () => {
        document.addEventListener('DOMContentLoaded', () => {
            // --- 1. ELEMENTOS DA DOM ---
            const indicator = document.getElementById('tab-indicator');
            const navContainer = document.getElementById('nav-container');
            const tabBtns = document.querySelectorAll('.tab-btn');
            
            const modalEditar = document.getElementById('modalEditar');
            const btnAbrirEditar = document.getElementById('btnAbrirEditar');
            const btnFecharModal = document.getElementById('btnFecharModal');
            const btnExcluirPerfil = document.getElementById('btnExcluirPerfil');
            const formEditar = document.getElementById('formEditarPerfil');
          
            // --- 2. BUSCA DE DADOS DO USUÁRIO VIA API ---
            fetch('perfil.php?action=get_data')
              .then(response => {
                if (!response.ok) throw new Error('Não autenticado');
                return response.json();
              })
              .then(data => {
                if (data.erro) {
                  window.location.href = 'Login.html?erro=acesso_negado';
                  return;
                }
                // Preenche os dados do perfil no topo e na modal
                if (document.getElementById('usuarioNome')) document.getElementById('usuarioNome').innerText = data.nome;
                if (document.getElementById('usuarioEmail')) document.getElementById('usuarioEmail').innerText = data.email;
                if (document.getElementById('modalNomeUser')) document.getElementById('modalNomeUser').innerText = data.nome;
          
                // Preenche os inputs do formulário de edição
                if (document.getElementById('inputNome')) document.getElementById('inputNome').value = data.nome;
              })
              .catch(() => {
                // Caso haja erro no fetch ou não esteja logado
              });
          
            // --- 3. ABRIR E FECHAR A MODAL DE EDIÇÃO ---
            if (btnAbrirEditar && modalEditar) {
              btnAbrirEditar.addEventListener('click', () => {
                modalEditar.classList.remove('hidden');
              });
            }
          
            if (btnFecharModal && modalEditar) {
              btnFecharModal.addEventListener('click', () => {
                modalEditar.classList.add('hidden');
              });
            }
          
            // Fechar clicando no fundo escuro (fora da caixa da modal)
            window.addEventListener('click', (e) => {
              if (e.target === modalEditar) {
                modalEditar.classList.add('hidden');
              }
            });
          
            // Action do botão de lixeira dentro da modal
            if (btnExcluirPerfil) {
              btnExcluirPerfil.addEventListener('click', () => {
                if (confirm('Deseja realmente excluir seu perfil? Esta ação não pode ser desfeita.')) {
                  window.location.href = 'PHP/deleteCad.php';
                }
              });
            }
          
            // --- 4. CONTROLE DE ABAS DA TELA ---
            function moveIndicator(element) {
              if (!element || !indicator || !navContainer) return;
              const navRect = navContainer.getBoundingClientRect();
              const targetRect = element.getBoundingClientRect();
              indicator.style.width = `${targetRect.width}px`;
              indicator.style.transform = `translateX(${targetRect.left - navRect.left}px)`;
            }
          
            function switchTab(selectedBtn) {
              const tabName = selectedBtn.getAttribute('data-tab');
              moveIndicator(selectedBtn);
          
              tabBtns.forEach(btn => {
                btn.classList.remove('font-extrabold');
                btn.classList.add('hover:text-azul-claro');
              });
              selectedBtn.classList.add('font-extrabold');
              selectedBtn.classList.remove('hover:text-azul-claro');
          
              document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
              });
              const targetContent = document.getElementById(`content-${tabName}`);
              if (targetContent) targetContent.classList.remove('hidden');
            }
          
            tabBtns.forEach(btn => {
              btn.addEventListener('click', () => switchTab(btn));
            });
          
            const firstTab = document.querySelector('.tab-btn');
            if (firstTab) {
              setTimeout(() => moveIndicator(firstTab), 100);
            }
            
            window.addEventListener('resize', () => {
              const activeTab = document.querySelector('.tab-btn.font-extrabold') || firstTab;
              if (activeTab) moveIndicator(activeTab);
            });
          });
      
        // --- 5. VALIDAÇÃO DE SENHA ANTES DE ENVIAR ---
        formEditar.addEventListener('submit', (e) => {
          const antiga = document.getElementById('senha_antiga').value;
          const nova = document.getElementById('nova_senha').value;
      
          if (nova.length > 0) {
            if (!antiga) {
              msgErroSenha.innerText = "Informe a senha antiga para cadastrar uma nova!";
              msgErroSenha.classList.remove('hidden');
              e.preventDefault();
              return;
            }
      
            if (antiga === nova) {
              msgErroSenha.innerText = "A nova senha não pode ser igual à senha antiga!";
              msgErroSenha.classList.remove('hidden');
              e.preventDefault();
              return;
            }
          }
          msgErroSenha.classList.add('hidden');
        });
      
        // --- 6. LEITURA DE PARÂMETROS DE ERRO/SUCESSO DA URL ---
        const urlParams = new URLSearchParams(window.location.search);
        const alertaContainer = document.getElementById('alertaContainer');
      
        if (urlParams.has('msg') || urlParams.has('erro')) {
          alertaContainer.classList.remove('hidden');
      
          if (urlParams.get('msg') === 'sucesso') {
            alertaContainer.innerHTML = `<div class="bg-green-600 text-white p-4 rounded-xl shadow-lg border-2 border-white font-bold">Perfil atualizado com sucesso!</div>`;
          } else if (urlParams.has('erro')) {
            let texto = 'Ocorreu um erro ao processar.';
            switch (urlParams.get('erro')) {
              case 'senha_antiga_obrigatoria': texto = 'Senha antiga é obrigatória para redefinição.'; break;
              case 'senha_antiga_incorreta': texto = 'A senha antiga digitada está incorreta.'; break;
              case 'senha_igual_antiga': texto = 'A nova senha não pode ser igual à antiga.'; break;
            }
            alertaContainer.innerHTML = `<div class="bg-red-600 text-white p-4 rounded-xl shadow-lg border-2 border-white font-bold">${texto}</div>`;
          }
      
          setTimeout(() => alertaContainer.classList.add('hidden'), 4000);
        }
      });

      document.addEventListener('DOMContentLoaded', () => {
        const modalEditar = document.getElementById('modalEditar');
        const btnAbrirEditar = document.querySelector('button[title="Editar"]');
        const btnFecharModal = document.getElementById('btnFecharModal');
      
        if (btnAbrirEditar && modalEditar) {
          // Abrir o Modal ao Clicar em Editar
          btnAbrirEditar.addEventListener('click', () => {
            modalEditar.classList.remove('hidden');
          });
        }
      
        if (btnFecharModal && modalEditar) {
          // Fechar o Modal
          btnFecharModal.addEventListener('click', () => {
            modalEditar.classList.add('hidden');
          });
        }
      
        // Fechar o modal clicando fora da caixa
        window.addEventListener('click', (e) => {
          if (e.target === modalEditar) {
            modalEditar.classList.add('hidden');
          }
        });
      });