CREATE DATABASE gerenciador_de_solicitacoes;
USE gerenciador_de_solicitacoes;

CREATE TABLE cliente (
	id_cliente INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nome_cliente VARCHAR(115) NOT NULL,
    senha_cliente VARCHAR(115) NOT NULL,
    ultimo_nome_cliente VARCHAR(115) NOT NULL,
    cpf_cliente VARCHAR(11) NOT NULL,
    telefone_cliente VARCHAR(13) NOT NULL, -- 5547987654321
    email_cliente VARCHAR(115) NOT NULL
);

CREATE TABLE colaborador (
	id_colaborador INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    nome_colaborador VARCHAR(115) NOT NULL,
	ultimo_nome_colaborador VARCHAR(115) NOT NULL,
    matricula_colaborador VARCHAR(45) NOT NULL,
    email_colaborador VARCHAR(115) NOT NULL,
    senha_colaborador VARCHAR(115) NOT NULL
);

CREATE TABLE solicitacao (
	id_solicitacao INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    id_cliente_solicitacao INT NOT NULL,
    FOREIGN KEY (id_cliente_solicitacao) REFERENCES cliente(id_cliente) ON DELETE CASCADE,
    descricao_solicitacao VARCHAR(500) NOT NULL,
    criticidade_solicitacao ENUM('baixa', 'média', 'alta') NOT NULL,
    status_solicitacao ENUM('pendente', 'em andamento', 'resolvido') NOT NULL,
    data_abertura_solicitacao DATETIME NOT NULL,
    id_colaborador_responsavel_solicitacao INT, -- Opcional
	FOREIGN KEY (id_colaborador_responsavel_solicitacao) REFERENCES colaborador(id_colaborador) ON DELETE SET NULL
);


INSERT INTO colaborador 
VALUES (1, "João", "Magalhães", "3X84K", "j_magalhaes@wtech.com", "#Arcane2Temporada"),
(2, "Arthur", "dos Santos", "8J72B", "a_dosSantos@wtech.com", "@piririm12"),
(3, "Otávio", "Soares", "3P41M", "o_soares@wtech.com", "72kEhPouco!");

