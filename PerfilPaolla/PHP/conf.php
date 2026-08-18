<?php
define('usuario', 'root');
define('senha', '');
define('host', 'localhost');
define('porta', '3306');
define('bd', 'Paolla'); // Alterado de 'escola' para 'Paolla'
define('dsn', 'mysql:host=' . host . ';port=' . porta . ';dbname=' . bd);

function getConexao() {
    try {
        $conexao = new PDO(dsn, usuario, senha);
        $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conexao;
    } catch (PDOException $e) {
        die('Erro na conexão com o banco de dados: ' . $e->getMessage());
    }
}
?>