<?php
require_once 'conf.php';

function listarUsuarios() {
    $conexao = getConexao();
    $SQL = 'SELECT id, nome, email FROM usuarios ORDER BY id DESC';
    $comando = $conexao->prepare($SQL);
    $comando->execute();
    return $comando->fetchAll(PDO::FETCH_ASSOC);
}

function buscarUsuarioPorId($id) {
    $conexao = getConexao();
    $SQL = 'SELECT id, nome, email FROM usuarios WHERE id = :id';
    $comando = $conexao->prepare($SQL);
    $comando->bindValue(':id', $id);
    $comando->execute();
    return $comando->fetch(PDO::FETCH_ASSOC);
}

// Se acessado diretamente via HTTP, retorna JSON
if (basename(__FILE__) == basename($_SERVER['SCRIPT_FILENAME'])) {
    header('Content-Type: application/json');
    echo json_encode(listarUsuarios());
}
?>