<?php
require_once 'conf.php';

$email = $_POST['email'] ?? '';
$senha_usuario = $_POST['senha'] ?? '';

if ($email != '' && $senha_usuario != '') {

    $conexao = new PDO(dsn, usuario, senha);

    $SQL = 'SELECT * FROM usuarios WHERE email = :email AND senha = :senha';

    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':email', $email);
    $comando->bindValue(':senha', $senha_usuario);

    $comando->execute();
    $usuario_encontrado = $comando->fetch(PDO::FETCH_ASSOC);

    if ($usuario_encontrado) {
        echo 'Login realizado com sucesso! Bem-vindo(a), ' . htmlspecialchars($usuario_encontrado['nome']);
    } else {
        echo 'E-mail ou senha incorretos!';
    }
}
?>