CREATE DATABASE contatos_web;

USE contatos_web;

CREATE TABLE usuario (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    senha VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    token VARCHAR(255) DEFAULT NULL,
) Engine = INNODB;


CREATE TABLE contatos_info (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    telefone VARCHAR(20),   
    email VARCHAR(100) NOT NULL,
)Engine = INNODB; 


