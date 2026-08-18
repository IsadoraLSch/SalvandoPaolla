<?php
session_start();
header('Content-Type: application/json');

// Garante que o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['erro' => 'Não autorizado']);
    exit();
}

// Retorna os dados da sessão formatados para o frontend
echo json_encode([
    'id' => $_SESSION['usuario_id'],
    'nome' => $_SESSION['usuario_nome'] ?? 'Usuário',
    'email' => $_SESSION['usuario_email'] ?? ''
]);
exit();
?>