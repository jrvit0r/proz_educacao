<?php
session_start();
include 'config/conexao.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: error.php?msg=ID do jogo não fornecido');
    exit();
}

$idJogo = (int)$_GET['id'];

$stmtJogo = $conexao->prepare("SELECT * FROM jogos WHERE id = ?");
$stmtJogo->execute([$idJogo]);
$jogo = $stmtJogo->fetch(PDO::FETCH_ASSOC);

if (!$jogo) {
    header('Location: error.php?msg=Jogo não encontrado');
    exit();
}

$stmtComentarios = $conexao->prepare("SELECT c.comentario, c.data_comentario, u.nome FROM comentarios c JOIN usuarios u ON c.usuario_id = u.id WHERE c.id_jogo = ? ORDER BY c.data_comentario DESC");
$stmtComentarios->execute([$idJogo]);
$comentarios = $stmtComentarios->fetchAll(PDO::FETCH_ASSOC);

$stmtLikes = $conexao->prepare("SELECT COUNT(*) AS total_likes FROM likes WHERE id_jogo = ? AND tipo_like = 1");
$stmtLikes->execute([$idJogo]);
$totalLikes = $stmtLikes->fetch(PDO::FETCH_ASSOC)['total_likes'];

$stmtDislikes = $conexao->prepare("SELECT COUNT(*) AS total_dislikes FROM likes WHERE id_jogo = ? AND tipo_like = 0");
$stmtDislikes->execute([$idJogo]);
$totalDislikes = $stmtDislikes->fetch(PDO::FETCH_ASSOC)['total_dislikes'];

function youtubeEmbedUrl($url) {
    preg_match('/(youtu\.be\/|youtube\.com\/(watch\?(.*&)?v=|(embed|v)\/))([^\?&"\'<> #]+)/', $url, $matches);
    return 'https://www.youtube.com/embed/' . $matches[5];
}

$videoUrl = youtubeEmbedUrl($jogo['video']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($jogo['titulo']); ?> - Detalhes</title>
   <!-- <link rel="stylesheet" href="css/style_jogo.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="JS/script_jogo.js"></script>
</head>
<body>

<header>
    <div class="header-container">
        <div class="logo">
            <img src="assets/steam_icon_invertido.png" alt=" Logo ">
        </div>
        <h2 class="esse-filho-da-puta-nao-fica-branco">FAKE_STEAM</h2>
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
<div class="titulo-jogo">
<h1 class="titulo_jogo"><?php echo htmlspecialchars($jogo['titulo']); ?></h1>
</div>


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
}

/* Estilos do cabeçalho */
header {
  background-color: #0d1b24;
  border-bottom: 1px solid #3b4d5a;
  padding: 10px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.header-container {
  display: flex;
  align-items: center; /* Alinha verticalmente */
  justify-content: space-between; /* Espaça os itens do header */
}

.logo img {
  max-height: 50px; /* Ajuste conforme necessário */
}

h2 {
  font-family: "Steam";
  color: #d9dce0;
  font-size: 28px;
  margin: 0;
}

.esse-filho-da-puta-nao-fica-branco{
  color: #d9dce0;
}

header nav {
  display: flex;
  gap: 20px;
}

header a {
  color: #d9dce0;
  text-decoration: none;
  font-size: 20px;
  transition: color 0.3s;
}

header a:hover {
  color: #ff4d4d;
}

/* Título da página */
h1 {
  text-align: center;
  color: white;
  font-size: 32px;
  padding-bottom: 10px;
  border-bottom: 2px solid beige;
}

/* Seção de Jogo */
.jogo-detalhes {
  background-color: #0d1b24;
  border: 2px solid #3b4d5a;
  border-radius: 10px;
  max-width: 900px;
  margin: 50px auto;
  padding: 30px;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.5);
  text-align: center;
}

.jogo-detalhes img {
  max-width: 100%;
  border-radius: 10px;
  box-shadow: 0 4px 10px rgba(0, 0, 0, 0.4);
  margin-bottom: 20px;
}

.jogo-detalhes p {
  font-size: 18px;
  line-height: 1.6;
  color: #d9dce0;
}

