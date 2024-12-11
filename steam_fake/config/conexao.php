<?php
// Configuração de conexão com o banco de dados
$host = 'localhost'; // Servidor de banco de dados
$db = 'lancamentos_jogos'; // Nome do banco de dados
$user = 'root'; // Usuário do banco de dados
$pass = 'a1b2c3'; // Senha do banco de dados

try {
    // Tenta estabelecer uma conexão com o banco de dados usando PDO
    $conexao = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Configura o modo de erro para exceção
} catch (PDOException $e) {
    echo "Erro: " . $e->getMessage(); // Exibe uma mensagem de erro se a conexão falhar
}
?>
