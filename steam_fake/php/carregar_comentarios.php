<?php
include 'config/conexao.php';

$id_jogo = isset($_GET['id_jogo']) ? (int)$_GET['id_jogo'] : 0;
$offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 3;

$stmt = $conexao->prepare("
    SELECT c.usuario_id, c.comentario, c.data_comentario, u.nome 
    FROM comentarios c 
    JOIN usuarios u ON c.usuario_id = u.id 
    WHERE c.id_jogo = ? 
    ORDER BY c.data_comentario DESC 
    LIMIT ?, ?
");

$stmt->bindValue(1, $id_jogo, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->bindValue(3, $limit, PDO::PARAM_INT);
$stmt->execute();

$comentarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Se não houver comentários, não mostrar nada
if (empty($comentarios)) {
    echo '';
    exit;
}

foreach ($comentarios as $comentario) {
    echo "<div class='comentario'>";
    echo "<strong>" . htmlspecialchars($comentario['nome']) . ":</strong> ";
    echo htmlspecialchars($comentario['comentario']);
    echo "<p><em>" . date('d/m/Y H:i', strtotime($comentario['data_comentario'])) . "</em></p>";
    echo "</div>";
}
?>
