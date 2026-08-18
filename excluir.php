<?php
include "conf.inc.php";

$id = isset($_GET['id']) ? $_GET['id'] : 0;

//abrir conexão
$conexao = new PDO (dsn, usuario, senha);

//montar o SQL
$SQL = "DELETE FROM aluno
        WHERE id = :id";

$consulta = $conexao->prepare($SQL);
$consulta->bindValue(':id', $id);

if($consulta->execute()){
    echo "Dados Excluidos <br>
    <a href='list.php'>Voltar para lista? </a>";
} else {
    echo "Erro ao excluir";
}
?>
