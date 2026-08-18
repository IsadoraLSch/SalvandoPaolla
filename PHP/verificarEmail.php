<?php
require_once 'conf.php';

header('Content-Type: application/json');

$email = trim($_POST['email'] ?? '');

if (empty($email)) {
    echo json_encode(['existe' => false, 'mensagem' => 'E-mail não informado.']);
    exit();
}

try {
    $conexao = getConexao();
    $SQL = 'SELECT id FROM usuarios WHERE email = :email';
    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':email', $email);
    $comando->execute();

    if ($comando->fetch()) {
        echo json_encode(['existe' => true]);
    } else {
        echo json_encode(['existe' => false, 'mensagem' => 'Este e-mail não está cadastrado no sistema.']);
    }
} catch (PDOException $e) {
    echo json_encode(['existe' => false, 'mensagem' => 'Erro ao consultar o banco de dados.']);
}
?>