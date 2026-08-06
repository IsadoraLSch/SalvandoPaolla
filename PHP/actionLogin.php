<?php
session_start();
require_once 'conf.php';

$email = $_POST['email'] ?? '';
$senha_usuario = $_POST['senha'] ?? '';

if (!empty($email) && !empty($senha_usuario)) {
    $conexao = getConexao();

    $SQL = 'SELECT * FROM usuarios WHERE email = :email';
    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':email', $email);
    $comando->execute();

    $usuario = $comando->fetch(PDO::FETCH_ASSOC);

    if ($usuario && (password_verify($senha_usuario, $usuario['senha']) || $senha_usuario === $usuario['senha'])) {
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        
        // Redireciona com aviso de SUCESSO no login
        header('Location: ../Login.html?msg=login_sucesso');
        exit();
    } else {
        // Redireciona com aviso de ERRO
        header('Location: ../Login.html?erro=invalido');
        exit();
    }
} else {
    header('Location: ../Login.html?erro=vazio');
    exit();
}
?>