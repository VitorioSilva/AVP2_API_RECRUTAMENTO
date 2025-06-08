# API de Recrutamento

API para análise de candidaturas conforme desafio da disciplina de Backend

## 🚀 Tecnologias
- PHP 8.0+
- MySQL 8.0
- PDO (PHP Data Objects)

## ✅ Requisitos Atendidos
- [X] CRUD de Vagas/Pessoas/Candidaturas
- [X] Cálculo automático de scores
- [X] Validações rigorosas (UUID, níveis 1-5, localizações A-F)
- [X] Banco de dados MySQL sem ORM
- [X] 100% dos endpoints implementados
- [X] Tratamento de erros conforme especificação

## 📊 Endpoints

| POST   | /vagas
| POST   | /pessoas
| POST   | /candidaturas
| GET    | /vagas/{id}/candidaturas/ranking

## 🔧 Instalação

1. Pré-requisitos:
   - PHP 8.0+
   - MySQL 8.0
   - Apache/Nginx

2. Configuração do banco:

   CREATE DATABASE recrutamento_api;
   USE recrutamento_api;
   
   CREATE TABLE vagas (
       id CHAR(36) PRIMARY KEY,
       empresa VARCHAR(255) NOT NULL,
       titulo VARCHAR(255) NOT NULL,
       descricao TEXT,
       localizacao CHAR(1) NOT NULL CHECK (localizacao REGEXP '^[A-F]$'),
       nivel TINYINT NOT NULL CHECK (nivel BETWEEN 1 AND 5)
   );
   
   -- Demais tabelas (pessoas, candidaturas) conforme banco.sql

## 🛠 Estrutura do Projeto

/AVP2_API_RECRUTAMENTO
├── config/
│   └── database.php
├── controllers/
│   ├── CandidaturaController.php
│   ├── PessoaController.php
│   └── VagaController.php
├── models/
│   ├── Candidatura.php
│   ├── Database.php
│   ├── Pessoa.php
│   └── Vaga.php
├── utils/
│   └── Validation.php
├── index.php
├── banco.sql
└── README.md