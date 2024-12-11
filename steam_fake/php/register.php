<?php
include 'config/conexao.php'; // Inclui o arquivo externo que estabelece conexão com o banco de dados.

// Verifica se o método de requisição é POST, indicando que o formulário foi enviado.
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = htmlspecialchars($_POST['usuario']); // Sanitiza o valor recebido do campo 'usuario', removendo caracteres especiais para evitar ataques XSS (Cross-Site Scripting).
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Cria um hash seguro da senha usando um algoritmo de criptografia recomendável, para armazenar a senha de forma segura no banco de dados.
    $nome = htmlspecialchars($_POST['nome']); // Sanitiza o valor do 'nome' por segurança.
    $genero = $_POST['genero']; // Obtém o valor do campo 'genero' sem sanitização extra (presumindo que seja seguro devido ao controle no front-end).
    $data_nascimento = $_POST['data_nascimento']; // Coleta a data de nascimento do usuário.
    $cpf = htmlspecialchars($_POST['cpf']); // Sanitiza o valor do CPF, removendo caracteres especiais.

    // Prepara uma instrução SQL para inserir um novo registro na tabela 'usuarios'.
    $stmt = $conexao->prepare("INSERT INTO usuarios (usuario, senha, nome, genero, data_nascimento, cpf) VALUES (?, ?, ?, ?, ?, ?)");
    
    // Executa a consulta passada com os dados do usuário. Se a execução for bem-sucedida, retorna true.
    if ($stmt->execute([$usuario, $senha, $nome, $genero, $data_nascimento, $cpf])) {
        echo "Registro criado com sucesso!"; // Mensagem de sucesso.
        // Redireciona o novo usuário para a página de login após o registro bem-sucedido.
        header("Location: login.php");
        exit(); // Termina a execução do script para garantir que nenhuma linha subsequente seja executada após o redirecionamento.
    } else {
        echo "Erro ao registrar o usuário."; // Mensagem de erro, caso a execução do registro falhe.
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define a codificação de caracteres para UTF-8, que suporta a maioria dos caracteres internacionais. -->
    <title>Registro</title> <!-- O título da página, que aparecerá na aba do navegador. -->
    <!--<link rel="stylesheet" href="css/style_register.css">-->

<style>

@font-face {
  font-family: "Steam";
  src: url("fonts/MotivaSansBold.ttf") format("truetype");
  font-weight: normal;
  font-style: normal;
}

body {
  background-color: #1b2838;
  color: #d9dce0;
  font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
  margin: 0;
  padding: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.register-container {
  background-color: #0d1b24;
  border-radius: 10px;
  padding: 20px;
  width: 320px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
  text-align: center;
}

h1 {
  color: white;
  font-family: "Steam";
  font-size: 24px;
  margin-bottom: 20px;
  padding: 10px 0;
}

label {
  color: white;
  font-family: "Franklin Gothic Medium", "Arial Narrow", Arial, sans-serif;
  display: block;
  margin-top: 10px;
  text-align: left;
}

input[type="text"],
input[type="password"],
input[type="date"],
select {
  border: 2px solid #ccc;
  border-radius: 10px;
  padding: 10px;
  font-size: 16px;
  width: calc(100% - 22px); /* Ajusta a largura para o padding */
  margin-top: 5px;
}

button {
  border: 2px solid #000000;
  border-radius: 15px;
  background-color: #1b2838;
  padding: 10px 20px;
  color: #d9dce0;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s;
  width: 100%;
  margin-top: 20px;
}

button:hover {
  background-color: #3b4d5a;
}


</style>

</head>
<body>
<div class="register-container">
    <h1>Registrar Usuário</h1> <!-- Cabeçalho para a seção de registro do usuário. -->
    <form method="post"> <!-- Início do formulário HTML que utiliza o método POST para enviar os dados ao servidor. -->
        <label for="usuario">Usuário:</label> <!-- Rótulo para o campo de entrada do usuário. -->
        <input type="text" name="usuario" required> <!-- Campo de texto obrigatório para o nome do usuário. -->

        <label for="senha">Senha:</label> <!-- Rótulo para o campo de senha. -->
        <input type="password" name="senha" required> <!-- Campo de senha obrigatório. -->

        <label for="nome">Nome:</label> <!-- Rótulo para o campo de nome. -->
        <input type="text" name="nome" required> <!-- Campo de texto obrigatório para o nome completo. -->

        <label for="genero">Gênero:</label> <!-- Rótulo para o menu suspenso de gênero. -->
        <select name="genero" required> <!-- Menu suspenso para seleção do gênero é obrigatório. -->
            <option value="M">Masculino</option> <!-- Opção de gênero Masculino. -->
            <option value="F">Feminino</option> <!-- Opção de gênero Feminino. -->
        </select>

        <label for="data_nascimento">Data de Nascimento:</label> <!-- Rótulo para o campo de data de nascimento. -->
        <input type="date" name="data_nascimento" required> <!-- Campo de data obrigatório para selecionar a data de nascimento. -->

        <label for="cpf">CPF:</label> <!-- Rótulo para o campo do CPF. -->
        <input type="text" name="cpf" maxlength="11" required> <!-- Campo de texto para CPF, obrigatório e limitado a 11 caracteres. -->

        <button type="submit">Registrar</button> <!-- Botão para submeter o formulário. -->
    </form>
</div>
</body>
</html>