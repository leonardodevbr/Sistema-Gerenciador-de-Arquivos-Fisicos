SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE DATABASE IF NOT EXISTS `arquivo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `arquivo`;

CREATE TABLE IF NOT EXISTS `caso` (
  `id_caso` int(11) NOT NULL AUTO_INCREMENT,
  `descricao_caso` text,
  `cliente_id_cliente` int(11) NOT NULL,
  `num_caso` int(11) NOT NULL DEFAULT '0',
  `id_usuario_logado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_caso`,`cliente_id_cliente`),
  UNIQUE KEY `id_caso_UNIQUE` (`id_caso`),
  KEY `fk_caso_cliente1_idx` (`cliente_id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DELIMITER $$
CREATE TRIGGER `depois_de_atualzar_caso` AFTER UPDATE ON `caso` FOR EACH ROW BEGIN
  INSERT
INTO
  log
SET
  id_usuario_log = NEW.id_usuario_logado,
  data_hora_log = NOW(),
  acao_log = 'Atualização de Caso';
  END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `depois_de_inserir_caso` AFTER INSERT ON `caso` FOR EACH ROW BEGIN
  INSERT
INTO
  log
SET
  id_usuario_log = NEW.id_usuario_logado,
  data_hora_log = NOW(),
  acao_log = 'Cadastro de Caso';
  END
$$
DELIMITER ;

CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nome_cliente` varchar(150) NOT NULL,
  `cod_cliente` varchar(100) DEFAULT NULL,
  `ativo_cliente` char(1) NOT NULL DEFAULT '1',
  `id_usuario_logado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `id_cliente_UNIQUE` (`id_cliente`),
  UNIQUE KEY `cod_cliente_UNIQUE` (`cod_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DELIMITER $$
CREATE TRIGGER `depois_de_atualzar_cliente` AFTER UPDATE ON `cliente` FOR EACH ROW BEGIN
  INSERT
INTO
  log
SET
  id_usuario_log = NEW.id_usuario_logado,
  data_hora_log = NOW(),
  acao_log = 'Atualização de Cliente';
  END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `depois_de_inserir_cliente` AFTER INSERT ON `cliente` FOR EACH ROW BEGIN
  INSERT
INTO
  log
SET
  id_usuario_log = NEW.id_usuario_logado,
  data_hora_log = NOW(),
  acao_log = 'Cadastro de Cliente';
  END
$$
DELIMITER ;

CREATE TABLE IF NOT EXISTS `documento` (
  `id_documento` int(11) NOT NULL AUTO_INCREMENT,
  `descricao_documento` text NOT NULL,
  `cod_documento` varchar(100) DEFAULT NULL,
  `obs_documento` text,
  `status_documento` char(1) NOT NULL DEFAULT '1',
  `num_pasta_documento` int(11) DEFAULT NULL,
  `processo_documento` text,
  `alocado` char(1) DEFAULT NULL,
  `caso_id_caso` int(11) NOT NULL,
  `caso_cliente_id_cliente` int(11) NOT NULL,
  `solicitado` char(1) DEFAULT '0',
  `id_usuario_logado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_documento`,`caso_id_caso`,`caso_cliente_id_cliente`),
  UNIQUE KEY `id_documento_UNIQUE` (`id_documento`),
  KEY `fk_documento_caso1_idx` (`caso_id_caso`,`caso_cliente_id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DELIMITER $$
CREATE TRIGGER `depois_de_atualzar_documento` AFTER UPDATE ON `documento` FOR EACH ROW BEGIN
  INSERT
INTO
  log
SET
  id_usuario_log = NEW.id_usuario_logado,
  data_hora_log = NOW(),
  acao_log = 'Atualização de Documento';
  END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `depois_de_inserir_documento` AFTER INSERT ON `documento` FOR EACH ROW BEGIN
  INSERT
INTO
  log
SET
  id_usuario_log = NEW.id_usuario_logado,
  data_hora_log = NOW(),
  acao_log = 'Cadastro de Documento';
  END
$$
DELIMITER ;

CREATE TABLE IF NOT EXISTS `emprestimo` (
  `id_emprestimo` int(11) NOT NULL AUTO_INCREMENT,
  `status_emprestimo` char(1) DEFAULT '0',
  `usuario_id_usuario` int(11) NOT NULL,
  `solicitacao_id_solicitacao` int(11) NOT NULL,
  PRIMARY KEY (`id_emprestimo`,`usuario_id_usuario`,`solicitacao_id_solicitacao`),
  UNIQUE KEY `idemprestimo_UNIQUE` (`id_emprestimo`),
  KEY `fk_emprestimo_usuario1_idx` (`usuario_id_usuario`),
  KEY `fk_emprestimo_solicitacao1_idx` (`solicitacao_id_solicitacao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `etiqueta` (
  `id_etiqueta` int(11) NOT NULL AUTO_INCREMENT,
  `html_etiqueta` text NOT NULL,
  `impressa` char(1) DEFAULT '0',
  `localizacao_id_localizacao` int(11) NOT NULL,
  `localizacao_cliente_id_cliente` int(11) NOT NULL,
  `tipo_etiqueta` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`id_etiqueta`,`localizacao_id_localizacao`,`localizacao_cliente_id_cliente`),
  UNIQUE KEY `idetiqueta_UNIQUE` (`id_etiqueta`),
  KEY `fk_etiqueta_localizacao1_idx` (`localizacao_id_localizacao`,`localizacao_cliente_id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `localizacao` (
  `id_localizacao` int(11) NOT NULL AUTO_INCREMENT,
  `num_localizacao` int(11) NOT NULL,
  `cliente_id_cliente` int(11) NOT NULL,
  `caso_id_caso` int(11) NOT NULL,
  `etiquetada` char(1) DEFAULT '0',
  `tipo_localizacao` varchar(5) DEFAULT NULL,
  `prateleira_id_prateleira` int(11) NOT NULL,
  `status_localizacao` char(1) DEFAULT '1',
  PRIMARY KEY (`id_localizacao`,`cliente_id_cliente`,`prateleira_id_prateleira`),
  UNIQUE KEY `id_caixa_UNIQUE` (`id_localizacao`),
  KEY `fk_localizacao_cliente1_idx` (`cliente_id_cliente`),
  KEY `fk_localizacao_prateleira1_idx` (`prateleira_id_prateleira`),
  KEY `caso_id_caso` (`caso_id_caso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `localizacao_has_documento` (
  `localizacao_id_localizacao` int(11) NOT NULL,
  `documento_id_documento` int(11) NOT NULL,
  PRIMARY KEY (`localizacao_id_localizacao`,`documento_id_documento`),
  KEY `fk_localizacao_has_documento_documento1_idx` (`documento_id_documento`),
  KEY `fk_localizacao_has_documento_localizacao1_idx` (`localizacao_id_localizacao`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `log` (
  `id_log` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario_log` int(11) NOT NULL,
  `data_hora_log` datetime NOT NULL,
  `acao_log` varchar(50) NOT NULL,
  PRIMARY KEY (`id_log`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `prateleira` (
  `id_prateleira` int(11) NOT NULL AUTO_INCREMENT,
  `num_prateleira` int(11) NOT NULL,
  `localizacoes_prateleira` int(11) DEFAULT '0',
  `id_usuario_logado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_prateleira`),
  UNIQUE KEY `idprateleira_UNIQUE` (`id_prateleira`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DELIMITER $$
CREATE TRIGGER `depois_de_atualzar_prateleira` AFTER UPDATE ON `prateleira` FOR EACH ROW BEGIN
  INSERT
INTO
  log
SET
  id_usuario_log = NEW.id_usuario_logado,
  data_hora_log = NOW(),
  acao_log = 'Atualização de Prateleira';
  END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `depois_de_inserir_prateleira` AFTER INSERT ON `prateleira` FOR EACH ROW BEGIN
  INSERT
INTO
  log
SET
  id_usuario_log = NEW.id_usuario_logado,
  data_hora_log = NOW(),
  acao_log = 'Cadastro de Prateleira';
  END
$$
DELIMITER ;

CREATE TABLE IF NOT EXISTS `solicitacao` (
  `id_solicitacao` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id_usuario` int(11) NOT NULL,
  `status_solicitacao` char(1) DEFAULT '0',
  `notificado` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id_solicitacao`,`usuario_id_usuario`),
  UNIQUE KEY `idsolicitacao_UNIQUE` (`id_solicitacao`),
  KEY `fk_solicitacao_usuario1_idx` (`usuario_id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `solicitacao_has_documento` (
  `solicitacao_id_solicitacao` int(11) NOT NULL,
  `usuario_id_usuario` int(11) NOT NULL,
  `documento_id_documento` int(11) NOT NULL,
  PRIMARY KEY (`solicitacao_id_solicitacao`,`usuario_id_usuario`,`documento_id_documento`),
  KEY `fk_solicitacao_has_documento_documento1_idx` (`documento_id_documento`),
  KEY `fk_solicitacao_has_documento_solicitacao1_idx` (`solicitacao_id_solicitacao`,`usuario_id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int(11) NOT NULL AUTO_INCREMENT,
  `nome_usuario` varchar(255) DEFAULT NULL,
  `email_usuario` varchar(100) DEFAULT NULL,
  `username_usuario` varchar(45) NOT NULL,
  `password_usuario` varchar(40) NOT NULL,
  `tipo_usuario` char(1) NOT NULL DEFAULT '3',
  `recebeEmail` int(11) NOT NULL DEFAULT '0',
  `status_usuario` char(1) NOT NULL DEFAULT '1',
  `alerta_usuario` int(11) DEFAULT '0',
  `atualizar_senha` tinyint(1) NOT NULL DEFAULT '0',
  `online_usuario` char(1) NOT NULL DEFAULT '0',
  `id_usuario_logado` int(11) NOT NULL DEFAULT '0',
  `codigo_redefinicao` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`),
  UNIQUE KEY `id_usuario_UNIQUE` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DELIMITER $$
CREATE TRIGGER `depois_de_atualzar_usuario` AFTER UPDATE ON `usuario` FOR EACH ROW BEGIN
  INSERT
INTO
  log
SET
  id_usuario_log = NEW.id_usuario_logado,
  data_hora_log = NOW(),
  acao_log = 'Atualização de Usuário';
  END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `depois_de_inserir_usuario` AFTER INSERT ON `usuario` FOR EACH ROW BEGIN
  INSERT
INTO
  log
SET
  id_usuario_log = NEW.id_usuario_logado,
  data_hora_log = NOW(),
  acao_log = 'Cadastro de Usuário';
  END
$$
DELIMITER ;
CREATE TABLE IF NOT EXISTS `vw_dados` (
`dados` mediumtext
,`id_doc` int(11)
,`id_cli` int(11)
);
DROP TABLE IF EXISTS `vw_dados`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_dados`  AS  select concat_ws(';',`c`.`cod_cliente`,`d`.`cod_documento`,`c`.`nome_cliente`,`d`.`descricao_documento`,`d`.`processo_documento`,`d`.`obs_documento`,`cs`.`descricao_caso`,`cs`.`num_caso`) AS `dados`,`d`.`id_documento` AS `id_doc`,`c`.`id_cliente` AS `id_cli` from ((`documento` `d` join `cliente` `c` on((`d`.`caso_cliente_id_cliente` = `c`.`id_cliente`))) join `caso` `cs` on((`cs`.`cliente_id_cliente` = `c`.`id_cliente`))) order by `c`.`nome_cliente` ;


ALTER TABLE `caso`
  ADD CONSTRAINT `fk_caso_cliente1` FOREIGN KEY (`cliente_id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `documento`
  ADD CONSTRAINT `fk_documento_caso1` FOREIGN KEY (`caso_id_caso`,`caso_cliente_id_cliente`) REFERENCES `caso` (`id_caso`, `cliente_id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `emprestimo`
  ADD CONSTRAINT `fk_emprestimo_solicitacao1` FOREIGN KEY (`solicitacao_id_solicitacao`) REFERENCES `solicitacao` (`id_solicitacao`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_emprestimo_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `etiqueta`
  ADD CONSTRAINT `fk_etiqueta_localizacao1` FOREIGN KEY (`localizacao_id_localizacao`,`localizacao_cliente_id_cliente`) REFERENCES `localizacao` (`id_localizacao`, `cliente_id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `localizacao`
  ADD CONSTRAINT `fk_localizacao_cliente1` FOREIGN KEY (`cliente_id_cliente`) REFERENCES `cliente` (`id_cliente`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_localizacao_prateleira1` FOREIGN KEY (`prateleira_id_prateleira`) REFERENCES `prateleira` (`id_prateleira`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `localizacao_ibfk_1` FOREIGN KEY (`caso_id_caso`) REFERENCES `caso` (`id_caso`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `localizacao_has_documento`
  ADD CONSTRAINT `fk_localizacao_has_documento_documento1` FOREIGN KEY (`documento_id_documento`) REFERENCES `documento` (`id_documento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_localizacao_has_documento_localizacao1` FOREIGN KEY (`localizacao_id_localizacao`) REFERENCES `localizacao` (`id_localizacao`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `solicitacao`
  ADD CONSTRAINT `fk_solicitacao_usuario1` FOREIGN KEY (`usuario_id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `solicitacao_has_documento`
  ADD CONSTRAINT `fk_solicitacao_has_documento_documento1` FOREIGN KEY (`documento_id_documento`) REFERENCES `documento` (`id_documento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_solicitacao_has_documento_solicitacao1` FOREIGN KEY (`solicitacao_id_solicitacao`,`usuario_id_usuario`) REFERENCES `solicitacao` (`id_solicitacao`, `usuario_id_usuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;
SET FOREIGN_KEY_CHECKS=1;
