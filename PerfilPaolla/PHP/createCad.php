<?php
require_once 'conf.php';

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha_usuario = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

// Monta os parâmetros para preservar o Nome e E-mail digitados
$dadosPreenchidos = '&nome=' . urlencode($nome) . '&email=' . urlencode($email);

// 1. Verifica se algum campo está vazio
if (empty($nome) || empty($email) || empty($senha_usuario) || empty($confirmar_senha)) {
    header('Location: ../Cadastro.html?erro=vazio' . $dadosPreenchidos);
    exit();
}

// 2. Senhas diferentes: Redireciona mantendo Nome e E-mail
if ($senha_usuario !== $confirmar_senha) {
    header('Location: ../Cadastro.html?erro=senhas_diferentes' . $dadosPreenchidos);
    exit();
}

try {
    $conexao = getConexao();

    // 3. Verifica se o e-mail já está cadastrado
    $sqlCheck = 'SELECT id FROM usuarios WHERE email = :email';
    $cmdCheck = $conexao->prepare($sqlCheck);
    $cmdCheck->bindValue(':email', $email);
    $cmdCheck->execute();

    if ($cmdCheck->fetch()) {
        header('Location: ../Cadastro.html?erro=email_existente' . $dadosPreenchidos);
        exit();
    }

    // 4. Cadastra o usuário
    $senha_hash = password_hash($senha_usuario, PASSWORD_DEFAULT);

    $SQL = 'INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)';
    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':nome', $nome);
    $comando->bindValue(':email', $email);
    $comando->bindValue(':senha', $senha_hash);

    if ($comando->execute()) {
        header('Location: ../Login.html?msg=cadastrado');
        exit();
    }

} catch (PDOException $e) {
    header('Location: ../Cadastro.html?erro=banco' . $dadosPreenchidos);
    exit();
}
?>