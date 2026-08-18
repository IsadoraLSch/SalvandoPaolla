<?php
session_start();
require_once 'conf.php';

// Garante que o usuário esteja logado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../Login.html?erro=acesso_negado');
    exit();
}

$id = $_SESSION['usuario_id'];
$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha_antiga = $_POST['senha_antiga'] ?? '';
$nova_senha = $_POST['nova_senha'] ?? '';

if (empty($nome) || empty($email)) {
    header('Location: ../perfil.php?erro=campos_vazios');
    exit();
}

try {
    $conexao = getConexao();

    // 1. Busca os dados atuais do usuário para validar a senha antiga se for alterá-la
    $sqlBusca = 'SELECT senha FROM usuarios WHERE id = :id';
    $cmdBusca = $conexao->prepare($sqlBusca);
    $cmdBusca->bindValue(':id', $id);
    $cmdBusca->execute();
    $usuario = $cmdBusca->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        header('Location: ../Login.html');
        exit();
    }

    // 2. Se informou nova senha, faz as validações de segurança
    $atualizarSenha = false;
    $senha_hash = $usuario['senha'];

    if (!empty($nova_senha)) {
        // Valida se a senha antiga foi informada
        if (empty($senha_antiga)) {
            header('Location: ../perfil.php?erro=senha_antiga_obrigatoria');
            exit();
        }

        // Valida se a senha antiga está correta
        if (!password_verify($senha_antiga, $usuario['senha']) && $senha_antiga !== $usuario['senha']) {
            header('Location: ../perfil.php?erro=senha_antiga_incorreta');
            exit();
        }

        // Valida se a nova senha é igual à antiga
        if ($senha_antiga === $nova_senha) {
            header('Location: ../perfil.php?erro=senha_igual_antiga');
            exit();
        }

        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $atualizarSenha = true;
    }

    // 3. Atualiza os dados no Banco
    $SQL = 'UPDATE usuarios SET nome = :nome, email = :email, senha = :senha WHERE id = :id';
    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':nome', $nome);
    $comando->bindValue(':email', $email);
    $comando->bindValue(':senha', $senha_hash);
    $comando->bindValue(':id', $id);

    if ($comando->execute()) {
        // Atualiza as variáveis de sessão
        $_SESSION['usuario_nome'] = $nome;
        $_SESSION['usuario_email'] = $email;

        header('Location: ../perfil.php?msg=sucesso');
        exit();
    }

} catch (PDOException $e) {
    header('Location: ../perfil.php?erro=banco');
    exit();
}
?>