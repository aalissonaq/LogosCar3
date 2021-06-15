-- phpMyAdmin SQL Dump
-- version 4.9.5deb2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Tempo de geração: 15/06/2021 às 11:54
-- Versão do servidor: 8.0.25-0ubuntu0.20.04.1
-- Versão do PHP: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "-03:00";
--
-- Banco de dados: `logoscar3`
--

-- --------------------------------------------------------


--
-- Estrutura para tabela `tb_users`
--

CREATE TABLE IF NOT EXISTS `tb_users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `matr_user` int NOT NULL,
  `nome_user` varchar(70) NOT NULL,
  `senha` varchar(70) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `cidade` varchar(30) NOT NULL,
  `nivel` varchar(3) NOT NULL,
  `disponivel` tinyint(1) NOT NULL,
  `motorista` tinyint(1) NOT NULL,
  `cnh` varchar(20) DEFAULT NULL,
  `cat_cnh` varchar(2) DEFAULT NULL,
  `data_emissao` date DEFAULT NULL,
  `data_validade` date DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `data_criacao` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resp_criar` int NOT NULL,
  `data_inativacao` timestamp NULL DEFAULT NULL,
  `resp_inativ` int DEFAULT NULL,
  PRIMARY KEY(`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `tb_log`
--

CREATE TABLE IF NOT EXISTS `tb_log` (
  `id_login` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `uf` varchar(2) NOT NULL,
  `cidade` varchar(30) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `dispositivo` varchar(20) DEFAULT NULL,
  `data_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `acao` text NOT NULL,
  PRIMARY KEY(`id_login`),
  FOREIGN KEY (`id_user`) REFERENCES `tb_users`(`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
--
-- Despejando dados para a tabela `tb_users`
--

INSERT INTO `tb_users` (`id_user`, `matr_user`, `nome_user`, `senha`, `uf`, `cidade`, `nivel`, `disponivel`, `motorista`, `cnh`, `cat_cnh`, `data_emissao`, `data_validade`, `ativo`, `data_criacao`, `resp_criar`, `data_inativacao`, `resp_inativ`) VALUES
(1, 3794, 'Fabio Henrique Silva Furtado', 'e1b7e7803215d5488588370572d13102', 'MA', 'São Luís', 'MTR', 0, 0, '', NULL, NULL, NULL, 1, '2020-10-23 06:00:00', 1, NULL, NULL),
(4, 328, 'Diulle Clilson Brito Nunes', '26946a4a4eb22dbe9f85c90fb2b76ea0', 'MA', 'São Luís', 'ADM', 1, 1, '04578975406', 'B', '2019-04-02', '2024-04-02', 1, '2021-05-03 19:41:07', 1, NULL, NULL),
(5, 9782, 'Kassia Fernanda Ribeiro Leite', '6f3c5e5764f0f7ddd4f20a896bb752b5', 'MA', 'São Luís', 'OPR', 0, 0, NULL, NULL, NULL, NULL, 1, '2021-05-17 18:01:46', 1, NULL, NULL),
(6, 3344, 'Maria da Conceição da Silva Balieiro', '2003b517343def4d94458e4dd6a65d28', 'MA', 'São Luís', 'ADM', 0, 0, NULL, NULL, NULL, NULL, 1, '2021-05-17 18:02:37', 1, NULL, NULL),
(7, 9778, 'Andressa Costa Pereira', '9d13b409d0b55370695eded92d58263a', 'MA', 'São Luís', 'ADM', 0, 0, NULL, NULL, NULL, NULL, 1, '2021-05-17 18:03:07', 1, NULL, NULL),
(8, 8321, 'João da Cruz Santos Alves', 'b8ec0536bb3a86dbab9dbeab8386cf8e', 'MA', 'São Luís', 'OPR', 1, 1, '1234567', 'D', '2020-01-01', '2025-01-01', 1, '2021-05-17 18:04:36', 1, NULL, NULL),
(9, 44, 'João Henrique Castro Barros', 'a2d870b3b211e29394db4b6a9ad58c9a', 'MA', 'São Luís', 'OPR', 1, 1, '987654', 'B', '2020-02-02', '2025-02-02', 1, '2021-05-17 18:05:29', 1, NULL, NULL),
(10, 3343, 'Thiago Ramos Drumond', '9fffa09a92a28259fabe28f89e1b588a', 'MA', 'São Luís', 'MTR', 0, 0, NULL, NULL, NULL, NULL, 1, '2021-05-17 18:25:31', 1, NULL, NULL);

--
-- Estrutura para tabela `tb_veiculo`
--

CREATE TABLE IF NOT EXISTS `tb_veiculo` (
  `id_veiculo` int NOT NULL AUTO_INCREMENT,
  `alias` varchar(30) NOT NULL,
  `id_uf` varchar(3) NOT NULL,
  `id_cidade` varchar(40) NOT NULL,
  `ano_fab` int NOT NULL,
  `modelo_fab` int NOT NULL,
  `modelo` varchar(30) NOT NULL,
  `montadora` varchar(30) NOT NULL,
  `placa` varchar(7) NOT NULL,
  `kilometragem` int NOT NULL,
  `ult_manut_prog` int DEFAULT '0',
  `renavam` varchar(15) NOT NULL,
  `chassi` varchar(17) NOT NULL,
  `status` int NOT NULL,
  `cor` varchar(15) NOT NULL,
  `resp_criacao` int NOT NULL,
  `data_criacao` date NOT NULL,
  `data_recebimento` date NOT NULL,
  `ativo` tinyint(1) DEFAULT '1',
  `proprietario` varchar(40),
  `data_devolucao` date DEFAULT NULL,
  PRIMARY KEY(`id_veiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Estrutura para tabela `tb_viagem`
--

CREATE TABLE IF NOT EXISTS `tb_viagem` (
  `id_viagem` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `id_veiculo` int NOT NULL,
  `id_uf` varchar(2) NOT NULL,
  `id_cidade` varchar(35) NOT NULL,
  `em_andamento` tinyint(1) NOT NULL,
  `rota` varchar(250) NOT NULL,
  `alter_rota` varchar(250) DEFAULT NULL,
  `data_lancamento` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `data_retorno` timestamp NULL DEFAULT NULL,
  `km_inicio` int NOT NULL,
  `km_fim` int DEFAULT NULL,
  PRIMARY KEY(`id_viagem`),
  FOREIGN KEY (`id_veiculo`) REFERENCES `tb_veiculo`(`id_veiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `tb_abastecimento`
--

CREATE TABLE IF NOT EXISTS `tb_abastecimento` (
  `id_abastecimento` int NOT NULL AUTO_INCREMENT,
  `id_veiculo` int NOT NULL,
  `id_motorista` int NOT NULL,
  `valor_abastecimento` decimal(10,2) NOT NULL,
  `data_abastecimento` date NOT NULL,
  `km_abastecimento` int NOT NULL,
  `litros` decimal(10,2) DEFAULT NULL,
  `comprovante_abastecimento` longtext,
  PRIMARY KEY(`id_abastecimento`),
  FOREIGN KEY (`id_veiculo`) REFERENCES `tb_veiculo`(`id_veiculo`),
  FOREIGN KEY (`id_motorista`) REFERENCES `tb_users`(`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

CREATE TABLE IF NOT EXISTS `tb_licenciamento` (
  `id_licenciamento` int NOT NULL AUTO_INCREMENT,
  `id_veiculo` int NOT NULL,
  `ano_lic` int NOT NULL,
  `lic_pago` boolean not null default false,
  `data_pgto` timestamp,
  `valor_total` decimal(10,2),
  `valor_ipva` decimal(10,2),
  `valor_dpvat` decimal(10,2),
  `valor_lic` decimal(10,2),
  `lic_condicoes` varchar(50),
  `doc_pgto` longtext,
  PRIMARY KEY(`id_licenciamento`),
  FOREIGN KEY (`id_veiculo`) REFERENCES `tb_veiculo`(`id_veiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


--
-- Estrutura para tabela `tb_manutencao`
--

CREATE TABLE IF NOT EXISTS `tb_manutencao` (
  `id_manut` int NOT NULL AUTO_INCREMENT,
  `status_manut` tinyint(1) NOT NULL DEFAULT '1',
  `id_veiculo` int NOT NULL,
  `uf` varchar(2) NOT NULL,
  `cidade` varchar(30) NOT NULL,
  `km_ida` int NOT NULL,
  `km_retorno` int DEFAULT NULL,
  `tipo_manut` tinyint(1) NOT NULL,
  `local_manut` varchar(70) NOT NULL,
  `km_programada` int DEFAULT NULL,
  `data_manut` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data_retorno` timestamp NULL DEFAULT NULL,
  `valor_manut` float(10,2) DEFAULT NULL,
  `descricao_manut` text NOT NULL,
  PRIMARY KEY(`id_manut`),
  FOREIGN KEY (`id_veiculo`) REFERENCES `tb_veiculo`(`id_veiculo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Estrutura para tabela `tb_montadoras`
--

CREATE TABLE IF NOT EXISTS `tb_montadoras` (
  `id_montadora` int NOT NULL AUTO_INCREMENT,
  `montadora` varchar(30) NOT NULL,
  `modelo` varchar(30) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY(`id_montadora`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `tb_multas`
--

CREATE TABLE IF NOT EXISTS `tb_multas` (
  `id_multa` int NOT NULL AUTO_INCREMENT,
  `id_motorista` int NOT NULL,
  `id_veiculo` int NOT NULL,
  `id_viagem` int NOT NULL,
  `data_multa` date NOT NULL,
  `valor_multa` float(10,2) NOT NULL,
  `aceito` tinyint(1) DEFAULT NULL,
  `termo_assinado` longtext,
  `pago` int DEFAULT '0',
  `forma_pgto` varchar(3) DEFAULT NULL,
  `condicao_pgto` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `cidade_multa` varchar(30) NOT NULL,
  `uf_multa` varchar(2) NOT NULL,
  `local_multa` varchar(30) NOT NULL,
  PRIMARY KEY(`id_multa`),
  FOREIGN KEY (`id_motorista`) REFERENCES `tb_users`(`id_user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
