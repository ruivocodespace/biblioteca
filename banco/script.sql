-- Cria o banco de dados se ele ainda não existir
CREATE DATABASE IF NOT EXISTS biblioteca_db;

-- Seleciona o banco de dados para uso
USE biblioteca_db;

-- Cria a tabela de livros
CREATE TABLE IF NOT EXISTS livros (
    id        INT          NOT NULL AUTO_INCREMENT,
    titulo    VARCHAR(150) NOT NULL,
    autor     VARCHAR(150) NOT NULL,
    genero    VARCHAR(255)     NULL,
    ano       INT     		   NULL,
    capa      VARCHAR(255)     NULL,
    criado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cria a tabela de usuarios
CREATE TABLE IF NOT EXISTS usuarios (
    id        INT          NOT NULL AUTO_INCREMENT,
    nome      VARCHAR(150) NOT NULL,
    email     VARCHAR(150) NOT NULL,
    senha     VARCHAR(255) NOT NULL,
    tipo      ENUM('user','admin') NOT NULL DEFAULT 'admin',
    criado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insere o usuário admin na tabela de usuarios
INSERT INTO usuarios (nome, email, senha) VALUES 
('Administrador', 'admin@email.com', 'admin123');