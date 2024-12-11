<?php
session_start(); // Inicia a sessão para manter informações persistentes como as variáveis de sessão.
include 'config/conexao.php'; // Inclui o arquivo de conexão com o banco de dados.

// Verifica se o usuário está logado e se é administrador
if (!isset($_SESSION['usuario_id']) || $_SESSION['nivel_acesso'] !== 'admin') {
    // Redireciona para a página inicial se o usuário não estiver logado ou não for admin
    header('Location: index.php'); // Redireciona para a página inicial.
    exit(); // Garante que o script pare de executar após o redirecionamento.
}

// Obtendo as categorias do banco de dados
$sql = "SELECT id, nome_categoria FROM categorias"; // Consulta SQL para obter o id e nome das categorias.
$stmt = $conexao->prepare($sql); // Prepara a consulta SQL.
$stmt->execute(); // Executa a consulta preparada.
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna os resultados como um array associativo.
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Novo Jogo</title>
    <link rel="stylesheet" href="css/style_adicionar.css">
</head>
<body>

<header>
    <div class="header-container">
        <div class="logo">
            <img src="assets/steam_icon_invertido.png" alt=" Logo ">
        </div>
        <h2>FAKE_STEAM</h2>
    </div>
    <div>
        <nav>
            <a href="carrinho.php"><i class="fa fa-shopping-cart" style="font-size:24px"></i> Carrinho (<?php echo count($_SESSION['carrinho'] ?? []); ?>)</a>
            <a href="index.php">Home</a>
            <a href="about.php">Sobre</a>
            <a href="contact.php">Contato</a>
            <?php if (isset($_SESSION['nivel_acesso']) && $_SESSION['nivel_acesso'] === 'admin'): ?>
                <a href="adicionar.php">ADD</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

    <h1>Adicionar Novo Jogo</h1>
    <form action="salvar.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="titulo">Título:</label>
            <input type="text" name="titulo" id="titulo" required>
        </div>

        <div class="form-group">
            <label for="descricao">Descrição:</label>
            <textarea name="descricao" id="descricao" required></textarea>
        </div>
        
        <label for="capa_video">Capa de video:</label>
        <input type="file" name="capa_video" id="capa_video" accept="image/*" required style="display: none;">
        <button type="button" onclick="document.getElementById('imagem').click();">
            <img src="assets/imagens/icon_pag_add_jogo/add_icon.png" alt="Upload" style="width: 20px; height: 20px; margin-right: 5px;">
            Adicionar capa de video
        </button><br>

        

        <label for="imagem">Imagem:</label>
        <input type="file" name="imagem" id="imagem" accept="image/*" required style="display: none;">
        <button type="button" onclick="document.getElementById('imagem').click();">
            <img src="assets/imagens/icon_pag_add_jogo/add_icon.png" alt="Upload" style="width: 20px; height: 20px; margin-right: 5px;">
            Adicionar imagem
        </button><br>

        <label for="video">URL do Vídeo:</label>
        <input type="url" name="video" id="video" required> <!-- Campo para a URL do vídeo -->

        <div class="form-group">
            <label for="data_lancamento">Data de Lançamento:</label>
            <input type="date" name="data_lancamento" id="data_lancamento" required>
        </div>

        <div class="form-group">
            <label for="categorias">Categorias:</label>
            <select name="categorias[]" id="categorias" multiple required>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?= htmlspecialchars($categoria['id']); ?>"><?= htmlspecialchars($categoria['nome_categoria']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="valor">Valor:</label>
            <input type="number" name="valor" id="valor" step="0.01" required>
        </div>

        <button type="submit">Adicionar Jogo</button>
    </form>
</body>
</html>
