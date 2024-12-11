<?php
session_start();
include 'config/conexao.php';

function carregarCarrinhoDoBanco($conexao, $usuario_id) {
    $stmtCarrinho = $conexao->prepare("SELECT id_jogo FROM carrinho WHERE usuario_id = ?");
    $stmtCarrinho->execute([$usuario_id]);
    return $stmtCarrinho->fetchAll(PDO::FETCH_COLUMN, 0);
}

if (isset($_SESSION['usuario_id'])) {
    $usuario_id = $_SESSION['usuario_id'];
    $_SESSION['carrinho'] = carregarCarrinhoDoBanco($conexao, $usuario_id);
}

$queryCategorias = $conexao->query("SELECT * FROM categorias ORDER BY nome_categoria ASC");
$categorias = $queryCategorias->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrossel de Lançamento de Jogos</title>
    <link rel="stylesheet" href="css/style_index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="JS\script_index.js"></script>
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

<h1>Jogos Lançados por Categoria</h1>


<div class="main-page">
<div class="nav-content-1"> 
    <nav class="nav-user-categoria">
        
    <dl>
        <dt> VISTOS RECENTEMENTE</dt>
            <dd> Red Dead Redemption 2</a></dd>
            <dd>Grand Theft Auto VI</dd>
            <dd>Dune: Awakening</dd>
            <dd>God of War Ragnarok</dd>

        <dt>RECOMENDADO</dt>
            <dd>Por amigos</dd>
            <dd>Por curadores</dd>
            <dd>Marcadores</dd>

        <dt>OFERTAS</dt>
            <dd>Mais vendidos</dd>
            <dd>Lançamentos</dd>
            <dd>Em breve</dd>
            <dd>Em promoção</dd>

        <dt>CATEGORIAS</dt>
            <dd>Ação</dd>
            <dd>Aventura</dd>
            <dd>Esportes</dd>
            <dd>Estratégia</dd>
            <dd>Luta</dd>
            <dd>OpenWorld</dd>
            <dd>Simulação</dd> 
    </dl>      
    </nav>
</div>

<div class="principal">
<?php foreach ($categorias as $categoria): ?>
    <div class="categoria">
        <div class="name-categoria">
        <h3><?php echo htmlspecialchars($categoria['nome_categoria']); ?></h3>
        </div>
        
        <?php
        $stmtJogos = $conexao->prepare("
            SELECT j.* FROM jogos j
            INNER JOIN jogo_categorias jc ON j.id = jc.id_jogo
            WHERE jc.id_categoria = ? 
            ORDER BY j.data_lancamento DESC
        ");
        $stmtJogos->execute([$categoria['id']]);
        $jogos = $stmtJogos->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <div class="carrossel-container">
            <button onclick="slideLeft(<?php echo $categoria['id']; ?>)">&#10094;</button>
            <div class="carrossel" id="carrossel-<?php echo $categoria['id']; ?>">
                <?php foreach ($jogos as $jogo): ?>
                    <div class="jogo" onclick="window.location.href='jogo.php?id=<?php echo $jogo['id']; ?>'">
                        <div class="game-info">
                            <?php
                            $imagemPath = "assets/imagens/jogos/" . htmlspecialchars($jogo['imagem']);
                            if (file_exists($imagemPath)) { ?>
                                <img src="<?php echo $imagemPath; ?>" alt="<?php echo htmlspecialchars($jogo['titulo']); ?>">
                            <?php } else { ?>
                                <p>Imagem não disponível</p>
                            <?php } ?>
                            
                            <h4><?php echo htmlspecialchars($jogo['titulo']); ?></h4>
                            <p>R$ <?php echo number_format($jogo['valor'], 2, ',', '.'); ?></p>
                            <p>Lançamento: <?php echo date('d/m/Y', strtotime($jogo['data_lancamento'])); ?></p>
                            <?php if (isset($_SESSION['carrinho']) && in_array($jogo['id'], $_SESSION['carrinho'])): ?>
                                <i class="fa fa-shopping-cart" style="color: green;" title="No carrinho"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <button onclick="slideRight(<?php echo $categoria['id']; ?>)">&#10095;</button>
        </div>
    </div>
<?php endforeach; ?>
</div>

<div class="nav-content">
    <nav class="nav-user-categoria">
        <dl>
            <dt> LISTA DE AMIGOS</dt>
                <dd><img src="assets/Imagens de perfil/1.jpeg" alt="" class="img-dd">ZokaAlan</dd>
                <dd><img src="assets/Imagens de perfil/2.jpeg" alt="" class="img-dd">Nelipe Feto</dd>
                <dd><img src="assets/Imagens de perfil/3.jpeg" alt="" class="img-dd">jrvitor</dd>
                <dd><img src="assets/Imagens de perfil/4.jpeg" alt="" class="img-dd">Ryan</dd>
                <dd><img src="assets/Imagens de perfil/5.jpeg" alt="" class="img-dd">ALcardoso</dd>
                <dd><img src="assets/Imagens de perfil/6.jpeg" alt="" class="img-dd">CaptainTIST</dd>
                <dd><img src="assets/Imagens de perfil/7.jpeg" alt="" class="img-dd">BestLight</dd>
                <dd><img src="assets/Imagens de perfil/8.jpeg" alt="" class="img-dd">ODRAP1RAKAHBTY </dd>
                <dd><img src="assets/Imagens de perfil/9.jpeg" alt="" class="img-dd">Jefferson Luiz Gomes Teixeira</dd>
                <dd><img src="assets/Imagens de perfil/10.jpeg" alt="" class="img-dd">Pereirä</dd>
                <dd><img src="assets/Imagens de perfil/11.jpeg" alt="" class="img-dd">Jalin_Habey</dd>
                <dd><img src="assets/Imagens de perfil/12.jpeg" alt="" class="img-dd">boneco em flávio</dd>
                <dd><img src="assets/Imagens de perfil/13.jpeg" alt="" class="img-dd">GustaZago </dd>
                <dd><img src="assets/Imagens de perfil/14.jpeg" alt="" class="img-dd">MaThEuS KaMiKaZ</dd>
                <dd><img src="assets/Imagens de perfil/1.jpeg" alt="" class="img-dd">bilôla </dd>
                <dd><img src="assets/Imagens de perfil/2.jpeg" alt="" class="img-dd">Black</dd>
                <dd><img src="assets/Imagens de perfil/3.jpeg" alt="" class="img-dd">LonelyWolf</dd>
                <dd><img src="assets/Imagens de perfil/4.jpeg" alt="" class="img-dd">Haruu</dd>
                <dd><img src="assets/Imagens de perfil/5.jpeg" alt="" class="img-dd">Raluca</dd>
                <dd><img src="assets/Imagens de perfil/6.jpeg" alt="" class="img-dd">Goularte</dd>
                <dd><img src="assets/Imagens de perfil/7.jpeg" alt="" class="img-dd">EduBRKS</dd>
                <dd><img src="assets/Imagens de perfil/8.jpeg" alt="" class="img-dd">EduGames</dd>
    </nav>
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

<style>
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
</html>