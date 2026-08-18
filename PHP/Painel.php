<?php
session_start();

// Verifica se há sessão de cliente ativa, impedindo o acesso sem login e redireciona para o login
if (!isset($_SESSION['cliente_id'])) {
    header('Location: Login.html?erro=acesso_negado');
    exit();
}

require_once 'PHP/readCad.php';
$usuarios = listarUsuarios();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-[#FFE8BD] p-8 text-[#1C4C70]">

    <div class="max-w-4xl mx-auto bg-white rounded-2xl p-6 shadow-xl border-4 border-[#1C4C70]">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Bem-vindo, <?php echo htmlspecialchars($_SESSION['usuario_nome']); ?>!</h1>
            <a href="PHP/logout.php" class="bg-red-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-red-600 transition">
                Sair
            </a>
        </div>

        <p class="mb-4 text-gray-700">Você está logado no sistema.</p>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-[#7AA9A3] text-white">
                        <th class="p-3 border">ID</th>
                        <th class="p-3 border">Nome</th>
                        <th class="p-3 border">E-mail</th>
                        <th class="p-3 border text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr class="border-b">
                            <td class="p-3 border"><?php echo $u['id']; ?></td>
                            <td class="p-3 border"><?php echo htmlspecialchars($u['nome']); ?></td>
                            <td class="p-3 border"><?php echo htmlspecialchars($u['email']); ?></td>
                            <td class="p-3 border text-center">
                                <form action="PHP/deleteCad.php" method="POST" class="inline">
                                    <input type="hidden" name="id" value="<?php echo $u['id']; ?>">
                                    <button type="submit" onclick="return confirm('Deseja excluir este usuário?')" class="bg-red-500 text-white px-3 py-1 rounded text-sm hover:bg-red-600">
                                        Excluir
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>