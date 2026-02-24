<?php
//Primeiro Crud, como criar ele --> colocando dados no Banco de dados

define ('usuario', 'root');
define ('senha',  '');
define ('host', 'localhost');
define ('porta', '3306');
define ('bd', 'escola');
define ('dsn', 'mysql:host=' . host . ';port=' . porta . ';dbname=' . bd);

//Pega as informações do formulário
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';

if ($nome != '') {

    //Conexão com o banco de dados
    $conexao = new PDO(dsn, usuario, senha);

    //Montar SQL
    $SQL = 'INSERT INTO aluno (nome, email) VALUES (:nome, :email)';

    //Preparar
    $comando = $conexao->prepare($SQL);

    //Bind
    $comando->bindValue(':nome', $nome);
    $comando->bindValue(':email', $email);

    //Executar
    if ($comando->execute()){
        echo 'Dados inseridos com sucesso';
    } else {
        echo 'Erro ao inserir dados no banco';
    }
}
?>
