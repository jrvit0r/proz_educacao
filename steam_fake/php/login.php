<?php
session_start(); // Inicia uma nova sessão ou retoma uma sessão existente para gerenciamento de informações do usuário entre páginas.
include 'config/conexao.php'; // Inclui o arquivo PHP que estabelece uma conexão com o banco de dados.

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Verifica se o formulário foi enviado fazendo uma requisição POST.
    // Recuperação e sanitização dos dados do formulário
    $usuario = htmlspecialchars($_POST['usuario']); // Recebe e sanitiza o nome de usuário para prevenir XSS (Cross-Site Scripting).
    $senha = $_POST['senha']; // Captura a senha do formulario para verificação, não é necessário sanitização.

    // Prepara uma consulta para buscar o usuário no banco de dados.
    $stmt = $conexao->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    $stmt->execute([$usuario]); // Executa a consulta no banco de dados com o nome de usuário informado.
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC); // Recupera a primeira linha correspondente da consulta como um array associativo.

    // Verifica se o usuário foi encontrado e se a senha informada corresponde ao hash armazenado.
    if ($usuario && password_verify($senha, $usuario['senha'])) { 
        // Armazena dados importantes do usuário na sessão para uso posterior sem recorrer ao banco novamente.
        $_SESSION['usuario_id'] = $usuario['id']; // Armazena o ID do usuário na sessão.
        $_SESSION['usuario_nome'] = $usuario['usuario']; // Armazena o nome de usuário na sessão.
        $_SESSION['nivel_acesso'] = $usuario['nivel_acesso']; // Armazena o nível de acesso na sessão, útil para controle de permissões.

        // Redireciona o usuário para a página inicial ou dashboard após fazer login com sucesso.
        header("Location: index.php");
        exit(); // Encerra imediatamente o script para garantir que o redirecionamento ocorra.
    } else {
        // Exibe uma mensagem de erro se as credenciais estiverem incorretas.
        echo "Usuário ou senha incorretos.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8"> <!-- Define o conjunto de caracteres utilizado pelo documento para evitar problemas com caracteres especiais. -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Configura a visualização em dispositivos móveis para responsive design. -->
    <title>Lançamento de Jogos</title> <!-- Define o título da aba ou janela do navegador. -->
    <link rel="stylesheet" href="css/style_login.css"> <!-- Inclui a folha de estilos CSS para estilos visuais da página (layout, cores, etc.). -->
</head>
<body>

<header>
    <div class="header-container">
        <div class="logo">
            <img src="assets/steam_icon_invertido.png" alt="Logo"> <!-- Exibe o logo na parte superior da página. -->
        </div>
        <h2>FAKE_STEAM</h2> <!-- Nome da plataforma ou site exibido como cabeçalho. -->
    </div>
</header>

<main>
    <div class="login-container">
        <h1>Login</h1> <!-- Cabeçalho indicando a funcionalidade da seção de login. -->
        <form method="post"> <!-- Formulário para login que envia os dados via método POST ao servidor. -->
            <div class="input-group">
                <label for="usuario">Usuário:</label> <!-- Rótulo para o campo de entrada de texto do nome de usuário. -->
                <input type="text" name="usuario" required> <!-- Campo obrigatório para entrada do nome de usuário. -->
            </div>
            <div class="input-group">
                <label for="senha">Senha:</label> <!-- Rótulo para o campo de entrada de texto da senha. -->
                <input type="password" name="senha" required> <!-- Campo obrigatório para entrada da senha, escondendo-a na tela. -->
            </div>
            <button type="submit">Entrar</button> <!-- Botão que, ao ser clicado, submete o formulário. -->
            <h3><a href="register.php" style="color: white;">Caso não tenha login, clique aqui para criar.</a></h3> <!-- Link para a página de registro caso o usuário ainda não tenha uma conta. -->
        </form>
    </div>
</main>
</body>
</html>
</body>
</html>