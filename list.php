<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
</head>
<body>
    <?php
    $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 0;
    $filtro = isset($_GET['filtro']) ? $_GET['filtro'] : 0;
//ligado ao 2form.php
//Instalar Code runner para poder testar o codigo direto no VScode

// inclui o texto de 'define' sem ter que repetir em todas os arquivos
include "conf.inc.php";

// Abrir conexão com o banco
$conexao = new PDO (dsn, usuario, senha);

// montar consulta
$sql = "SELECT * FROM aluno";

switch ($tipo){
    case 1:
        $sql .= " WHERE id = :filtro";
        break;
    case 2:
        $sql .= " WHERE nome = :filtro";
        break;
}

//preparar consulta
$consulta = $conexao->prepare($sql);

//enviar parametros da consulta
if ($tipo > 0){
    $consulta->bindValue(':filtro', $filtro);
}

//executar consulta
$consulta->execute();

//listar registros do banco
$registros = $consulta->fetchAll(); 
?>
   <form action="" method="get">
        <label for="tipo">tipo</label>
        <select name="tipo" id="tipo">
            <option value="">Selecione</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
        <input type="text" name="filtro">
        <button type="submit">Filtrar</button>
     </form>

 <table>
    <tr>
        <th>id </th>
        <th>nome </th>
        <th>email </th>
        <th>matricula </th>
    </tr>
 </table>
 <br>


<?php

foreach($registros as $aluno){
    echo "
    <tr><td> " . $aluno['id'] . " </td>" .
    "<td> " . $aluno['nome'] . " </td>" .
    "<td> " . $aluno['email']. " </td>" .
    "<td> " . $aluno['matricula']. " </td>" . 
    "<td> <a href='edit.php?id=0" . $aluno['id'] . "'>Alterar</a> </td>" .
    "<td> <a href='excluir.php?id=" . $aluno['id'] . "'>Excluir</a> </td>" .
    "</tr> <br><br>";
}
?>

</body>
</html>

