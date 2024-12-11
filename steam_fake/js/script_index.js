// Função para deslizar o carrossel para a esquerda
function slideLeft(categoriaId) {
  const carrossel = document.getElementById("carrossel-" + categoriaId);
  const width = carrossel.querySelector(".jogo").clientWidth;
  carrossel.scrollBy({ left: -width, behavior: "smooth" });
}

// Função para deslizar o carrossel para a direita
function slideRight(categoriaId) {
  const carrossel = document.getElementById("carrossel-" + categoriaId);
  const width = carrossel.querySelector(".jogo").clientWidth;
  carrossel.scrollBy({ left: width, behavior: "smooth" });
}
