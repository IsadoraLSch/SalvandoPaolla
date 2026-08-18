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

    // Busca no banco integrando usuarios e clientes via Chave Estrangeira (usuario_id)
    $SQL = 'SELECT u.id AS usuario_id, u.email, u.senha, c.id AS cliente_id, c.nome 
    FROM usuarios u 
    INNER JOIN clientes c ON u.id = c.usuario_id 
    WHERE u.email = :email';

    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':email', $email);
    $comando->execute();

    $usuario = $comando->fetch(PDO::FETCH_ASSOC);

    if ($usuario && (password_verify($senha_cliente, $usuario['senha']) || $senha_cliente === $usuario['senha'])) {
    $_SESSION['cliente_id'] = $usuario['cliente_id'];
    $_SESSION['usuario_id'] = $usuario['usuario_id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_email'] = $usuario['email'];

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