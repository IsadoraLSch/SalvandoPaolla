<?php
require_once 'conf.php';

$nome = trim($_POST['nome'] ?? '');
$email = trim($_POST['email'] ?? '');
$senha_usuario = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

$dadosPreenchidos = '&nome=' . urlencode($nome) . '&email=' . urlencode($email);

if (empty($nome) || empty($email) || empty($senha_usuario) || empty($confirmar_senha)) {
    header('Location: ../Cadastro.html?erro=vazio' . $dadosPreenchidos);
    exit();
}

if ($senha_usuario !== $confirmar_senha) {
    header('Location: ../Cadastro.html?erro=senhas_diferentes' . $dadosPreenchidos);
    exit();
}

try {
    $conexao = getConexao();

    // 1. Verifica se e-mail já existe na tabela de login (usuarios)
    $sqlCheck = 'SELECT id FROM usuarios WHERE email = :email';
    $cmdCheck = $conexao->prepare($sqlCheck);
    $cmdCheck->bindValue(':email', $email);
    $cmdCheck->execute();

    if ($cmdCheck->fetch()) {
        header('Location: ../Cadastro.html?erro=email_existente' . $dadosPreenchidos);
        exit();
    }

    $senha_hash = password_hash($senha_usuario, PASSWORD_DEFAULT);

    // Inicia a transação
    $conexao->beginTransaction();

    // Insert 1: Insere login/senha na tabela 'usuarios'
    $SQL1 = 'INSERT INTO usuarios (email, senha) VALUES (:email, :senha)';
    $comando1 = $conexao->prepare($SQL1);
    $comando1->bindValue(':email', $email);
    $comando1->bindValue(':senha', $senha_hash);
    $comando1->execute();

    // Obtém a Chave Primária (id) recém-criada
    $idUsuario = $conexao->lastInsertId();

    // Insert 2: Insere os dados de perfil na tabela 'clientes' relacionando com a Chave Estrangeira (usuario_id)
    $SQL2 = 'INSERT INTO clientes (nome, usuario_id) VALUES (:nome, :usuario_id)';
    $comando2 = $conexao->prepare($SQL2);
    $comando2->bindValue(':nome', $nome);
    $comando2->bindValue(':usuario_id', $idUsuario);
    $comando2->execute();

    // Confirma a transação nas duas tabelas
    $conexao->commit();

    header('Location: ../Login.html?msg=cadastrado');
    exit();

} catch (PDOException $e) {
    if (isset($conexao) && $conexao->inTransaction()) {
        $conexao->rollBack();
    }
    header('Location: ../Cadastro.html?erro=banco' . $dadosPreenchidos);
    exit();
}
?>