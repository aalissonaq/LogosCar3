-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 15-Fev-2021 às 02:42
-- Versão do servidor: 10.4.14-MariaDB
-- versão do PHP: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "-03:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `logoscar3`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_log`
--

CREATE TABLE IF NOT EXISTS `tb_log` (
  `id_login` int(11) NOT NULL,
  `id_user` int(4) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `cidade` varchar(30) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `dispositivo` varchar(20) DEFAULT NULL,
  `data_login` timestamp NOT NULL DEFAULT current_timestamp(),
  `acao` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_log`
--

INSERT INTO `tb_log` (`id_login`, `id_user`, `uf`, `cidade`, `ip`, `dispositivo`, `data_login`, `acao`) VALUES
(1, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-07 03:44:08', 'efetuou o login via Desktop'),
(2, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-07 03:44:08', 'efetuou o login via Desktop'),
(3, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-07 03:44:31', 'efetuou o logout'),
(4, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-07 03:44:41', 'efetuou o login via Desktop'),
(5, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-07 04:42:06', 'efetuou o logout'),
(6, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-07 04:49:48', 'efetuou o login via Desktop'),
(7, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-07 18:44:23', 'efetuou o login via Desktop'),
(8, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-07 20:51:14', 'adicionou um novo usuário: nome Fulano de Tal dos Anzóis, de matrícula 1234 com o nível OPR'),
(9, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-07 20:51:37', 'efetuou o logout'),
(10, 2, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-07 20:51:48', 'efetuou o login via Desktop'),
(11, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-07 23:42:39', 'efetuou o login via Desktop'),
(12, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 00:13:21', 'adicionou o carro Volkswagen Saveiro de placa QRV4A31 à lista de veículos'),
(13, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 00:17:44', 'adicionou o carro Volkswagen Saveiro de placa QRV0B98 à lista de veículos'),
(14, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 00:20:44', 'adicionou o carro Volkswagen Saveiro de placa QRP0617 à lista de veículos'),
(15, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 00:20:44', 'adicionou o carro Volkswagen Saveiro de placa QRP0617 à lista de veículos'),
(16, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 00:24:23', 'efetuou o logout'),
(17, 2, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 00:24:35', 'efetuou o login via Desktop'),
(18, 1, 'MA', 'São Luís', '181.220.251.73', 'Android', '2021-02-08 00:29:23', 'efetuou o login via Android'),
(19, 1, 'MA', 'São Luís', '181.220.251.73', 'Android', '2021-02-08 00:30:37', 'efetuou o logout'),
(20, 1, 'MA', 'São Luís', '181.220.251.73', 'Android', '2021-02-08 00:57:28', 'efetuou o login via Android'),
(21, 1, 'MA', 'São Luís', '181.220.251.73', 'Android', '2021-02-08 00:58:49', 'adicionou um novo usuário: nome Dino da Silva Sauro, de matrícula 9999 com o nível OPR'),
(22, 2, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 03:20:08', 'inseriu a rota The Prime ao veículo de id 2, dirigido por9999'),
(23, 1, 'MA', 'São Luís', '::1', 'Desktop', '2021-02-08 16:37:19', 'efetuou o login via Desktop'),
(24, 1, 'MA', 'São Luís', '200.253.156.10', 'Desktop', '2021-02-08 20:33:22', 'efetuou o logout'),
(25, 1, 'MA', 'São Luís', '200.253.156.10', 'Desktop', '2021-02-08 20:33:36', 'efetuou o login via Desktop'),
(26, 1, 'MA', 'São Luís', '200.253.156.10', 'Desktop', '2021-02-08 21:11:20', 'inseriu a rota The Prime ao veículo de id 2, dirigido porDino da Silva Sauro'),
(27, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 22:53:11', 'efetuou o login via Desktop'),
(28, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 22:53:29', 'efetuou o logout'),
(29, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 23:10:20', 'efetuou o login via Desktop'),
(30, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 23:15:40', 'inseriu a rota Sítio da Mata ao veículo de id 1, dirigido por9999'),
(31, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 23:22:59', 'inseriu a rota Sítio da Mata ao veículo de id 2, dirigido por9999'),
(32, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 23:25:58', 'inseriu a rota The Prime ao veículo de id 1, dirigido por9999'),
(33, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 23:47:29', 'encerrou a rota The Prime, de id 5 ao veículo de id 1, dirigido por9999'),
(34, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 23:48:57', 'inseriu a rota Sítio da Mata ao veículo de id 1, dirigido por9999'),
(35, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-08 23:49:23', 'encerrou a rota Sítio da Mata, de id 6 ao veículo de id 1, dirigido por9999'),
(36, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-09 01:14:46', 'inseriu a rota Sítio da Mata ao veículo de id 1, dirigido por 9999'),
(37, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-09 23:32:45', 'efetuou o login via Desktop'),
(38, 1, 'MA', 'São Luís', '177.91.54.38', 'Desktop', '2021-02-11 20:12:01', 'efetuou o login via Desktop'),
(39, 1, 'MA', 'São Luís', '177.91.54.38', 'Desktop', '2021-02-11 20:15:20', 'encerrou a rota Sítio da Mata, de id 7 ao veículo de id 1, dirigido por 9999'),
(40, 1, 'MA', 'São Luís', '177.91.54.38', 'Desktop', '2021-02-11 20:16:19', 'efetuou o logout'),
(41, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-13 00:50:34', 'efetuou o login via Desktop'),
(42, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-13 00:50:43', 'efetuou o logout'),
(43, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-13 00:52:45', 'efetuou o login via Desktop'),
(44, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-13 02:39:43', 'inseriu a rota Cartório ao veículo de id 2, dirigido por 9999'),
(45, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-13 03:21:40', 'efetuou o login via Desktop'),
(46, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-13 03:23:06', 'efetuou o logout'),
(47, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-13 03:23:39', 'encerrou a rota Cartório, de id 8 ao veículo de id 2, dirigido por 9999'),
(48, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-13 04:25:32', '1 indicou manutenção não programada no veículo de ID: '),
(49, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-14 20:41:49', 'efetuou o login via Desktop'),
(50, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-14 23:04:05', '1 indicou retorno da manutenção no veículo de ID: 2 com o km: 23'),
(51, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-14 23:40:15', '1 indicou manutenção não programada no veículo de ID: com o km: 190'),
(52, 1, 'MA', 'São Luís', '181.220.251.73', 'Desktop', '2021-02-14 23:41:06', '1 indicou retorno da manutenção no veículo de ID: 1 com o km: 198');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_manutencao`
--

CREATE TABLE IF NOT EXISTS `tb_manutencao` (
  `id_manut` int(11) NOT NULL,
  `status_manut` tinyint(1) NOT NULL DEFAULT 1,
  `id_veiculo` int(11) NOT NULL,
  `uf` varchar(2) NOT NULL,
  `cidade` varchar(30) NOT NULL,
  `km_ida` int(11) NOT NULL,
  `km_retorno` int(11) DEFAULT NULL,
  `tipo_manut` tinyint(1) NOT NULL,
  `local_manut` varchar(70) NOT NULL,
  `km_programada` int(11) DEFAULT NULL,
  `data_manut` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `data_retorno` timestamp NULL DEFAULT NULL,
  `descricao_manut` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_manutencao`
--

INSERT INTO `tb_manutencao` (`id_manut`, `status_manut`, `id_veiculo`, `uf`, `cidade`, `km_ida`, `km_retorno`, `tipo_manut`, `local_manut`, `km_programada`, `data_manut`, `data_retorno`, `descricao_manut`) VALUES
(1, 0, 2, 'MA', 'São Luís', 19, 23, 1, 'Bremen', 10000, '2021-02-14 23:04:04', '2021-02-14 23:50:13', 'Vazou água radiador e ajeitou a rebimboca da parafuseta'),
(2, 0, 1, 'MA', 'São Luís', 190, 198, 0, 'Bremen', NULL, '2021-02-15 01:40:14', '2021-02-15 01:40:00', 'Carro não liga - Troca da bateria');

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_montadoras`
--

CREATE TABLE IF NOT EXISTS `tb_montadoras` (
  `id_montadora` int(11) NOT NULL,
  `montadora` varchar(30) NOT NULL,
  `modelo` varchar(30) NOT NULL,
  `data_cadastro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_users`
--

CREATE TABLE IF NOT EXISTS `tb_users` (
  `id_user` int(4) NOT NULL,
  `matr_user` int(6) NOT NULL,
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
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `resp_criar` int(4) NOT NULL,
  `data_inativacao` timestamp NULL DEFAULT NULL,
  `resp_inativ` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_users`
--

INSERT INTO `tb_users` (`id_user`, `matr_user`, `nome_user`, `senha`, `uf`, `cidade`, `nivel`, `disponivel`, `motorista`, `cnh`, `cat_cnh`, `data_emissao`, `data_validade`, `ativo`, `data_criacao`, `resp_criar`, `data_inativacao`, `resp_inativ`) VALUES
(1, 3794, 'Fabio Henrique Silva Furtado', 'e1b7e7803215d5488588370572d13102', 'MA', 'São Luís', 'MTR', 0, 0, '', NULL, NULL, NULL, 1, '2020-10-23 03:00:00', 1, NULL, NULL),
(2, 1234, 'Fulano de Tal dos Anzóis', 'ba7097f90f96cbf5ac9d6e08acf06282', 'MA', 'São Luís', 'OPR', 0, 0, NULL, NULL, NULL, NULL, 1, '2021-02-07 20:51:13', 1, NULL, NULL),
(3, 9999, 'Dino da Silva Sauro', 'b7fc899ad32bc7e2d8d0d6e723969439', 'MA', 'São Luís', 'OPR', 1, 1, '123456', 'AB', '2019-10-01', '2023-10-01', 1, '2021-02-08 00:58:48', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_veiculo`
--

CREATE TABLE IF NOT EXISTS `tb_veiculo` (
  `id_veiculo` int(11) NOT NULL,
  `id_uf` varchar(2) NOT NULL,
  `id_cidade` varchar(40) NOT NULL,
  `proprietario` varchar(20) NOT NULL,
  `montadora` varchar(20) NOT NULL,
  `modelo` varchar(20) NOT NULL,
  `alias` varchar(25) NOT NULL,
  `ano_fab` int(11) NOT NULL,
  `modelo_fab` int(11) NOT NULL,
  `placa` varchar(7) NOT NULL,
  `kilometragem` int(11) NOT NULL,
  `renavam` varchar(15) NOT NULL,
  `chassi` varchar(15) NOT NULL,
  `status` int(11) NOT NULL,
  `cor` varchar(15) NOT NULL,
  `resp_criacao` int(11) NOT NULL,
  `data_criacao` date NOT NULL,
  `data_recebimento` date NOT NULL,
  `ativo` boolean DEFAULT 1,
  `data_devolucao` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `tb_veiculo`
--

INSERT INTO `tb_veiculo` (`id_veiculo`, `id_uf`, `id_cidade`, `proprietario`, `montadora`, `modelo`, `alias`, `ano_fab`, `modelo_fab`, `placa`, `kilometragem`, `renavam`, `chassi`, `status`, `cor`, `resp_criacao`, `data_criacao`, `data_recebimento`, `ativo`, `data_devolucao`) VALUES
(1, 'MA', 'São Luís', 'Logos', 'Volkswagen', 'Gol', 'Gol 01', 2018, 2019, 'QPO7J29', 198, '123456789', '123456', 1, '#6B6B6B', 1, '2021-02-06', '2020-12-12', 1, NULL),
(2, 'MA', 'São Luís', 'Logos', 'Volkswagen', 'Saveiro', 'Saveiro', 2018, 2018, 'QRP0617', 23, '24791452039', 'QA458DSQW6CC5S8', 1, '#6B6B6B', 1, '2021-02-07', '2018-06-13', 1, NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_viagem`
--

CREATE TABLE IF NOT EXISTS `tb_viagem` (
  `id_viagem` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_veiculo` int(11) NOT NULL,
  `id_uf` varchar(2) NOT NULL,
  `id_cidade` varchar(35) NOT NULL,
  `em_andamento` tinyint(1) NOT NULL,
  `rota` varchar(250) NOT NULL,
  `alter_rota` varchar(250) DEFAULT NULL,
  `data_lancamento` timestamp NOT NULL DEFAULT current_timestamp(),
  `data_retorno` timestamp NULL DEFAULT NULL,
  `km_inicio` int(11) NOT NULL,
  `km_fim` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_viagem`
--

CREATE TABLE IF NOT EXISTS `tb_viagem` (
  `id_multa` int NOT NULL AUTO_INCREMENT,
  `id_motorista` int NOT NULL,
  `id_veiculo` int NOT NULL,
  `data_multa` date NOT NULL,
  `valor_multa` varchar(8) NOT NULL,
  `pago` boolean NOT NULL,
  `cidade_multa` varchar(30) NOT NULL,
  `uf_multa` varchar(2) NOT NULL, NOT NULL,
  `local_multa` varchar(30) NOT  NOT NULL,NULL,
  `resp_pgto` boolean NOT NULL,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
--
-- Extraindo dados da tabela `tb_viagem`
--

INSERT INTO `tb_viagem` (`id_viagem`, `id_user`, `id_veiculo`, `id_uf`, `id_cidade`, `em_andamento`, `rota`, `alter_rota`, `data_lancamento`, `data_retorno`, `km_inicio`, `km_fim`) VALUES
(5, 9999, 1, 'MA', 'São Luís', 0, 'The Prime', NULL, '2021-02-08 23:25:58', '2021-02-08 23:47:29', 123, 150),
(6, 9999, 1, 'MA', 'São Luís', 0, 'Sítio da Mata', 'Casa do Construtor', '2021-02-08 23:48:56', '2021-02-08 23:49:22', 150, 172),
(7, 9999, 1, 'MA', 'São Luís', 0, 'Sítio da Mata', NULL, '2021-02-09 01:14:45', '2021-02-11 20:15:20', 172, 190),
(8, 9999, 2, 'MA', 'São Luís', 0, 'Cartório', NULL, '2021-02-13 02:39:43', '2021-02-13 03:23:38', 13, 19);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `tb_log`
--
ALTER TABLE `tb_log`
  ADD PRIMARY KEY (`id_login`),
  ADD KEY `id_user` (`id_user`);

--
-- Índices para tabela `tb_manutencao`
--
ALTER TABLE `tb_manutencao`
  ADD PRIMARY KEY (`id_manut`);

--
-- Índices para tabela `tb_montadoras`
--
ALTER TABLE `tb_montadoras`
  ADD PRIMARY KEY (`id_montadora`);

--
-- Índices para tabela `tb_users`
--
ALTER TABLE `tb_users`
  ADD PRIMARY KEY (`id_user`);

--
-- Índices para tabela `tb_veiculo`
--
ALTER TABLE `tb_veiculo`
  ADD PRIMARY KEY (`id_veiculo`),
  ADD KEY `resp_criacao` (`resp_criacao`);

--
-- Índices para tabela `tb_viagem`
--
ALTER TABLE `tb_viagem`
  ADD PRIMARY KEY (`id_viagem`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `tb_log`
--
ALTER TABLE `tb_log`
  MODIFY `id_login` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de tabela `tb_manutencao`
--
ALTER TABLE `tb_manutencao`
  MODIFY `id_manut` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_montadoras`
--
ALTER TABLE `tb_montadoras`
  MODIFY `id_montadora` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `tb_users`
--
ALTER TABLE `tb_users`
  MODIFY `id_user` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `tb_veiculo`
--
ALTER TABLE `tb_veiculo`
  MODIFY `id_veiculo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `tb_viagem`
--
ALTER TABLE `tb_viagem`
  MODIFY `id_viagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `tb_log`
--
ALTER TABLE `tb_log`
  ADD CONSTRAINT `tb_log_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `tb_users` (`id_user`);

--
-- Limitadores para a tabela `tb_veiculo`
--
ALTER TABLE `tb_veiculo`
  ADD CONSTRAINT `tb_veiculo_ibfk_1` FOREIGN KEY (`resp_criacao`) REFERENCES `tb_users` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
