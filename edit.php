<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit</title>
</head>
<body>

<?php
//ligado ao 2form.php
//Instalar Code runner para poder testar o codigo direto no VScode

// inclui o texto de 'define' sem ter que repetir em todas os arquivos
include "conf.inc.php";
$id = isset($_GET['id'])?$_GET['id']:0;
$aluno = array();

if ($id > 0){
// Abrir conexão com o banco
$conexao = new PDO (dsn, usuario, senha);

// montar consulta
$sql = "SELECT * 
        FROM aluno
        WHERE id = :id";

//preparar consulta
$consulta = $conexao->prepare($sql);

//enviar parametros da consulta
$consulta->bindvalue(':id', $id);

//executar consulta
$consulta->execute();

//listar registros do banco
$aluno = $consulta->fetch(); 
}
?>

<form action="atualizar.php" method="POST">

        <label for="id">Id</label><br>
        <input type="text" name="id" id="" readonly value="<?=isset($aluno) ? $aluno['id']:0?>"><br>

        <label for="nome">Nome</label><br>
        <input type="text" name="nome" id="" value="<?=isset($aluno) ? $aluno['nome']:0?>"><br>

        <label for="email">Email</label><br>
        <input type="email" name="email" id="" value="<?=isset($aluno) ? $aluno['email']:0?>"><br>

        <label for="matricula">Matricula</label><br>
        <input type="text" name="matricula" id="" value="<?=isset($aluno) ? $aluno['matricula']:0?>"><br>

        <input type="submit" value="Enviar">
    </form>

</body>
</html>

