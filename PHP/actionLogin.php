<?php
session_start();
require_once 'conf.php';

$email = trim($_POST['email'] ?? '');
$senha_usuario = $_POST['senha'] ?? '';

// Parâmetro para preservar o e-mail preenchido no input em caso de erro
$dadosPreenchidos = '&email=' . urlencode($email);

// 1. Verifica se os campos foram preenchidos
if (empty($email) || empty($senha_usuario)) {
    header('Location: ../Login.html?erro=vazio' . $dadosPreenchidos);
    exit();
}

try {
    $conexao = getConexao();

    // 2. Busca o usuário pelo e-mail
    $SQL = 'SELECT * FROM usuarios WHERE email = :email';
    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':email', $email);
    $comando->execute();

    $usuario = $comando->fetch(PDO::FETCH_ASSOC);

    // 3. Valida a senha (com suporte para password_hash ou texto puro)
    if ($usuario && (password_verify($senha_usuario, $usuario['senha']) || $senha_usuario === $usuario['senha'])) {
        
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        
        // Login bem-sucedido: Redireciona para o Painel
        header('Location: ../Painel.php');
        exit();

    } else {
        // Credenciais inválidas
        header('Location: ../Login.html?erro=invalido' . $dadosPreenchidos);
        exit();
    }

} catch (PDOException $e) {
    header('Location: ../Login.html?erro=invalido' . $dadosPreenchidos);
    exit();
}
?>