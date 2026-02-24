<?php
//Segundo Crud, melhorando o primeiro Crud

//para fazer esse funcionar, é necessario abrir o workbench, criar uma database escola e uma tabela aluno com todas as informações necessarias desse php (vai ficar escrito escola.aluno)

define ('usuario', 'root');
define ('senha',  '');
define ('host', 'localhost');
define ('porta', '3306');
define ('bd', 'escola');
define ('dsn', 'mysql:host=' . host . ';port=' . porta . ';dbname=' . bd);

//Pega as informações do formulário
$nome = $_POST['nome'] ?? '';
$email = $_POST['email'] ?? '';
$matricula = $_POST['matricula'] ?? '';
$senhaAluno = $_POST['senha'] ?? '';

if ($nome != '') {

    //Conexão com o banco de dados
    $conexao = new PDO(dsn, usuario, senha);

    //Montar SQL
    $SQL = 'INSERT INTO aluno (nome, email, matricula, senha) VALUES (:nome, :email, :matricula, :senha)';

    //Preparar
    $comando = $conexao->prepare($SQL);

    //Bind
    $comando->bindValue(':nome', $nome);
    $comando->bindValue(':email', $email);
    $comando->bindValue(':matricula', $matricula);
    $comando->bindValue(':senha', $senhaAluno);

    //Executar
    if ($comando->execute()){
        echo 'Dados inseridos com sucesso';
    } else {
        echo 'Erro ao inserir dados no banco';
    }
}
?>
