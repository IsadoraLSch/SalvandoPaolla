<?php
session_start();
require_once 'conf.php';

$email = trim($_POST['email'] ?? '');
$senha_cliente = $_POST['senha'] ?? '';

// Parâmetro para preservar o e-mail preenchido no input em caso de erro
$dadosPreenchidos = '&email=' . urlencode($email);

// 1. Verifica se os campos foram preenchidos
if (empty($email) || empty($senha_cliente)) {
    header('Location: ../Login.html?erro=vazio' . $dadosPreenchidos);
    exit();
}

try {
    $conexao = getConexao();

        // Busca o registro na tabela de LOGIN (clientes)
    $SQL = 'SELECT * FROM clientes WHERE email = :email';
    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':email', $email);
    $comando->execute();

    $cliente = $comando->fetch(PDO::FETCH_ASSOC);

    // Salva as sessões com o prefixo 'cliente'
    if ($cliente && (password_verify($senha_cliente, $cliente['senha']) || $senha_cliente === $cliente['senha'])) {
        $_SESSION['cliente_id'] = $cliente['id'];
        $_SESSION['cliente_nome'] = $cliente['nome'];
        $_SESSION['cliente_email'] = $cliente['email'];

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