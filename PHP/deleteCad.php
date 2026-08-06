<?php
require_once 'conf.php';

$id = $_POST['id'] ?? $_GET['id'] ?? null;

if ($id) {
    $conexao = getConexao();
    $SQL = 'DELETE FROM usuarios WHERE id = :id';
    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':id', $id);

    if ($comando->execute()) {
        header('Location: ../Painel.php?msg=deletado');
        exit();
    } else {
        echo 'Erro ao deletar usuário.';
    }
} else {
    echo 'ID de usuário inválido.';
}
?>