.jogo-detalhes p:last-child {
  font-size: 20px;
  font-weight: bold;
}

/* Seção de Vídeos */
.video-container {
  position: relative;
  width: 100%;
  max-width: 600px;
  height: 340px;
  margin: 20px auto;
  cursor: pointer;
}

.video-container img {
  width: 100%;
  border-radius: 8px;
}

.video-container iframe {
  display: none;
  width: 100%;
  height: 100%;
  border-radius: 8px;
}

.like-dislike-container {
  display: flex;
  justify-content: center;
  gap: 20px;
  margin-top: 20px;
}

.button-like-dislike {
  display: flex;
  align-items: center;
  gap: 8px;
}

.like-button,
.dislike-button {
  background: none;
  border: none;
  cursor: pointer;
  font-size: 24px;
  color: #d9dce0;
  transition: color 0.3s;
}

.like-button:hover,
.dislike-button:hover {
  color: #ff4d4d;
}

/* Seção de Comentários */
.comentarios {
  display: none;
  background-color: #1b2838;
  border: 1px solid #3b4d5a;
  border-radius: 5px;
  padding: 15px;
  margin-top: 20px;
  max-height: 300px;
  overflow-y: auto;
  box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.4);
}

.comentarios h3 {
  color: #ff4d4d;
  margin-bottom: 10px;
  font-size: 20px;
}

.cart-icon{ 
  display: flex;
  width: 100%;
  height: 100%;
  border-radius: 328px;
}

.comentario {
  background-color: #0d1b24;
  padding: 10px;
  border-radius: 5px;
  margin-bottom: 10px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
  position: absolute;
}

.comentario strong {
  color: #ff4d4d;
  font-weight: bold;
}

.comentario p em {
  font-size: 12px;
  color: #8a99a6;
}

/* Botão Mostrar Comentários */
.show-comments-btn {
  background-color: #ff4d4d;
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  padding: 10px 20px;
  transition: background-color 0.3s;
}

.show-comments-btn:hover {
  background-color: #d43636;
}

/* Input e Área de Texto */
input[type="text"],
textarea {
  width: 100%;
  padding: 10px;
  margin-top: 10px;
  border: 1px solid #3b4d5a;
  border-radius: 5px;
  background-color: #2b3948;
  color: #d9dce0;
  font-size: 16px;
}

textarea {
  height: 80px;
  resize: none;
}

.btn_enviar {
  width: 40px; 
  height: 40px;
  margin-right: 5px;
  background-color: #1653a1;
  border-radius: 50%; /* Deixa o botão redondo */
  border: none; /* Remove a borda padrão */
  display: flex; /* Alinha a imagem dentro do botão */
  justify-content: center; 
  align-items: center;
  box-shadow: 0px 4px 8px rgba(10, 65, 243, 0.774); /* Adiciona uma sombra suave */
  cursor: pointer; /* Muda o cursor para indicar que é clicável */
  transition: all 0.3s ease; /* Transição suave ao passar o mouse */
}

.btn_enviar:hover {
  background-color: #1f8ae2; /* Muda a cor quando o mouse passa por cima */
  transform: scale(1.1); /* Aumenta levemente o tamanho do botão */
  box-shadow: 0px 6px 12px rgba(10, 75, 173, 0.3); /* Aumenta a sombra no hover */
}

.btn_enviar img {
  width: 24px; /* Ajusta o tamanho da imagem */
  height: 24px;
}

button {
  background: none; /* Remove o fundo do botão para que a imagem fique visível */
  border: none; /* Remove a borda do botão */
  padding: 0; /* Remove o padding padrão */
  cursor: pointer; /* Garante que o cursor seja um ponteiro */
}

.cart-icon {
    display: flex;
    justify-content: center;   /* Centraliza o conteúdo horizontalmente */
    align-items: center;       /* Centraliza o conteúdo verticalmente */
    margin-top: 20px;          /* Espaço superior opcional */
}

