<?php
require_once 'conf.php';

$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$senha_usuario = $_POST['senha'] ?? '';
$confirmar_senha = $_POST['confirmar_senha'] ?? '';

if ($senha_usuario !== $confirmar_senha) {
    die('As senhas não coincidem!');
}

if ($nome != '' && $email != '' && $senha_usuario != '') {

    $conexao = new PDO(dsn, usuario, senha);

    $SQL = 'INSERT INTO usuarios 
            (nome, email, senha) 
            VALUES 
            (:nome, :email, :senha)';

    $comando = $conexao->prepare($SQL);

    $comando->bindValue(':nome', $nome);
    $comando->bindValue(':email', $email);
    $comando->bindValue(':senha', $senha_usuario);

    if ($comando->execute()){
        echo 'Usuário cadastrado com sucesso! <a href="Login.html">Ir para Login</a>';
    } else {
        echo 'Erro ao cadastrar usuário.';
    }
}
?>