<?php
session_start();
include 'config/conexao.php';

$usuario_id = $_SESSION['usuario_id'] ?? null;

if (!$usuario_id) {
    echo json_encode(['error' => 'Usuário não está logado.']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Verifica se o método da requisição é POST

    // Verifica se a requisição é para adicionar um novo jogo
    if (isset($_POST['titulo']) && isset($_POST['descricao'])) {
        // Obtendo os dados do formulário
        $titulo = htmlspecialchars(trim($_POST['titulo'])); // Sanitiza e remove espaços em branco do título
        $descricao = htmlspecialchars(trim($_POST['descricao'])); // Sanitiza e remove espaços em branco da descrição
        $data_lancamento = $_POST['data_lancamento']; // Obtém a data de lançamento
        $valor = (float) $_POST['valor']; // Converte o valor para float
        $categorias = $_POST['categorias']; // Obtém as categorias selecionadas

        // Lidar com upload de imagem
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == UPLOAD_ERR_OK) { // Verifica se a imagem foi enviada sem erros
            $imagem_nome = $_FILES['imagem']['name']; // Obtém o nome do arquivo de imagem
            $imagem_temp = $_FILES['imagem']['tmp_name']; // Obtém o caminho temporário do arquivo de imagem no servidor
            $imagem_caminho = 'assets/imagens/jogos/' . $imagem_nome; // Define o caminho final onde a imagem será salva
            
            move_uploaded_file($imagem_temp, $imagem_caminho); // Move a imagem para o diretório especificado
        }

        // Lidar com a imagem de capa do vídeo e vídeo
        $capa_video_nome = null; // Inicializa a variável para a capa do vídeo
        $video_nome = null; // Inicializa a variável para o vídeo
        
        if (isset($_FILES['capa_video']) && $_FILES['capa_video']['error'] == UPLOAD_ERR_OK) { // Verifica se a capa do vídeo foi enviada sem erros
            $capa_video_nome = $_FILES['capa_video']['name']; // Obtém o nome do arquivo da capa do vídeo
            $capa_video_temp = $_FILES['capa_video']['tmp_name']; // Obtém o caminho temporário do arquivo da capa do vídeo
            $capa_video_caminho = 'assets/imagens/jogos/' . $capa_video_nome; // Define o caminho final da capa do vídeo
            move_uploaded_file($capa_video_temp, $capa_video_caminho); // Move a capa do vídeo para o diretório especificado
        }
        
        if (isset($_POST['video'])) { // Verifica se o campo de vídeo está definido (assumindo ser uma URL)
            $video_nome = htmlspecialchars(trim($_POST['video'])); // Sanitiza e remove espaços em branco
        }

        try {
            // Insere um novo jogo no banco de dados
            $stmt = $conexao->prepare("INSERT INTO jogos (titulo, descricao, imagem, capa_video, video, data_lancamento, valor, usuario_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$titulo, $descricao, $imagem_nome, $capa_video_nome, $video_nome, $data_lancamento, $valor, $usuario_id]);

            $jogo_id = $conexao->lastInsertId(); // Obtém o ID do novo jogo inserido
            
            // Inserir categorias do jogo
            if (!empty($categorias)) { // Verifica se as categorias não estão vazias
                foreach ($categorias as $categoria_id) { // Itera sobre as categorias selecionadas
                    $stmtCategoria = $conexao->prepare("INSERT INTO jogo_categorias (id_jogo, id_categoria) VALUES (?, ?)");
                    $stmtCategoria->execute([$jogo_id, $categoria_id]); // Insere cada categoria associada ao jogo
                }
            }

             // Redireciona para a página index.php após o sucesso
             header("Location: index.php");
             exit();
        } catch (Exception $e) {
            echo json_encode(['error' => 'Erro ao adicionar o jogo.']); // Retorna erro como JSON caso algo falhe
        }
        exit();
    }


   if (isset($_POST['like_jogo'], $_POST['tipo_like'])) {
        $id_jogo = $_POST['like_jogo'];
        $tipo_like = (int)$_POST['tipo_like'];

        try {
            $stmt = $conexao->prepare("SELECT * FROM likes WHERE id_jogo = ? AND usuario_id = ?");
            $stmt->execute([$id_jogo, $usuario_id]);
            $likeExistente = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($likeExistente) {
                $stmt = $conexao->prepare("UPDATE likes SET tipo_like = ? WHERE id_jogo = ? AND usuario_id = ?");
                $stmt->execute([$tipo_like, $id_jogo, $usuario_id]);
            } else {
                $stmt = $conexao->prepare("INSERT INTO likes (id_jogo, usuario_id, tipo_like) VALUES (?, ?, ?)");
                $stmt->execute([$id_jogo, $usuario_id, $tipo_like]);
            }

            $stmtLike = $conexao->prepare("SELECT COUNT(*) AS total_likes FROM likes WHERE id_jogo = ? AND tipo_like = 1");
            $stmtDislike = $conexao->prepare("SELECT COUNT(*) AS total_dislikes FROM likes WHERE id_jogo = ? AND tipo_like = 0");
            $stmtLike->execute([$id_jogo]);
            $stmtDislike->execute([$id_jogo]);

            $likes = $stmtLike->fetch(PDO::FETCH_ASSOC);
            $dislikes = $stmtDislike->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'total_likes' => $likes['total_likes'],
                'total_dislikes' => $dislikes['total_dislikes'],
                'success' => true
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Erro ao processar o like. Tente novamente.']);
        }
        exit();
    }

    elseif (isset($_POST['id_jogo'], $_POST['comentario'])) {
        $id_jogo = $_POST['id_jogo'];
        $comentario = htmlspecialchars(trim($_POST['comentario']));

        if (empty($comentario)) {
            echo json_encode(['error' => 'Comentário não pode ser vazio.']);
            exit();
        }

        try {
            $stmt = $conexao->prepare("INSERT INTO comentarios (id_jogo, usuario_id, comentario, data_comentario) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$id_jogo, $usuario_id, $comentario]);

            $stmtUsuario = $conexao->prepare("SELECT nome FROM usuarios WHERE id = ?");
            $stmtUsuario->execute([$usuario_id]);
            $usuario = $stmtUsuario->fetch(PDO::FETCH_ASSOC);

            echo json_encode([
                'comentario' => "<div class='comentario'>"
                               . "<strong>" . htmlspecialchars($usuario['nome']) . ":</strong> "
                               . $comentario
                               . "<p><em>" . date('d/m/Y H:i') . "</em></p>"
                               . "</div>"
            ]);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Erro ao salvar seu comentário. Tente novamente.']);
        }
        exit();
    }

    elseif (isset($_POST['add_to_cart'], $_POST['id_jogo'])) {
        $id_jogo = $_POST['id_jogo'];

        try {
            $stmtJogo = $conexao->prepare("SELECT valor FROM jogos WHERE id = ?");
            $stmtJogo->execute([$id_jogo]);
            $jogoInfo = $stmtJogo->fetch(PDO::FETCH_ASSOC);

            $stmtCarrinho = $conexao->prepare(
                "SELECT * FROM carrinho WHERE usuario_id = ? AND id_jogo = ?"
            );
            $stmtCarrinho->execute([$usuario_id, $id_jogo]);
            $itemExistente = $stmtCarrinho->fetch(PDO::FETCH_ASSOC);

            if ($itemExistente) {
                $novaQuantidade = $itemExistente['quantidade'] + 1;
                $valor = $novaQuantidade * $jogoInfo['valor'];

                $stmt = $conexao->prepare("UPDATE carrinho SET quantidade = ?, valor = ? WHERE usuario_id = ? AND id_jogo = ?");
                $stmt->execute([$novaQuantidade, $valor, $usuario_id, $id_jogo]);
            } else {
                $stmt = $conexao->prepare("INSERT INTO carrinho (usuario_id, id_jogo, quantidade, valor, data_adicao) VALUES (?, ?, 1, ?, NOW())");
                $stmt->execute([$usuario_id, $id_jogo, $jogoInfo['valor']]);
            }

            echo json_encode(['success' => 'Jogo adicionado ao carrinho.']);
        } catch (Exception $e) {
            echo json_encode(['error' => 'Erro ao adicionar ao carrinho.']);
        }
        exit();
    }
}
?>