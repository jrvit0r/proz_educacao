// Função para iniciar o vídeo ao clicar na capa
function playVideo() {
  const videoContainer = document.querySelector(".video-container");
  const img = videoContainer.querySelector("img");
  const iframe = videoContainer.querySelector("iframe");
  img.style.display = "none";
  iframe.style.display = "block";
}

// Função para gerenciar curtidas/descurtidas no jogo
function likeDislikeGame(idJogo, action) {
  const button =
    action === "like"
      ? $("#like-button-" + idJogo)
      : $("#dislike-button-" + idJogo);

  button.prop("disabled", true);

  $.post(
    "salvar.php",
    { like_jogo: idJogo, tipo_like: action === "like" ? 1 : 0 },
    function (response) {
      if (response.success) {
        document.getElementById("like-count-" + idJogo).innerText =
          response.total_likes;
        document.getElementById("dislike-count-" + idJogo).innerText =
          response.total_dislikes;
      }
      button.prop("disabled", false);
    },
    "json"
  ).fail(function () {
    alert("Erro ao salvar sua reação. Tente novamente.");
    button.prop("disabled", false);
  });
}

// Função para enviar comentários
function submitComment(id_jogo) {
  const comentario = $("#comentario-" + id_jogo)
    .val()
    .trim();
  if (comentario === "") return alert("Comentário não pode ser vazio!");

  const commentButton = $("#submit-comment-" + id_jogo);
  commentButton.prop("disabled", true);

  $.post(
    "salvar.php",
    { id_jogo: id_jogo, comentario: comentario },
    function (response) {
      if (response.comentario) {
        $("#comentarios-" + id_jogo).prepend(response.comentario);
        $("#comentario-" + id_jogo).val("");
      } else {
        alert(response.error);
      }
    },
    "json"
  )
    .fail(function () {
      alert("Erro ao salvar seu comentário. Tente novamente.");
    })
    .always(function () {
      commentButton.prop("disabled", false);
    });
}

// Função para adicionar ao carrinho
function addToCart(idJogo) {
  $.post(
    "salvar.php",
    { add_to_cart: true, id_jogo: idJogo },
    function (response) {
      if (response.success) {
        alert(response.success);
      } else {
        alert(response.error);
      }
    },
    "json"
  ).fail(function () {
    alert("Erro ao adicionar ao carrinho. Tente novamente.");
  });
}

// Função para mostrar ou esconder comentários
function toggleComments(idJogo) {
  const commentsContainer = document.getElementById("comments-" + idJogo);
  const button = document.getElementById("toggle-comments-" + idJogo);

  if (
    commentsContainer.style.display === "none" ||
    commentsContainer.style.display === ""
  ) {
    commentsContainer.style.display = "block";
    button.textContent = "Esconder Comentários";
  } else {
    commentsContainer.style.display = "none";
    button.textContent = "Mostrar Comentários";
  }
}