.cart-button {
    background-color: #FF6B6B; /* Cor de fundo vibrante */
    color: white;            /* Cor do texto */
    border: none;              /* Remove bordas */
    padding: 10px 20px;        /* Espaçamento interno */
    border-radius: 5px;        /* Bordas arredondadas */
    font-size: 16px;           /* Tamanho da fonte */
    font-weight: bold;         /* Negrito */
    display: flex;             /* Alinha ícone e texto */
    align-items: center;       /* Centraliza ícone e texto */
    gap: 8px;                  /* Espaço entre ícone e texto */
    cursor: pointer;           /* Cursor de mão */
    transition: background-color 0.3s ease; /* Efeito de hover */
}

.cart-button:hover {
    background-color: #FF5252; /* Cor de hover */
}

.cart-button i {
    font-size: 20px;           /* Tamanho do ícone */
}

    .jogo-detalhes{
        display: flex;
        flex-direction: column;
    }

    .jogo-imgvideo{
        display: flex;
        flex-direction: row;
    }

    .jogo-img{
        display:flex;
        width: 250px;
        height: 360px;
    }

    .buttons-game{
        display:flex;
        flex-direction:column;
        justify-content: center;
        align-items: center;
        
    }

    .show-comments-btn{
        display:flex;
        justify-content:center;
        align-items:center;
        margin-left:500px;
        margin-right:500px;

    }

    nav{
        margin-right:40px;

    }

    .titulo-jogo h1{
        color:#d9dce0;
    }
    

    #footer{
    --gpSystemLightestGrey: #DCDEDF;
    --gpSystemLighterGrey: #B8BCBF;
    --gpSystemLightGrey: #8B929A;
    --gpSystemGrey: #67707B;
    --gpSystemDarkGrey: #3D4450;
    --gpSystemDarkerGrey: #23262E;
    --gpSystemDarkestGrey: #0E141B;
    --gpStoreLightestGrey: #CCD8E3;
    --gpStoreLighterGrey: #A7BACC;
    --gpStoreLightGrey: #7C8EA3;
    --gpStoreGrey: #4e697d;
    --gpStoreDarkGrey: #2A475E;
    --gpStoreDarkerGrey: #1B2838;
    --gpStoreDarkestGrey: #000F18;
    --gpGradient-StoreBackground: linear-gradient(180deg, var(--gpStoreDarkGrey) 0%, var(--gpStoreDarkerGrey) 80%);
    --gpGradient-LibraryBackground: radial-gradient(farthest-corner at 40px 40px,#3D4450 0%, #23262E 80%);
    --gpColor-Blue: #1A9FFF;
    --gpColor-BlueHi: #00BBFF;
    --gpColor-Green: #5ba32b;
    --gpColor-GreenHi: #59BF40;
    --gpColor-Orange: #E35E1C;
    --gpColor-Red: #D94126;
    --gpColor-RedHi: #EE563B;
    --gpColor-DustyBlue: #417a9b;
    --gpColor-LightBlue: #B3DFFF;
    --gpColor-Yellow: #FFC82C;
    --gpColor-ChalkyBlue: #66C0F4;
    --gpBackground-LightSofter: #6998bb24;
    --gpBackground-LightSoft: #3b5a7280;
    --gpBackground-LightMedium: #678BA670;
    --gpBackground-LightHard: #93B8D480;
    --gpBackground-LightHarder: #aacce6a6;
    --gpBackground-DarkSofter: #0e141b33;
    --gpBackground-DarkSoft: #0e141b66;
    --gpBackground-DarkMedium: #0e141b99;
    --gpBackground-DarkHard: #0e141bcc;
    --gpBackground-Neutral-LightSofter: rgba(235, 246, 255, 0.10);
    --gpBackground-Neutral-LightSoft: rgba(235, 246, 255, 0.20);
    --gpBackground-Neutral-LightMedium: rgba(235, 246, 255, 0.30);
    --gpBackground-Neutral-LightHard: rgba(235, 246, 255, 0.40);
    --gpBackground-Neutral-LightHarder: rgba(235, 246, 255, 0.50);
    --gpCorner-Small: 1px;
    --gpCorner-Medium: 2px;
    --gpCorner-Large: 3px;
    --gpSpace-Gutter: 24px;
    --gpSpace-Gap: 12px;
    --gpNavWidth: 240px;
    --gpPaymentsNavWidth: 340px;
    --gpDselectWidth: 340px;
    --gpSidePanelWidth: 340px;
    --gpGiftingPanelWidth: 280px;
    --gpCommunityRightPanelWidth: 320px;
    --gpVerticalResponsivePadding-Small: calc( (100vw - 854px) / 60 );
    --gpVerticalResponsivePadding-Medium: calc( (100vw - 854px) / 20 );
    --gpVerticalResponsivePadding-Large: calc( (100vw - 854px) / 12 );
    --screen-width: 100vw;
    --gpWidth-6colcap: calc((var(--screen-width) - (5 * var(--gpSpace-Gap)) - (2 * var(--gpSpace-Gutter))) / 6);
    --gpWidth-5colcap: calc((var(--screen-width) - (4 * var(--gpSpace-Gap)) - (2 * var(--gpSpace-Gutter))) / 5);
    --gpWidth-4colcap: calc((var(--screen-width) - (3 * var(--gpSpace-Gap)) - (2 * var(--gpSpace-Gutter))) / 4);
    --gpWidth-3colcap: calc((var(--screen-width) - (2 * var(--gpSpace-Gap)) - (2 * var(--gpSpace-Gutter))) / 3);
    --gpWidth-2colcap: calc((var(--screen-width) - (1 * var(--gpSpace-Gap)) - (2 * var(--gpSpace-Gutter))) / 2);
    --gpWidth-1colcap: calc((var(--screen-width) - (2 * var(--gpSpace-Gutter))));
    --gpStoreMenuHeight: 58px;
    --gpShadow-Small: 0px 2px 2px 0px #0000003D;
    --gpShadow-Medium: 0px 3px 6px 0px #0000003D;
    --gpShadow-Large: 0px 12px 16px 0px #0000003D;
    --gpShadow-XLarge: 0px 24px 32px 0px #0000003D;
    --gpText-HeadingLarge: normal 700 26px/1.4 "Motiva Sans", Arial, Sans-serif;
    --gpText-HeadingMedium: normal 700 22px/1.4 "Motiva Sans", Arial, Sans-serif;
    --gpText-HeadingSmall: normal 700 18px/1.4 "Motiva Sans", Arial, Sans-serif;
    --gpText-BodyLarge: normal 400 16px/1.4 "Motiva Sans", Arial, Sans-serif;
    --gpText-BodyMedium: normal 400 14px/1.4 "Motiva Sans", Arial, Sans-serif;
    --gpText-BodySmall: normal 400 12px/1.4 "Motiva Sans", Arial, Sans-serif;
    --indent-level: 0;
    --field-negative-horizontal-margin: 0px;
    --field-row-children-spacing: 0px;
    color: #c6d4df;
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    left: 0;
    right: 0;
    padding: 16px 0 60px 0;
    margin: 0;
    -webkit-text-size-adjust: none;
    background: #171a21;
    position: relative;
    top: auto;
    bottom: auto;
      
    }

    .footer_content{
        text-decoration: none;
        color:white;


    --gpSystemLightestGrey: #DCDEDF;
    --gpSystemLighterGrey: #B8BCBF;
    --gpSystemLightGrey: #8B929A;
    --gpSystemGrey: #67707B;
    --gpSystemDarkGrey: #3D4450;
    --gpSystemDarkerGrey: #23262E;
    --gpSystemDarkestGrey: #0E141B;
    --gpStoreLightestGrey: #CCD8E3;
    --gpStoreLighterGrey: #A7BACC;
    --gpStoreLightGrey: #7C8EA3;
    --gpStoreGrey: #4e697d;
    --gpStoreDarkGrey: #2A475E;
    --gpStoreDarkerGrey: #1B2838;
    --gpStoreDarkestGrey: #000F18;
    --gpGradient-StoreBackground: linear-gradient(180deg, var(--gpStoreDarkGrey) 0%, var(--gpStoreDarkerGrey) 80%);
    --gpGradient-LibraryBackground: radial-gradient(farthest-corner at 40px 40px,#3D4450 0%, #23262E 80%);
    --gpColor-Blue: #1A9FFF;
    --gpColor-BlueHi: #00BBFF;
    --gpColor-Green: #5ba32b;
    --gpColor-GreenHi: #59BF40;
    --gpColor-Orange: #E35E1C;
    --gpColor-Red: #D94126;
    --gpColor-RedHi: #EE563B;
    --gpColor-DustyBlue: #417a9b;
    --gpColor-LightBlue: #B3DFFF;
    --gpColor-Yellow: #FFC82C;
    --gpColor-ChalkyBlue: #66C0F4;
    --gpBackground-LightSofter: #6998bb24;
    --gpBackground-LightSoft: #3b5a7280;
    --gpBackground-LightMedium: #678BA670;
    --gpBackground-LightHard: #93B8D480;
    --gpBackground-LightHarder: #aacce6a6;
    --gpBackground-DarkSofter: #0e141b33;
    --gpBackground-DarkSoft: #0e141b66;
    --gpBackground-DarkMedium: #0e141b99;
    --gpBackground-DarkHard: #0e141bcc;
    --gpBackground-Neutral-LightSofter: rgba(235, 246, 255, 0.10);
    --gpBackground-Neutral-LightSoft: rgba(235, 246, 255, 0.20);
    --gpBackground-Neutral-LightMedium: rgba(235, 246, 255, 0.30);
    --gpBackground-Neutral-LightHard: rgba(235, 246, 255, 0.40);
    --gpBackground-Neutral-LightHarder: rgba(235, 246, 255, 0.50);
    --gpCorner-Small: 1px;
    --gpCorner-Medium: 2px;
    --gpCorner-Large: 3px;
    --gpSpace-Gutter: 24px;
    --gpSpace-Gap: 12px;
    --gpNavWidth: 240px;
    --gpPaymentsNavWidth: 340px;
    --gpDselectWidth: 340px;
    --gpSidePanelWidth: 340px;
    --gpGiftingPanelWidth: 280px;
    --gpCommunityRightPanelWidth: 320px;
    --gpVerticalResponsivePadding-Small: calc( (100vw - 854px) / 60 );
    --gpVerticalResponsivePadding-Medium: calc( (100vw - 854px) / 20 );
    --gpVerticalResponsivePadding-Large: calc( (100vw - 854px) / 12 );
    --screen-width: 100vw;
    --gpWidth-6colcap: calc((var(--screen-width) - (5 * var(--gpSpace-Gap)) - (2 * var(--gpSpace-Gutter))) / 6);
    --gpWidth-5colcap: calc((var(--screen-width) - (4 * var(--gpSpace-Gap)) - (2 * var(--gpSpace-Gutter))) / 5);
    --gpWidth-4colcap: calc((var(--screen-width) - (3 * var(--gpSpace-Gap)) - (2 * var(--gpSpace-Gutter))) / 4);
    --gpWidth-3colcap: calc((var(--screen-width) - (2 * var(--gpSpace-Gap)) - (2 * var(--gpSpace-Gutter))) / 3);
    --gpWidth-2colcap: calc((var(--screen-width) - (1 * var(--gpSpace-Gap)) - (2 * var(--gpSpace-Gutter))) / 2);
    --gpWidth-1colcap: calc((var(--screen-width) - (2 * var(--gpSpace-Gutter))));
    --gpStoreMenuHeight: 58px;
    --gpShadow-Small: 0px 2px 2px 0px #0000003D;
    --gpShadow-Medium: 0px 3px 6px 0px #0000003D;
    --gpShadow-Large: 0px 12px 16px 0px #0000003D;
    --gpShadow-XLarge: 0px 24px 32px 0px #0000003D;
    --gpText-HeadingLarge: normal 700 26px/1.4 "Motiva Sans", Arial, Sans-serif;
    --gpText-HeadingMedium: normal 700 22px/1.4 "Motiva Sans", Arial, Sans-serif;
    --gpText-HeadingSmall: normal 700 18px/1.4 "Motiva Sans", Arial, Sans-serif;
    --gpText-BodyLarge: normal 400 16px/1.4 "Motiva Sans", Arial, Sans-serif;
    --gpText-BodyMedium: normal 400 14px/1.4 "Motiva Sans", Arial, Sans-serif;
    --gpText-BodySmall: normal 400 12px/1.4 "Motiva Sans", Arial, Sans-serif;
    --indent-level: 0;
    --field-negative-horizontal-margin: 0px;
    --field-row-children-spacing: 0px;
    color: #c6d4df;
    font-size: 12px;
    font-family: Arial, Helvetica, sans-serif;
    -webkit-text-size-adjust: none;
    padding: 0;
    width: 940px;
    margin: 0px auto;
    padding-top: 16px;
    }
    .footer_content a{
        text-decoration: none;
        color:white;

    }

</style>

<center><div class="jogo-detalhes">
    <div class="jogo-imgvideo">
        <div class="video-container" onclick="playVideo()">
        <img src="assets/imagens/jogos/<?php echo htmlspecialchars($jogo['capa_video']); ?>" alt="Video Cover">
        <iframe src="<?php echo $videoUrl; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>

    <?php
        $imagemPath = "assets/imagens/jogos/" . htmlspecialchars($jogo['imagem']);
        if (file_exists($imagemPath)) { ?>
            <img class="jogo-img" src="<?php echo $imagemPath; ?>" alt="<?php echo htmlspecialchars($jogo['titulo']); ?>">
        <?php } else { ?>
            <p>Imagem não disponível</p>
    <?php } ?>
    </div>

    <p><?php echo htmlspecialchars($jogo['descricao']); ?></p>
    <p>R$ <?php echo number_format($jogo['valor'], 2, ',', '.'); ?></p>
    <p>Lançamento: <?php echo date('d/m/Y', strtotime($jogo['data_lancamento'])); ?></p></center>

    <div class="buttons-game">
        <div class="cart-icon">
            <form id="add-to-cart-form" method="POST">
                <input type="hidden" name="id_jogo" value="<?php echo $jogo['id']; ?>">
                <button type="button" onclick="addToCart(<?php echo $jogo['id']; ?>)"><i class="fa fa-cart-plus" aria-hidden="true" style="font-size:20px"></button></i>
            </form>
        </div>

        <div class="like-dislike-container">
            <div class="button-like-dislike">
                <i id="like-button-<?php echo $jogo['id']; ?>" onclick="likeDislikeGame(<?php echo $jogo['id']; ?>, 'like')">
                    <i class="fa fa-thumbs-up" aria-hidden="true"></i>
                </i>
                <span id="like-count-<?php echo $jogo['id']; ?>" class="like-count"><?php echo $totalLikes; ?></span>
            </div>
            
            <div class="button-like-dislike">
                <i id="dislike-button-<?php echo $jogo['id']; ?>" onclick="likeDislikeGame(<?php echo $jogo['id']; ?>, 'dislike')">
                    <i class="fa fa-thumbs-down" aria-hidden="true"></i>
                </i>
                <span id="dislike-count-<?php echo $jogo['id']; ?>" class="dislike-count"><?php echo $totalDislikes; ?></span>
            </div>
        </div>

                <button class="show-comments-btn" onclick="toggleComments(<?php echo $jogo['id']; ?>)" id="toggle-comments-<?php echo $jogo['id']; ?>">Mostrar Comentários</button>

    <div class="comentarios" id="comments-<?php echo $jogo['id']; ?>" style="display:none;">
    <textarea id="comentario-<?php echo $jogo['id']; ?>" placeholder="Deixe um comentário"></textarea>
    <button id="submit-comment-<?php echo $jogo['id']; ?>" onclick="submitComment(<?php echo $jogo['id']; ?>)">
        <img src="assets\imagens\icon_jogo_send\send.png" class="btn_enviar" style=" width: 24px; height: 24px;">
    </button>

    <h3>Comentários</h3>
    <div id="comentarios-<?php echo $jogo['id']; ?>">
        <?php
        if (count($comentarios) > 0) {
            foreach ($comentarios as $comentario) {
                echo "<div class='comentario'>";
                echo "<strong>" . htmlspecialchars($comentario['nome']) . ":</strong> ";
                echo htmlspecialchars($comentario['comentario']);
                echo "<p><em>" . date('d/m/Y H:i', strtotime($comentario['data_comentario'])) . "</em></p>";
                echo "</div>";
            }
        } else {
            echo "<p>Sem comentários ainda.</p>";
        }
        ?>
    </div>
</div>

    </div>
</div>

<div id="footer" role="contentinfo" class="small_footer">
<div class="footer_content">

    <div class="rule"></div>
				<div id="footer_logo_steam"><img src="https://store.cloudflare.steamstatic.com/public/images/v6/logo_steam_footer.png" alt="Valve Software" border="0"></div>

    <div id="footer_logo"><a href="http://www.valvesoftware.com" target="_blank" rel=""><img src="https://store.cloudflare.steamstatic.com/public/images/footerLogo_valve_new.png" alt="Valve Software" border="0"></a></div>
    <div id="footer_text" data-panel="{&quot;flow-children&quot;:&quot;row&quot;}">
        <div>© 2024 Valve Corporation. Todos os direitos reservados. Todas as marcas são propriedade dos seus respectivos donos nos EUA e em outros países.</div>
        <div>IVA incluso em todos os preços onde aplicável.&nbsp;&nbsp;

            <a href="https://store.steampowered.com/privacy_agreement/?snr=1_44_44_" target="_blank" rel="">Política de Privacidade</a>
            &nbsp; <span aria-hidden="true">|</span> &nbsp;
            <a href="https://store.steampowered.com/legal/?snr=1_44_44_" target="_blank" rel="">Termos Legais</a>
            &nbsp; <span aria-hidden="true">|</span> &nbsp;
            <a href="https://store.steampowered.com/subscriber_agreement/?snr=1_44_44_" target="_blank" rel="">Acordo de Assinatura do Steam</a>
            &nbsp; <span aria-hidden="true">|</span> &nbsp;
            <a href="https://store.steampowered.com/steam_refunds/?snr=1_44_44_" target="_blank" rel="">Reembolsos</a>
            &nbsp; <span aria-hidden="true">|</span> &nbsp;
            <a href="https://store.steampowered.com/account/cookiepreferences/?snr=1_44_44_" target="_blank" rel="">Cookies</a>

        </div>
					<div class="responsive_optin_link">
				<div class="btn_medium btnv6_grey_black" onclick="Responsive_RequestMobileView()">
					<span>Ver versão para dispositivos móveis</span>
				</div>
			</div>
		
    </div>



    <div style="clear: left;"></div>
	<br>

    <div class="rule"></div>

    <div class="valve_links" data-panel="{&quot;flow-children&quot;:&quot;row&quot;}">
        <a href="http://www.valvesoftware.com/about" target="_blank" rel="">Sobre a Valve</a>
        &nbsp; <span aria-hidden="true">|</span> &nbsp;<a href="http://www.valvesoftware.com" target="_blank" rel="">Empregos</a>
        &nbsp; <span aria-hidden="true">|</span> &nbsp;<a href="http://www.steampowered.com/steamworks/" target="_blank" rel="">Steamworks</a>
        &nbsp; <span aria-hidden="true">|</span> &nbsp;<a href="https://partner.steamgames.com/steamdirect" target="_blank" rel="">Distribuição no Steam</a>
        &nbsp; <span aria-hidden="true">|</span> &nbsp;<a href="https://help.steampowered.com/pt-br/?snr=1_44_44_">Suporte</a>
                        		&nbsp; <span aria-hidden="true">|</span> &nbsp;<a href="https://store.steampowered.com/digitalgiftcards/?snr=1_44_44_" target="_blank" rel="">Vales-presente</a>
		&nbsp; <span aria-hidden="true">|</span> &nbsp;<a href="https://steamcommunity.com/linkfilter/?u=http%3A%2F%2Fwww.facebook.com%2FSteam" target="_blank" rel=" noopener"><img src="https://store.cloudflare.steamstatic.com/public/images/ico/ico_facebook.png" alt="Facebook"> Steam</a>
		&nbsp; <span aria-hidden="true">|</span> &nbsp;<a href="http://twitter.com/steam" target="_blank" rel=""><img src="https://store.cloudflare.steamstatic.com/public/images/ico/ico_twitter.png" alt="X"> @steam</a>
            </div>
				<div class="extra_space"></div>

                
	
</div>
</div>
</body>
</html>