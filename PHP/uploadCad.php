<?php
require_once 'conf.php';

$id = $_POST['id'] ?? null;
$email = $_POST['email'] ?? '';
$nome = $_POST['nome'] ?? '';
$nova_senha = $_POST['nova_senha'] ?? '';

$conexao = getConexao();

// Redefinição de Senha por E-mail (Pop-up 2)
if (!$id && !empty($email) && !empty($nova_senha)) {
    $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
    $SQL = 'UPDATE usuarios SET senha = :senha WHERE email = :email';
    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':senha', $senha_hash);
    $comando->bindValue(':email', $email);
    $comando->execute();

    if ($comando->rowCount() > 0) {
        header('Location: ../Login.html?msg=senha_alterada');
        exit();
    } else {
        header('Location: ../Login.html?erro=email_nao_encontrado');
        exit();
    }
} 
?>