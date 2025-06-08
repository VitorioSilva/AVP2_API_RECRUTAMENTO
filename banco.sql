CREATE DATABASE recrutamento_api;
USE recrutamento_api;

CREATE TABLE vagas (
    id CHAR(36) PRIMARY KEY,
    empresa VARCHAR(255) NOT NULL,
    titulo VARCHAR(255) NOT NULL,
    descricao TEXT,
    localizacao CHAR(1) NOT NULL,
    nivel TINYINT NOT NULL,
    CHECK (localizacao REGEXP '^[A-F]$'),
    CHECK (nivel BETWEEN 1 AND 5)
);

CREATE TABLE pessoas (
    id CHAR(36) PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    profissao VARCHAR(255) NOT NULL,
    localizacao CHAR(1) NOT NULL,
    nivel TINYINT NOT NULL,
    CHECK (localizacao REGEXP '^[A-F]$'),
    CHECK (nivel BETWEEN 1 AND 5)
);

CREATE TABLE candidaturas (
    id CHAR(36) PRIMARY KEY,
    id_vaga CHAR(36) NOT NULL,
    id_pessoa CHAR(36) NOT NULL,
    score INT NOT NULL,
    FOREIGN KEY (id_vaga) REFERENCES vagas(id) ON DELETE CASCADE,
    FOREIGN KEY (id_pessoa) REFERENCES pessoas(id) ON DELETE CASCADE,
    UNIQUE KEY (id_vaga, id_pessoa)
);