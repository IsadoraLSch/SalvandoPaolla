<?php
include "conf.inc.php";

$id = isset($_POST['id']) ? $_POST['id'] : 0;
$nome = isset($_POST['nome'])?$_POST['nome']:"";
$email = isset($_POST['email'])?$_POST['email']:"";
$matricula = isset($_POST['matricula'])?$_POST['matricula']:"";
$senha = isset($_POST['senha'])?$_POST['senha']:"";

//abrir conexão
$conexao = new PDO (dsn, usuario, senha);

//montar o SQL
$SQL = "UPDATE aluno
        SET nome = :nome,
        email = :email
        WHERE id = :id";

$consulta = $conexao->prepare($SQL);
$consulta->bindValue(':nome', $nome);
$consulta->bindValue(':email', $email);
$consulta->bindValue(':id', $id);

if($consulta->execute()){
    echo "Dados atualizados <br>
    <a href='list.php'>Voltar para lista? </a>";
} else {
    echo "Erro ao atualizar";
}
?>
