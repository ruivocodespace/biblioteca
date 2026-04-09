-- Cria o banco de dados se ele ainda não existir
CREATE DATABASE IF NOT EXISTS biblioteca_db;

-- Seleciona o banco de dados para uso
USE biblioteca_db;

-- Cria a tabela de livros
CREATE TABLE IF NOT EXISTS livros (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(255) NOT NULL,
    autor VARCHAR(255) NOT NULL,
    ano_publicacao INT NOT NULL,
    capa_caminho VARCHAR(255) DEFAULT NULL,
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);