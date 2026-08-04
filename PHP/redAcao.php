<?php
require_once 'conf.php';

$email = $_POST['email'] ?? '';
$nova_senha = $_POST['nova_senha'] ?? '';

if ($email != '' && $nova_senha != '') {

    $conexao = new PDO(dsn, usuario, senha);

    $SQL = 'UPDATE usuarios SET senha = :nova_senha WHERE email = :email';

    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':nova_senha', $nova_senha);
    $comando->bindValue(':email', $email);

    if ($comando->execute()){
        echo 'Senha alterada com sucesso! <a href="Login.html">Ir para Login</a>';
    } else {
        echo 'Erro ao redefinir a senha.';
    }
}
?>