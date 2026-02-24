<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create</title>
</head>
<body>
    <?php
//ligado ao 2form.php
//Instalar Code runner para poder testar o codigo direto no VScode

// inclui o texto de 'define' sem ter que repetir em todas os arquivos
include "conf.inc.php";

// Abrir conexão com o banco
$conexao = new PDO (dsn, usuario, senha);

// montar consulta
$sql = "SELECT * FROM aluno";

//preparar consulta
$consulta = $conexao->prepare($sql);

//enviar parametros da consulta
//pass

//executar consulta
$consulta->execute();

//listar registros do banco
$registros = $consulta->fetchAll(); 
?>
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
    "<td> " . $aluno['matricula']. " </td>
    </tr> <br><br>";
}
?>

</body>
</html>

