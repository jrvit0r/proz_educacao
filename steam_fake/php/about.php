<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/about.css" />
  <title>Sobre Steam_fake</title>
</head>

<body>
  <header>
    <div class="header-container">
        <div class="logo">
            <img src="assets/steam_icon_invertido.png" alt=" Logo ">
            <h2>FAKE_STEAM</h2>
        </div>
        
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

  <div class="container">
    <h1>Steam_fake</h1>
    <p>Sobre a Steam_fake</p>

    <p>
      A Steam_fake é uma empresa inovadora no cenário dos jogos digitais,
      dedicada a criar experiências imersivas e memoráveis para jogadores em
      todo o mundo. Fundada com o objetivo de transformar ideias criativas em
      jogos de alta qualidade, nossa equipe de desenvolvedores, designers e
      especialistas em tecnologia trabalha incansavelmente para desenvolver
      produtos únicos e envolventes que desafiam a imaginação e redefinem a
      diversão.
    </p>

    <p>
      Nossa missão é combinar tecnologia de ponta com design intuitivo e
      narrativas emocionantes, criando jogos que não apenas entretenham, mas
      também inspirem. Na Steam_fake, acreditamos no poder dos jogos para
      conectar pessoas, estimular a criatividade e proporcionar aventuras
      inesquecíveis. Nossos lançamentos abrangem diversos gêneros e
      plataformas, sempre com foco em atender às expectativas dos jogadores
      mais exigentes e em expandir os limites da tecnologia de jogos.
    </p>

    <p>
      Além de desenvolver nossos próprios títulos, oferecemos serviços de
      suporte e atualização contínuos, garantindo que nossos jogos evoluam com
      as demandas do mercado e as preferências de nossa comunidade de
      jogadores. Estamos comprometidos em promover um ambiente de jogo seguro
      e inclusivo, onde todos possam explorar, aprender e se divertir.
    </p>

    <p>
      Seja bem-vindo ao universo da Steam_fake, onde criatividade e tecnologia
      se encontram para criar o futuro dos jogos digitais. Convidamos você a
      descobrir nossos jogos e embarcar em aventuras que só começam quando
      você aperta o play.
    </p>
    <p>Visite a gente nas redes sociais!</p>
    <center>
      <a href="https://www.facebook.com/share/15YJ5CwHqG/">
        <img class="img_redes" src="assets/imagens/icon_redes_home/face-icon.png" />
      </a>
      <a href="https://www.instagram.com/zd03.7/profilecard/?igsh=MXE1ZGw1ZnFmMmVtcQ==">
        <img class="img_redes2" src="assets/imagens/icon_redes_home/icon_insta.png" />
      </a>
    </center>
  </div>
</body>
</html>