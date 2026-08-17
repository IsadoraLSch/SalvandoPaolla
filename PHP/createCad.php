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

    // 3. Verifica se o e-mail já está cadastrado (verifica na tabela usuarios ou clientes)
    $sqlCheck = 'SELECT id FROM usuarios WHERE email = :email';
    $cmdCheck = $conexao->prepare($sqlCheck);
    $cmdCheck->bindValue(':email', $email);
    $cmdCheck->execute();

    if ($cmdCheck->fetch()) {
        header('Location: ../Cadastro.html?erro=email_existente' . $dadosPreenchidos);
        exit();
    }

    // 4. Cadastra no banco de dados (Passo 1: usuarios -> Passo 2: clientes)
    $senha_hash = password_hash($senha_usuario, PASSWORD_DEFAULT);

    // Inicia a transação
    $conexao->beginTransaction();

    // Insert 1: Tabela 'usuarios'
    $SQL1 = 'INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)';
    $comando1 = $conexao->prepare($SQL1);
    $comando1->bindValue(':nome', $nome);
    $comando1->bindValue(':email', $email);
    $comando1->bindValue(':senha', $senha_hash);
    $comando1->execute();

    // Obtém o ID que acabou de ser gerado na tabela usuarios
    $idUsuario = $conexao->lastInsertId();

    // Insert 2: Tabela 'clientes' (com o mesmo ID, nome, email e senha)
    $SQL2 = 'INSERT INTO clientes (id, nome, email, senha) VALUES (:id, :nome, :email, :senha)';
    $comando2 = $conexao->prepare($SQL2);
    $comando2->bindValue(':id', $idUsuario);
    $comando2->bindValue(':nome', $nome);
    $comando2->bindValue(':email', $email);
    $comando2->bindValue(':senha', $senha_hash);
    $comando2->execute();

    // Confirma as duas inserções no banco
    $conexao->commit();

    header('Location: ../Login.html?msg=cadastrado');
    exit();

} catch (PDOException $e) {
    // Caso ocorra qualquer erro, desfaz as alterações em ambas as tabelas
    if (isset($conexao) && $conexao->inTransaction()) {
        $conexao->rollBack();
    }
    header('Location: ../Cadastro.html?erro=banco' . $dadosPreenchidos);
    exit();
}
?>