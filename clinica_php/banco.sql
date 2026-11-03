-- ============================================================
-- Banco de dados: Clínica veterinária
-- Tabelas: usuarios, tutores (relacionada) e animais (principal)
-- Usuário administrador: admin@clinica.com / senha: admin123
-- ============================================================

SET NAMES utf8mb4;

CREATE DATABASE IF NOT EXISTS clinica_veterinaria
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinica_veterinaria;

-- Apaga as tabelas na ordem certa (primeiro a que tem a chave estrangeira)
DROP TABLE IF EXISTS animais;
DROP TABLE IF EXISTS tutores;
DROP TABLE IF EXISTS usuarios;

-- Usuários que podem entrar no painel
CREATE TABLE usuarios (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  nome      VARCHAR(100) NOT NULL,
  email     VARCHAR(150) NOT NULL UNIQUE,
  senha     VARCHAR(255) NOT NULL,          -- guarda o hash, nunca a senha pura
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tutores (donos dos animais)
CREATE TABLE tutores (
  id        INT AUTO_INCREMENT PRIMARY KEY,
  nome      VARCHAR(100) NOT NULL,
  telefone  VARCHAR(20)  NOT NULL,
  email     VARCHAR(150),
  criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Animais atendidos pela clínica
CREATE TABLE animais (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nome       VARCHAR(100) NOT NULL,
  especie    VARCHAR(50)  NOT NULL,
  raca       VARCHAR(80),
  idade      INT,                            -- em anos
  peso       DECIMAL(5,2),                   -- em kg
  imagem_url VARCHAR(255),
  tutor_id   INT NOT NULL,
  criado_em  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  -- RESTRICT: não deixa excluir um tutor que ainda tem animais
  CONSTRAINT fk_animais_tutor FOREIGN KEY (tutor_id)
    REFERENCES tutores(id) ON DELETE RESTRICT ON UPDATE CASCADE
);

-- Administrador (senha admin123 criptografada com password_hash)
INSERT INTO usuarios (nome, email, senha) VALUES
('Administrador', 'admin@clinica.com', '$2y$10$ebqgpiVzrXvuSYjfqTbJKOwPBL/3qpLgmK3eF3K3VgDZHufGW6tyq');

INSERT INTO tutores (nome, telefone, email) VALUES
('Ana Souza',      '(11) 98765-4321', 'ana.souza@email.com'),
('Bruno Lima',     '(11) 97654-3210', 'bruno.lima@email.com'),
('Carla Mendes',   '(11) 96543-2109', 'carla.mendes@email.com'),
('Diego Ferreira', '(11) 95432-1098', NULL),
('Elisa Rocha',    '(11) 94321-0987', 'elisa.rocha@email.com');

INSERT INTO animais (nome, especie, raca, idade, peso, imagem_url, tutor_id) VALUES
('Thor',   'Cachorro', 'Golden Retriever', 4, 32.50, 'https://images.dog.ceo/breeds/retriever-golden/n02099601_3004.jpg', 1),
('Mel',    'Gato',     'Siamês',           2,  4.20, 'https://cdn2.thecatapi.com/images/ai6.jpg', 1),
('Bob',    'Cachorro', 'Beagle',           6, 12.80, 'https://images.dog.ceo/breeds/beagle/n02088364_11136.jpg', 2),
('Luna',   'Gato',     'Sem raça definida',1,  3.10, 'https://cdn2.thecatapi.com/images/MTY3ODIyMQ.jpg', 3),
('Pipoca', 'Ave',      'Calopsita',        3,  0.09, NULL, 4),
('Rex',    'Cachorro', 'Pastor Alemão',    8, 35.00, 'https://images.dog.ceo/breeds/germanshepherd/n02106662_10908.jpg', 5);
