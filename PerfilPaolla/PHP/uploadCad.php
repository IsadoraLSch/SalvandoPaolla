<?php
require_once 'conf.php';

$id = $_POST['id'] ?? null;
$email = trim($_POST['email'] ?? '');
$nome = trim($_POST['nome'] ?? '');
$nova_senha = $_POST['nova_senha'] ?? '';
$confirmar_nova_senha = $_POST['confirmar_nova_senha'] ?? '';

try {
    $conexao = getConexao();

    // 1. Redefinição de Senha por E-mail (Modal de Login)
    if (!$id && !empty($email) && !empty($nova_senha)) {
        
        // Validação no servidor: confirmação de senhas
        if ($nova_senha !== $confirmar_nova_senha) {
            header('Location: ../Login.html?erro=senhas_diferentes&email=' . urlencode($email));
            exit();
        }

        // Criptografa a nova senha
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        
        $SQL = 'UPDATE usuarios SET senha = :senha WHERE email = :email';
        $comando = $conexao->prepare($SQL);
        $comando->bindValue(':senha', $senha_hash);
        $comando->bindValue(':email', $email);
        $comando->execute();

        // Verifica se a senha foi realmente atualizada
        if ($comando->rowCount() > 0) {
            header('Location: ../Login.html?msg=senha_alterada');
            exit();
        } else {
            header('Location: ../Login.html?erro=email_nao_encontrado');
            exit();
        }
    } 

    // 2. Edição de Dados via Painel (Update do CRUD)
    if ($id && !empty($nome) && !empty($email)) {
        $SQL = 'UPDATE usuarios SET nome = :nome, email = :email WHERE id = :id';
        $comando = $conexao->prepare($SQL);
        $comando->bindValue(':nome', $nome);
        $comando->bindValue(':email', $email);
        $comando->bindValue(':id', $id);

        if ($comando->execute()) {
            header('Location: ../Painel.php?msg=editado');
            exit();
        } else {
            echo 'Erro ao atualizar usuário no banco de dados.';
        }
    }

} catch (PDOException $e) {
    echo 'Erro no processamento: ' . $e->getMessage();
}
?>