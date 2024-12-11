-- Criando o banco de dados e selecionando-o
CREATE DATABASE lancamentos_jogos;
USE lancamentos_jogos;

-- Tabela de usuários
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(100) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    nome VARCHAR(100) NOT NULL,
    genero ENUM('M', 'F') NOT NULL,
    data_nascimento DATE NOT NULL,
    cpf VARCHAR(11) UNIQUE NOT NULL,
    nivel_acesso ENUM('admin', 'usuario') NOT NULL DEFAULT 'usuario',
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de categorias
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_categoria VARCHAR(50) NOT NULL UNIQUE,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Tabela de jogos
CREATE TABLE jogos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    descricao TEXT NOT NULL,
    imagem VARCHAR(100) NOT NULL,
    capa_video VARCHAR(100) NULL, -- Adiciona o caminho ou URL para a imagem de capa do vídeo
    video VARCHAR(100) NULL, -- Adiciona o caminho ou URL para o vídeo do jogo
    data_lancamento DATE,
    valor DECIMAL(10, 2) NOT NULL,
    usuario_id INT,
    data_criacao_jogo DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Tabela de relacionamento entre jogos e categorias
CREATE TABLE jogo_categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_jogo INT,
    id_categoria INT,
    FOREIGN KEY (id_jogo) REFERENCES jogos(id) ON DELETE CASCADE,
    FOREIGN KEY (id_categoria) REFERENCES categorias(id) ON DELETE CASCADE
);

ALTER TABLE jogos MODIFY valor DECIMAL(10, 2) DEFAULT 0;

-- Tabela de comentários
CREATE TABLE comentarios (
    id_comentario INT AUTO_INCREMENT PRIMARY KEY,
    id_jogo INT,
    usuario_id INT,
    comentario TEXT,
    data_comentario DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_jogo) REFERENCES jogos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);

-- Tabela de likes
CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,       
    id_jogo INT NOT NULL,                    
    usuario_id INT NOT NULL,                 
    tipo_like TINYINT NOT NULL, -- Tipo do like: 1 para 'bom', 0 para 'ruim'
    UNIQUE (id_jogo, usuario_id),            
    FOREIGN KEY (id_jogo) REFERENCES jogos(id) ON DELETE CASCADE, 
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

-- Tabela de carrinho
CREATE TABLE carrinho (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    id_jogo INT NOT NULL,
    quantidade INT DEFAULT 1,
    valor DECIMAL(10, 2) NOT NULL,  -- O valor será automaticamente preenchido pelo gatilho
    data_adicao DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_jogo) REFERENCES jogos(id) ON DELETE CASCADE
);