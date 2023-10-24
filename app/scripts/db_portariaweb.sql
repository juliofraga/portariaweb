-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07-Set-2023 às 13:55
-- Versão do servidor: 10.4.17-MariaDB
-- versão do PHP: 7.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_portariaweb`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cameras`
--

CREATE TABLE `cameras` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `endereco_ip` char(255) NOT NULL,
  `situacao` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Ativo\n1 - Inativo',
  `created_at` datetime NOT NULL,
  `updated_at` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `camera_has_portaria`
--

CREATE TABLE `camera_has_portaria` (
  `id` int(11) NOT NULL,
  `camera_id` int(11) NOT NULL,
  `portaria_id` int(11) NOT NULL,
  `entrada_saida` char(1) NOT NULL COMMENT 'E - Entrada\r\nS - Saída'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `configuracoes`
--

CREATE TABLE `configuracoes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(60) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Sim\n1 - Não',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `configuracoes`
--

INSERT INTO `configuracoes` (`id`, `titulo`, `descricao`, `valor`, `created_at`, `updated_at`) VALUES
(1, 'Ativar complexidade de senha', 'Com esta opção ativada, a senha de acesso ao sistema deverá atender os seguintes requisitos: possuir no mínimo 8 caracteres; possuir pelo menos uma letra maiúscula; possuir pelo menos uma letra minúscula; ter números. Caso ela esteja desativada, o único r', 1, '2023-03-08 21:14:19', '2023-04-01 07:46:13'),
(2, 'Bloqueio de conta por tentativas de acesso', 'Com esta opção ativada, se o usuário errar mais de 5 vezes a senha, o acesso será bloqueado, devendo ser liberado novamente por algum administrador. Caso esteja desativada, o usuário poderá errar a senha inúmeras vezes que não causará bloqueio da conta.', 1, '2023-03-11 18:58:14', '2023-09-06 08:30:30'),
(3, 'Permitir operação de emergência para operador', 'Com esta opção ativada, além do administrador do sistema, o operador também poderá executar operações de emergência, ou seja, poderá abrir e fechar as cancelas sem registrar entrada de veículos.', 1, '2023-04-14 08:41:32', '2023-04-14 08:43:30'),
(4, 'Capturar imagens no fechamento da cancela', 'Com esta opção ativa, serão capturadas também imagens no momento do fechamento da cancela. Se ela estiver desativada, serão capturadas imagens apenas na abertura da cancela.', 1, '2023-07-31 07:57:14', '2023-09-05 08:13:44'),
(5, 'Permitir consultas de operações para operador', 'Com esta opção ativada, além do administrador do sistema, o operador também poderá visualizar a tela de consultas de operações.', 0, '2023-09-02 14:35:08', '2023-09-05 08:13:47'),
(6, 'Ativa logs backend', 'Com essa opção ativa, serão capturados os logs do backend', 0, '2023-09-07 08:50:25', NULL),
(7, 'Ativa logs frontend', 'Com essa opção ativa, serão capturados os logs do frontend', 1, '2023-09-07 08:52:27', NULL),
(8, 'Ativa logs de erros DB', 'Com essa opção ativa, serão capturados os logs erros do banco de dados', 0, '2023-09-07 08:53:09', NULL),
(9, 'Ativa logs de erros PHP', 'Com essa opção ativa, serão capturados os logs erros do PHP', 1, '2023-09-07 08:54:10', NULL),
(10, 'Exibir logs para Administrador', 'Com esta opção ativa, os usuários com perfil de Administrador\" poderão acessar a tela de logs básicos do sistema.', '1', '2023-09-13 08:16:01', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `cookies`
--

CREATE TABLE `cookies` (
  `id_cookie` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `nome` varchar(32) NOT NULL,
  `valor` varchar(32) NOT NULL,
  `hostname` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `cnpj` char(19) NOT NULL,
  `razao_social` varchar(100) DEFAULT NULL,
  `nome_fantasia` varchar(100) NOT NULL,
  `logradouro` varchar(100) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `bairro` varchar(100) DEFAULT NULL,
  `cidade` varchar(100) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `cep` char(9) DEFAULT NULL,
  `complemento` varchar(100) DEFAULT NULL,
  `situacao` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Ativo\n1 - Inativo',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `imagens`
--

CREATE TABLE `imagens` (
  `id` int(11) NOT NULL,
  `url_imagem` varchar(600) NOT NULL,
  `created_at` datetime NOT NULL,
  `tipo` tinyint(4) NOT NULL COMMENT '0 - Abrir cancela\n1 - Fechar cancela',
  `tipo_operacao` tinyint(4) DEFAULT NULL COMMENT '0 - Entrada\r\n1 - Saída',
  `operacoes_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `logs`
--

CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `id_classe` int(11) DEFAULT NULL,
  `classe` varchar(20) NOT NULL,
  `acao` tinyint(4) NOT NULL COMMENT '0 - Inserir\n1 - Alterar\n2 - Deletar',
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `operacoes`
--

CREATE TABLE `operacoes` (
  `id` int(11) NOT NULL,
  `hora_abre_cancela_entrada` datetime NOT NULL,
  `hora_fecha_cancela_entrada` datetime DEFAULT NULL,
  `hora_abre_cancela_saida` datetime DEFAULT NULL,
  `hora_fecha_cancela_saida` datetime DEFAULT NULL,
  `peso_entrada` varchar(50) DEFAULT NULL,
  `peso_saida` varchar(50) DEFAULT NULL,
  `usuarios_id` int(11) NOT NULL,
  `veiculos_id` int(11) DEFAULT NULL,
  `pessoas_id` int(11) DEFAULT NULL,
  `portaria_id` int(11) NOT NULL,
  `tipo` char(1) NOT NULL DEFAULT 'N' COMMENT 'N - Normal\r\nE - Emergência',
  `obs_emergencia` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoas`
--

CREATE TABLE `pessoas` (
  `id` int(11) NOT NULL,
  `nome_completo` varchar(200) NOT NULL,
  `cpf` char(14) NOT NULL,
  `rg` char(10) DEFAULT NULL,
  `situacao` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Ativo\n1 - Inativo',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoas_has_veiculos`
--

CREATE TABLE `pessoas_has_veiculos` (
  `pessoas_id` int(11) NOT NULL,
  `veiculos_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `placas`
--

CREATE TABLE `placas` (
  `id` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `endereco_ip` char(15) NOT NULL,
  `porta` int(11) NOT NULL,
  `rele_abre_cancela` char(2) NOT NULL,
  `rele_fecha_cancela` char(2) NOT NULL,
  `situacao` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Ativo\n1 - Inativo',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `portoes`
--

CREATE TABLE `portoes` (
  `id` int(11) NOT NULL,
  `descricao` varchar(200) NOT NULL,
  `situacao` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Ativo\n1 - Inativo',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `placas_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `portoes_pessoas`
--

CREATE TABLE `portoes_pessoas` (
  `id` int(11) NOT NULL,
  `portoes_id` int(11) NOT NULL,
  `usuarios_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `login` varchar(45) NOT NULL,
  `senha` varchar(120) NOT NULL,
  `primeiro_acesso` datetime DEFAULT NULL,
  `ultimo_acesso` datetime DEFAULT NULL,
  `alterar_senha` char(1) NOT NULL DEFAULT 'S',
  `situacao` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Ativo\n1 - Inativo',
  `perfil` varchar(45) NOT NULL,
  `login_error` int(11) DEFAULT 0,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `login`, `senha`, `primeiro_acesso`, `ultimo_acesso`, `alterar_senha`, `situacao`, `perfil`, `login_error`, `created_at`, `updated_at`) VALUES
(1, 'Superadmin', 'superadmin', '$2y$10$tl8BJYryE5S7B9XNXZqTmeFrA.SvbJVAlc4GPB55tgtsTTIMc7XBW', '2023-04-06 06:55:12', '2023-09-07 08:37:51', 'N', 0, 'Superadmin', 0, '2023-03-31 08:39:22', NULL),
(10, 'Administrador', 'administrador', '$2y$10$f9iwfTLcXqD5l2ofn6dgHuuJPsWYPXcNBOw4g21wROH8M/uf5g6Ke', '2023-04-16 08:44:03', '2023-09-02 14:06:04', 'S', 0, 'Administrador', 0, '2023-04-16 08:26:39', '2023-09-07 08:46:10'),
(11, 'Operador', 'operador', '$2y$10$9o76bVc9hzKWgXT4Wf5hvO/cKemv3zQt4xSqgvh0uB8l6QzhOzgLC', '2023-04-16 08:46:05', '2023-09-06 08:32:40', 'S', 0, 'Operador', 0, '2023-04-16 08:45:50', '2023-09-07 08:45:57');

-- --------------------------------------------------------

--
-- Estrutura da tabela `veiculos`
--

CREATE TABLE `veiculos` (
  `id` int(11) NOT NULL,
  `placa` char(8) NOT NULL,
  `descricao` varchar(50) NOT NULL,
  `tipo` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Não Informado\n1 - Carro\n2 - Caminhão\n3 - Moto\n4 - Outro',
  `situacao` tinyint(4) NOT NULL DEFAULT 0 COMMENT '0 - Ativo\n1 - Inativo',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `empresas_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `portaria_ligacao_portaria`
--

CREATE TABLE `portaria_ligacao_portaria` (
  `id` int(11) NOT NULL,
  `portaria_id_1` int(11) NOT NULL,
  `portaria_id_2` int(11) NOT NULL,
  `tipo` int(11) NOT NULL COMMENT '0 - Portaria 1 sai em portaria 2\r\n1 - Portaria 2 sai em portaria 1\r\n2 - Ambas saem em ambas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cameras`
--
ALTER TABLE `cameras`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `camera_has_portaria`
--
ALTER TABLE `camera_has_portaria`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `cookies`
--
ALTER TABLE `cookies`
  ADD PRIMARY KEY (`id_cookie`);

--
-- Índices para tabela `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `imagens`
--
ALTER TABLE `imagens`
  ADD PRIMARY KEY (`id`,`operacoes_id`),
  ADD KEY `fk_imagens_operacoes1_idx` (`operacoes_id`);

--
-- Índices para tabela `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `operacoes`
--
ALTER TABLE `operacoes`
  ADD PRIMARY KEY (`id`) USING BTREE,
  ADD KEY `fk_operacoes_usuarios1_idx` (`usuarios_id`),
  ADD KEY `fk_operacoes_veiculos1_idx` (`veiculos_id`);

--
-- Índices para tabela `pessoas`
--
ALTER TABLE `pessoas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `pessoas_has_veiculos`
--
ALTER TABLE `pessoas_has_veiculos`
  ADD PRIMARY KEY (`pessoas_id`,`veiculos_id`),
  ADD KEY `fk_pessoas_has_veiculos_veiculos1_idx` (`veiculos_id`),
  ADD KEY `fk_pessoas_has_veiculos_pessoas1_idx` (`pessoas_id`);

--
-- Índices para tabela `placas`
--
ALTER TABLE `placas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `portoes`
--
ALTER TABLE `portoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_portoes_placas1_idx` (`placas_id`);

--
-- Índices para tabela `portoes_pessoas`
--
ALTER TABLE `portoes_pessoas`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `veiculos`
--
ALTER TABLE `veiculos`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `portaria_ligacao_portaria`
--
ALTER TABLE `portaria_ligacao_portaria`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `cameras`
--
ALTER TABLE `cameras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `camera_has_portaria`
--
ALTER TABLE `camera_has_portaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de tabela `configuracoes`
--
ALTER TABLE `configuracoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `cookies`
--
ALTER TABLE `cookies`
  MODIFY `id_cookie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `operacoes`
--
ALTER TABLE `operacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pessoas`
--
ALTER TABLE `pessoas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `placas`
--
ALTER TABLE `placas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `portoes`
--
ALTER TABLE `portoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `portoes_pessoas`
--
ALTER TABLE `portoes_pessoas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de tabela `veiculos`
--
ALTER TABLE `veiculos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `portaria_ligacao_portaria`
--
ALTER TABLE `portaria_ligacao_portaria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `imagens`
--
ALTER TABLE `imagens`
  ADD CONSTRAINT `fk_imagens_operacoes1` FOREIGN KEY (`operacoes_id`) REFERENCES `operacoes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `operacoes`
--
ALTER TABLE `operacoes`
  ADD CONSTRAINT `fk_operacoes_usuarios1` FOREIGN KEY (`usuarios_id`) REFERENCES `usuarios` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_operacoes_veiculos1` FOREIGN KEY (`veiculos_id`) REFERENCES `veiculos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pessoas_has_veiculos`
--
ALTER TABLE `pessoas_has_veiculos`
  ADD CONSTRAINT `fk_pessoas_has_veiculos_pessoas1` FOREIGN KEY (`pessoas_id`) REFERENCES `pessoas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pessoas_has_veiculos_veiculos1` FOREIGN KEY (`veiculos_id`) REFERENCES `veiculos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `portoes`
--
ALTER TABLE `portoes`
  ADD CONSTRAINT `fk_portoes_placas1` FOREIGN KEY (`placas_id`) REFERENCES `placas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
