<?php

include "conf.inc.php";

$id = $_POST['id'] ?? 0;
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$matricula = $_POST['matricula'] ?? '';
$senha = $_POST['senha'] ?? '';

$conexao = new PDO(dsn, usuario, senha);

if ($id > 0) {

    // UPDATE
    $SQL = "UPDATE aluno 
            SET nome = :nome,
                email = :email,
                matricula = :matricula
            WHERE id = :id";

    $consulta = $conexao->prepare($SQL);
    $consulta->bindValue(':id', $id);

} else {

    // INSERT
    $SQL = "INSERT INTO aluno (nome, email, matricula, senha) 
            VALUES (:nome, :email, :matricula, :senha)";

    $consulta = $conexao->prepare($SQL);
    $consulta->bindValue(':senha', $senha);
}

$consulta->bindValue(':nome', $nome);
$consulta->bindValue(':email', $email);
$consulta->bindValue(':matricula', $matricula);

    //Executar
    if ($consulta->execute()){
        echo 'Dados inseridos com sucesso';
    } else {
        echo 'Erro ao inserir dados no banco';
    }
?>
