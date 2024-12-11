<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contato - FAKE_STEAM</title>
    <link rel="stylesheet" href="css\contact.css" />
  </head>
  <body>
    <!-- Cabeçalho -->
    <header>
      <div class="header-container">
        <div class="logo">
          <img src="assets/steam_icon_invertido.png" alt="Logo FAKE_STEAM" />
          <h2>FAKE_STEAM</h2>
        </div>

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

    <!-- Conteúdo Principal -->
    <main>
      <section class="contact-section">
        <h1>Fale Conosco</h1>
        <p>
          Entre em contato conosco para tirar dúvidas, sugestões ou suporte
          técnico.
        </p>

        <!-- Formulário de Contato -->
        <form action="enviar_contato.php" method="POST" class="contact-form">
          <label for="nome">Nome:</label>
          <input type="text" id="nome" name="nome" required />

          <label for="email">E-mail:</label>
          <input type="email" id="email" name="email" required />

          <label for="assunto">Assunto:</label>
          <input type="text" id="assunto" name="assunto" required />

          <label for="mensagem">Mensagem:</label>
          <textarea id="mensagem" name="mensagem" rows="5" required></textarea>

          <button type="submit">Enviar Mensagem</button>
        </form>

        <!-- Informações de Contato -->
        <div class="contact-info">
          <h3>Outras formas de contato:</h3>
          <p>Email: suporte@fake_steam.com</p>
          <p>Telefone: (37) 9 4002-8922</p>
          <p>Endereço: Rua Quero café ,69 - Bob Marley,MG</p>
        </div>
      </section>
    </main>

  </body>
</html>
