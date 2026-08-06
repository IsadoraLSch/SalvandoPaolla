<?php
require_once 'conf.php';

$nome =$_POST['nome'] ?? '';
$email =$_POST['email'] ?? '';
$senha_usuario =$_POST['senha'] ?? '';
$confirmar_senha =$_POST['confirmar_senha'] ?? '';

if ($senha_usuario !==$confirmar_senha) {
    die('As senhas não coincidem! <a href="../Cadastro.html">Voltar</a>');
}

if (!empty($nome) && !empty($email) && !empty($senha_usuario)) {$conexao = getConexao();

    $senha_hash = password_hash($senha_usuario, PASSWORD_DEFAULT);

    $SQL = 'INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)';$comando = $conexao->prepare($SQL);
    $comando->bindValue(':nome',$nome);
    $comando->bindValue(':email',$email);
    $comando->bindValue(':senha',$senha_hash);

    if ($comando->execute()) {
        header('Location: ../Login.html?msg=cadastrado');
        exit();
    } else {
        echo 'Erro ao cadastrar usuário.';
    }
} else {
    echo 'Preencha todos os campos!';
}
?>