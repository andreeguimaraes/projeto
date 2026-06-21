-- --------------------------------------------------------
-- Anfitrião:                    vsgate-s1.dei.isep.ipp.pt
-- Versão do servidor:           8.0.45 - MySQL Community Server - GPL
-- SO do servidor:               Linux
-- HeidiSQL Versão:              12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- A despejar estrutura da base de dados para db1240722
DROP DATABASE IF EXISTS `db1240722`;
CREATE DATABASE IF NOT EXISTS `db1240722` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `db1240722`;

-- A despejar estrutura para tabela db1240722.Atleta
DROP TABLE IF EXISTS `Atleta`;
CREATE TABLE IF NOT EXISTS `Atleta` (
  `codigo` int NOT NULL,
  `nome` varchar(80) COLLATE utf8mb4_bin NOT NULL,
  `dataNascimento` date NOT NULL,
  `paísOrigem` varchar(4) COLLATE utf8mb4_bin NOT NULL,
  `género` varchar(10) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`codigo`),
  CONSTRAINT `ckAtletagénero ` CHECK ((lower(`género`) in (_utf8mb4'masculino',_utf8mb4'feminino')))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.Atleta: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela db1240722.categorias_equipamento
DROP TABLE IF EXISTS `categorias_equipamento`;
CREATE TABLE IF NOT EXISTS `categorias_equipamento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `descricao` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.categorias_equipamento: ~7 rows (aproximadamente)
INSERT INTO `categorias_equipamento` (`id`, `nome`, `descricao`) VALUES
	(1, 'Monitorização', 'Equipamentos usados para monitorizar sinais vitais e parâmetros clínicos.'),
	(2, 'Suporte de vida', 'Equipamentos essenciais à manutenção das funções vitais.'),
	(3, 'Diagnóstico', 'Equipamentos utilizados para diagnóstico clínico e imagiológico.'),
	(4, 'Terapia', 'Equipamentos utilizados em tratamentos médicos.'),
	(5, 'Laboratório', 'Equipamentos usados em análises laboratoriais.'),
	(6, 'Esterilização', 'Equipamentos usados para limpeza, desinfeção e esterilização.'),
	(7, 'Reabilitação', 'Equipamentos usados em fisioterapia e recuperação funcional.');

-- A despejar estrutura para tabela db1240722.conteudos_site
DROP TABLE IF EXISTS `conteudos_site`;
CREATE TABLE IF NOT EXISTS `conteudos_site` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chave` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `valor` text COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chave` (`chave`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.conteudos_site: ~7 rows (aproximadamente)
INSERT INTO `conteudos_site` (`id`, `chave`, `valor`) VALUES
	(1, 'titulo_principal', 'Sistema de Gestão MEDINV'),
	(5, 'telefone', '+351 912 345 67'),
	(6, 'email', 'geral@medinv.pt'),
	(7, 'morada', 'Rua de António Bernardino, 421, Porto'),
	(8, 'codigo_postal', '4200-002'),
	(9, 'localidade', 'Porto'),
	(10, 'horario', '2ª–6ª: 7h — 21h | Sáb: 9h — 15h | Dom: Encerrado');

-- A despejar estrutura para tabela db1240722.contratos
DROP TABLE IF EXISTS `contratos`;
CREATE TABLE IF NOT EXISTS `contratos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `equipamento_id` int NOT NULL,
  `tipo_id` int NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `entidade_responsavel` varchar(150) COLLATE utf8mb4_bin DEFAULT NULL,
  `periodicidade` enum('mensal','trimestral','semestral','anual') COLLATE utf8mb4_bin DEFAULT NULL,
  `estado` enum('ativo','expirado','cancelado') COLLATE utf8mb4_bin NOT NULL DEFAULT 'ativo',
  `ficheiro_path` varchar(500) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `equipamento_id` (`equipamento_id`),
  KEY `tipo_id` (`tipo_id`),
  CONSTRAINT `contratos_ibfk_1` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `contratos_ibfk_2` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_contrato` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.contratos: ~1 rows (aproximadamente)
INSERT INTO `contratos` (`id`, `codigo`, `equipamento_id`, `tipo_id`, `data_inicio`, `data_fim`, `entidade_responsavel`, `periodicidade`, `estado`, `ficheiro_path`, `observacoes`) VALUES
	(1, 'CON00001', 1, 2, '2026-06-08', '2026-06-25', 'teste', 'semestral', 'ativo', 'uploads/contratos/contrato_6a3517aa04c94.pdf', '');

-- A despejar estrutura para tabela db1240722.documentos
DROP TABLE IF EXISTS `documentos`;
CREATE TABLE IF NOT EXISTS `documentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int DEFAULT NULL,
  `tipo_id` int NOT NULL,
  `nome` varchar(200) COLLATE utf8mb4_bin NOT NULL,
  `data_documento` date DEFAULT NULL,
  `data_validade` date DEFAULT NULL,
  `ficheiro_path` varchar(500) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `equipamento_id` (`equipamento_id`),
  KEY `fornecedor_id` (`fornecedor_id`),
  KEY `tipo_id` (`tipo_id`),
  CONSTRAINT `documentos_ibfk_1` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `documentos_ibfk_2` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`),
  CONSTRAINT `documentos_ibfk_3` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_documento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.documentos: ~1 rows (aproximadamente)
INSERT INTO `documentos` (`id`, `codigo`, `equipamento_id`, `fornecedor_id`, `tipo_id`, `nome`, `data_documento`, `data_validade`, `ficheiro_path`, `observacoes`) VALUES
	(4, 'DOC001-01', 1, NULL, 4, 'teste', '2026-06-15', '2026-06-25', 'uploads/documentos/doc_6a351514c8ba6.pdf', NULL),
	(5, 'DOC001-02', 1, NULL, 6, 'manual', '2026-06-15', '2026-06-27', 'uploads/documentos/doc_6a382b94ee2d9.pdf', NULL);

-- A despejar estrutura para tabela db1240722.equipamentos
DROP TABLE IF EXISTS `equipamentos`;
CREATE TABLE IF NOT EXISTS `equipamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `designacao` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `categoria_id` int NOT NULL,
  `marca` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `modelo` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `numero_serie` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `fabricante` varchar(150) COLLATE utf8mb4_bin DEFAULT NULL,
  `data_aquisicao` date DEFAULT NULL,
  `ano_fabrico` year DEFAULT NULL,
  `custo_aquisicao` decimal(10,2) DEFAULT NULL,
  `tipo_entrada` enum('compra','doacao','aluguer','emprestimo') COLLATE utf8mb4_bin NOT NULL DEFAULT 'compra',
  `estado` enum('ativo','em_manutencao','inativo','em_calibracao','em_quarentena','abatido') COLLATE utf8mb4_bin NOT NULL DEFAULT 'ativo',
  `criticidade` enum('baixa','media','alta','suporte_de_vida') COLLATE utf8mb4_bin NOT NULL,
  `localizacao_id` int NOT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `localizacao_id` (`localizacao_id`),
  KEY `categoria_id` (`categoria_id`),
  CONSTRAINT `equipamentos_ibfk_1` FOREIGN KEY (`localizacao_id`) REFERENCES `localizacoes` (`id`),
  CONSTRAINT `equipamentos_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias_equipamento` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.equipamentos: ~29 rows (aproximadamente)
INSERT INTO `equipamentos` (`id`, `codigo`, `designacao`, `categoria_id`, `marca`, `modelo`, `numero_serie`, `fabricante`, `data_aquisicao`, `ano_fabrico`, `custo_aquisicao`, `tipo_entrada`, `estado`, `criticidade`, `localizacao_id`, `observacoes`) VALUES
	(1, 'EQ001', 'Monitor IntelliVue MP', 1, 'Siemens', 'IntelliVue MP5', 'MP5-2022-45873', 'Philips Healthcare', '2022-03-15', '2022', 12500.00, 'compra', 'inativo', 'suporte_de_vida', 1, 'Equipamento em bom estado.'),
	(2, 'EQ002', 'Ventilador Evita V', 5, 'Dräger', 'Evita', 'EV500-2021-9934', 'Dräger Medical', '2021-06-10', '2020', 35000.00, 'compra', 'inativo', 'baixa', 6, 'Contrato de manutenção ativo.'),
	(3, 'EQ003', 'Desfibrilhador R Series', 2, 'Zoll', 'R Series', 'ZR-2021-7712', 'Zoll Medical', '2021-09-20', '2021', 18000.00, 'compra', 'em_manutencao', 'alta', 2, 'Em manutenção preventiva.'),
	(4, 'EQ004', 'Bomba de Infusão Infusomat', 4, 'B. Braun', 'Infusomat Space', 'INF-2020-88321', 'B. Braun', '2020-11-05', '2020', 4500.00, 'compra', 'ativo', 'media', 4, NULL),
	(5, 'EQ005', 'Oxímetro de Pulso', 1, 'Nonin', 'Model 9590', 'NON-2021-33210', 'Nonin Medical', '2021-02-18', '2021', 800.00, 'compra', 'ativo', 'media', 1, ''),
	(6, 'EQ006', 'Eletrocardiógrafo ECG', 3, 'Schiller', 'Cardiovit AT-2', 'SCH-2020-11204', 'Schiller AG', '2020-07-22', '2020', 6200.00, 'compra', 'ativo', 'alta', 2, ''),
	(7, 'EQ007', 'Autoclave 23L', 6, 'Raypa', 'AE-23', 'RAY-2019-55432', 'Raypa', '2019-04-10', '2019', 3800.00, 'compra', 'ativo', 'media', 3, NULL),
	(8, 'EQ008', 'Ecógrafo Portátil', 3, 'GE Healthcare', 'Vscan Air', 'GE-2022-77891', 'GE Healthcare', '2022-08-30', '2022', 22000.00, 'compra', 'ativo', 'alta', 4, ''),
	(9, 'EQ009', 'Ventilador de Transporte', 2, 'Hamilton', 'Hamilton-T1', 'HAM-2021-44321', 'Hamilton Medical', '2021-12-01', '2021', 28000.00, 'compra', 'ativo', 'suporte_de_vida', 2, NULL),
	(10, 'EQ010', 'Monitor Multiparamétrico', 1, 'Mindray', 'BeneVision N15', 'MIN-2022-99012', 'Mindray', '2022-05-14', '2022', 11000.00, 'compra', 'ativo', 'suporte_de_vida', 1, NULL),
	(11, 'EQ011', 'Bomba de Seringa', 4, 'B. Braun', 'Perfusor Space', 'PER-2020-66543', 'B. Braun', '2020-09-08', '2020', 3200.00, 'compra', 'em_calibracao', 'media', 4, 'Em calibração anual.'),
	(12, 'EQ012', 'Desfibrilhador AED', 2, 'Philips', 'HeartStart FRx', 'AED-2021-22341', 'Philips Healthcare', '2021-03-25', '2021', 2500.00, 'compra', 'ativo', 'suporte_de_vida', 2, NULL),
	(13, 'EQ013', 'Aspirador de Secreções', 4, 'Medela', 'Dominant 50', 'MED-2019-87654', 'Medela', '2019-11-12', '2019', 1800.00, 'compra', 'ativo', 'media', 3, NULL),
	(14, 'EQ014', 'Monitor de Pressão Invasiva', 1, 'Edwards', 'ClearSight', 'EDW-2022-34521', 'Edwards Lifesciences', '2022-01-20', '2022', 15000.00, 'compra', 'ativo', 'suporte_de_vida', 1, NULL),
	(15, 'EQ015', 'Analisador de Gases', 5, 'Radiometer', 'ABL90 FLEX', 'RAD-2021-56789', 'Radiometer', '2021-07-15', '2021', 42000.00, 'compra', 'ativo', 'alta', 3, NULL),
	(16, 'EQ016', 'Cama Articulada Elétrica', 7, 'Stryker', 'InTouch Critical Care', 'STR-2020-12345', 'Stryker', '2020-03-10', '2020', 8500.00, 'compra', 'ativo', 'baixa', 4, NULL),
	(17, 'EQ017', 'Termómetro Timpânico', 3, 'Braun', 'ThermoScan 7', 'BRN-2021-98765', 'Braun', '2021-08-05', '2021', 150.00, 'compra', 'ativo', 'baixa', 5, NULL),
	(18, 'EQ018', 'Esfigmomanómetro Digital', 1, 'Omron', 'HBP-1300', 'OMR-2020-45678', 'Omron Healthcare', '2020-06-18', '2020', 420.00, 'compra', 'inativo', 'baixa', 6, 'Equipamento substituído.'),
	(19, 'EQ019', 'Incubadora Neonatal', 4, 'Dräger', 'Caleo', 'DRG-2022-11122', 'Dräger Medical', '2022-04-22', '2022', 32000.00, 'compra', 'ativo', 'suporte_de_vida', 5, NULL),
	(20, 'EQ020', 'Fotómetro de Bilirrubina', 5, 'Philips', 'BiliCare', 'BIL-2021-33445', 'Philips Healthcare', '2021-10-30', '2021', 5600.00, 'compra', 'ativo', 'media', 5, NULL),
	(21, 'EQ021', 'Laringoscópio Vídeo', 3, 'Karl Storz', 'C-MAC', 'KST-2022-87123', 'Karl Storz', '2022-07-11', '2022', 9800.00, 'compra', 'ativo', 'alta', 3, NULL),
	(22, 'EQ022', 'Centrífuga de Laboratório', 5, 'Eppendorf', '5810R', 'EPP-2020-54321', 'Eppendorf', '2020-02-28', '2020', 7200.00, 'compra', 'ativo', 'media', 3, NULL),
	(23, 'EQ023', 'Cadeira de Rodas Elétrica', 7, 'Invacare', 'TDX SP2', 'INV-2021-76543', 'Invacare', '2021-05-19', '2021', 4200.00, 'compra', 'ativo', 'baixa', 6, NULL),
	(24, 'EQ024', 'Monitor de Glicemia', 3, 'Roche', 'Accu-Chek Inform II', 'ROC-2020-23456', 'Roche Diagnostics', '2020-08-14', '2020', 1200.00, 'compra', 'ativo', 'media', 4, NULL),
	(25, 'EQ025', 'Ventilador Neonatal', 2, 'Dräger', 'Babylog VN500', 'BVN-2022-65432', 'Dräger Medical', '2022-02-08', '2022', 48000.00, 'compra', 'ativo', 'suporte_de_vida', 5, NULL),
	(29, 'EQ026', 'Eletrocardiógrafo Ecg', 6, 'Siemens', 'Evita V', '111-111', NULL, NULL, NULL, NULL, 'compra', 'em_calibracao', 'media', 7, NULL),
	(30, 'EQ027', 'Eletrocardiógrafo Ecg', 6, 'Siemens', 'Evita V', '111-111', NULL, NULL, NULL, NULL, 'compra', 'em_quarentena', 'alta', 5, NULL),
	(31, 'EQ028', 'Monitor De Sinais Vitais', 1, 'Philips', 'IntelliVue MX450', 'MX450-2024-0091', 'Philips Healthcare', '2024-03-15', '2024', 8500.00, 'compra', 'ativo', 'alta', 6, ''),
	(32, 'EQ029', 'Bomba Infusora Teste', 2, 'Siemens', 'Evita', 'MX450-2024-0092', 'Philips', NULL, '2006', NULL, 'compra', 'ativo', 'baixa', 7, NULL);

-- A despejar estrutura para tabela db1240722.equipamento_fornecedor
DROP TABLE IF EXISTS `equipamento_fornecedor`;
CREATE TABLE IF NOT EXISTS `equipamento_fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `tipo_id` int NOT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  KEY `equipamento_id` (`equipamento_id`),
  KEY `fornecedor_id` (`fornecedor_id`),
  KEY `tipo_id` (`tipo_id`),
  CONSTRAINT `equipamento_fornecedor_ibfk_1` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `equipamento_fornecedor_ibfk_2` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`),
  CONSTRAINT `equipamento_fornecedor_ibfk_3` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_fornecedor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.equipamento_fornecedor: ~14 rows (aproximadamente)
INSERT INTO `equipamento_fornecedor` (`id`, `equipamento_id`, `fornecedor_id`, `tipo_id`, `observacoes`) VALUES
	(5, 4, 5, 1, 'Fornecedor associado ao equipamento EQ004'),
	(6, 8, 3, 1, 'Fornecedor associado ao equipamento EQ008'),
	(7, 12, 1, 3, 'Fornecedor associado ao equipamento EQ012'),
	(8, 19, 2, 1, 'Fornecedor associado ao equipamento EQ019'),
	(9, 20, 1, 3, 'Fornecedor associado ao equipamento EQ020'),
	(10, 25, 2, 1, 'Fornecedor associado ao equipamento EQ025'),
	(12, 29, 4, 3, NULL),
	(13, 29, 6, 1, NULL),
	(14, 30, 4, 3, NULL),
	(15, 30, 1, 4, NULL),
	(22, 2, 2, 1, NULL),
	(35, 31, 6, 3, NULL),
	(40, 1, 5, 1, NULL),
	(41, 1, 1, 3, NULL);

-- A despejar estrutura para tabela db1240722.EventoDesportivo
DROP TABLE IF EXISTS `EventoDesportivo`;
CREATE TABLE IF NOT EXISTS `EventoDesportivo` (
  `codigo` int NOT NULL,
  `local` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `data` date NOT NULL,
  PRIMARY KEY (`codigo`),
  UNIQUE KEY `uqEventoDesportivolocaldata` (`local`,`data`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.EventoDesportivo: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela db1240722.fornecedores
DROP TABLE IF EXISTS `fornecedores`;
CREATE TABLE IF NOT EXISTS `fornecedores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `nome` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `nif` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `morada` text COLLATE utf8mb4_bin NOT NULL,
  `website` varchar(255) COLLATE utf8mb4_bin DEFAULT NULL,
  `pessoa_contacto` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `telefone_contacto` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `email_contacto` varchar(150) COLLATE utf8mb4_bin DEFAULT NULL,
  `tipo_id` int NOT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `nif` (`nif`),
  KEY `tipo_id` (`tipo_id`),
  CONSTRAINT `fornecedores_ibfk_1` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_fornecedor` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.fornecedores: ~12 rows (aproximadamente)
INSERT INTO `fornecedores` (`id`, `codigo`, `nome`, `nif`, `telefone`, `email`, `morada`, `website`, `pessoa_contacto`, `telefone_contacto`, `email_contacto`, `tipo_id`, `observacoes`, `ativo`) VALUES
	(1, 'FOR001', 'Philips Healthcare', '123123123', '+351 214 123 666', 'geral@philips-healthcare.pt', 'Av. da República, 90, Porto', 'https://www.philips.pt', 'Ana', '+351 912 111 011', 'ana.martins@philipshealthcare.pt', 3, 'Fabricante de equipamentos de monitorização e diagnóstico', 0),
	(2, 'FOR002', 'Dräger Medical Portugal', '502345678', '+351 218 456 789', 'geral@draeger.pt', 'Rua do Centro Empresarial, 15, Porto', 'https://www.draeger.com', 'João Silva', '+351 912 111 002', 'joao.silva@draeger.pt', 1, 'Fabricante de ventiladores e equipamentos de suporte de vida.', 1),
	(3, 'FOR003', 'GE Healthcare Portugal', '503456789', '+351 213 789 456', 'contacto@gehealthcare.pt', 'Rua das Tecnologias, 22, Lisboa', 'https://www.gehealthcare.com', 'Carla Ferreira', '+351 912 111 003', 'carla.ferreira@gehealthcare.pt', 1, 'Fornecedor de equipamentos de imagiologia e diagnóstico.', 1),
	(4, 'FOR004', 'Siemens Healthineers Portugal', '504567899', '+351 214 987 321', 'info@siemens-healthineers.pt', 'Av. dos Engenheiros, 40, Lisboa', 'https://www.siemens-healthineers.com', 'Miguel Costa', '+351 912 111 004', 'miguel.costa@siemens-healthineers.pt', 1, 'Fornecedor de equipamentos de radiologia e diagnóstico.', 1),
	(5, 'FOR005', 'B. Braun Medical Portugal', '505678901', '+351 229 876 543', 'geral@bbraun.pt', 'Rua da Indústria Médica, 12, Maia', 'https://www.bbraun.pt', 'Sofia Almeida', '+351 912 111 005', 'sofia.almeida@bbraun.pt', 1, 'Fabricante de bombas de infusão e consumíveis hospitalares.', 1),
	(6, 'FOR006', 'Medtronic Portugal', '506789012', '+351 211 234 987', 'geral@medtronic.pt', 'Av. da Saúde, 33, Lisboa', 'https://www.medtronic.com', 'Ricardo Neves', '+351 912 111 006', 'ricardo.neves@medtronic.pt', 2, 'Distribuidor de dispositivos médicos e equipamentos hospitalares.', 1),
	(7, 'FOR007', 'Tecnimede Equipamentos Hospitalares', '507890123', '+351 222 456 789', 'comercial@tecnimede.pt', 'Rua do Hospital, 75, Porto', NULL, 'Patrícia Lopes', '+351 912 111 007', 'patricia.lopes@tecnimede.pt', 2, 'Fornecedor comercial de equipamentos e acessórios médicos.', 1),
	(8, 'FOR008', 'AssistMed Serviços Técnicos', '508901234', '+351 223 654 987', 'assistencia@assistmed.pt', 'Zona Industrial da Maia, Lote 8, Maia', NULL, 'Hugo Pereira', '+351 912 111 008', 'hugo.pereira@assistmed.pt', 3, 'Empresa responsável por assistência técnica e manutenção preventiva.', 1),
	(9, 'FOR009', 'CalibraPlus Engenharia Clínica', '509012345', '+351 226 789 123', 'geral@calibraplus.pt', 'Rua da Engenharia Biomédica, 18, Vila Nova de Gaia', NULL, 'Mariana Rocha', '+351 912 111 009', 'mariana.rocha@calibraplus.pt', 3, 'Serviços de calibração e verificação técnica de equipamentos médicos.', 1),
	(14, 'FOR010', 'Teste', '111111111', '+351914396792', 'ruimisimoes@yahoo.com.ar', 'Rua Das Doze Casas, 219', 'https://www.philips.pt', 'Rui Guimarães', '+351914396792', NULL, 4, NULL, 1),
	(15, 'FOR011', 'Administrador', '123123122', '+351914396792', 'ruimisimoes@yahoo.com.ar', 'Rua Das Doze Casas, 219', 'https://www.philips.pt', 'Rui Guimarães', '+351914396792', 'andre16sg@gmail.com', 2, NULL, 0),
	(16, 'FOR012', 'Administrador', '512311342', '+351914396792', 'ruimisimoes@yahoo.com.ar', 'ELOSPARK II ARMAZEM C-17 ESTRADA DE SÃO MARCOS', NULL, 'IMMERSIVUS, LDA', '+351914396792', NULL, 3, NULL, 0);

-- A despejar estrutura para tabela db1240722.garantias
DROP TABLE IF EXISTS `garantias`;
CREATE TABLE IF NOT EXISTS `garantias` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `equipamento_id` int NOT NULL,
  `tipo_id` int NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `entidade_responsavel` varchar(150) COLLATE utf8mb4_bin DEFAULT NULL,
  `estado` enum('ativa','expirada','cancelada') COLLATE utf8mb4_bin NOT NULL DEFAULT 'ativa',
  `ficheiro_path` varchar(500) COLLATE utf8mb4_bin DEFAULT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `equipamento_id` (`equipamento_id`),
  KEY `tipo_id` (`tipo_id`),
  CONSTRAINT `garantias_ibfk_1` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `garantias_ibfk_2` FOREIGN KEY (`tipo_id`) REFERENCES `tipos_garantia` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.garantias: ~2 rows (aproximadamente)
INSERT INTO `garantias` (`id`, `codigo`, `equipamento_id`, `tipo_id`, `data_inicio`, `data_fim`, `entidade_responsavel`, `estado`, `ficheiro_path`, `observacoes`) VALUES
	(2, 'GAR00001', 1, 2, '2026-06-16', '2026-06-17', 'teste', 'ativa', 'uploads/garantias/garantia_6a3518d62e4ad.pdf', NULL),
	(3, 'GAR002', 31, 1, '2024-03-15', '2026-03-15', 'Philips Healthcare', 'ativa', 'uploads/garantias/GAR_1782042142.pdf', NULL);

-- A despejar estrutura para tabela db1240722.localizacoes
DROP TABLE IF EXISTS `localizacoes`;
CREATE TABLE IF NOT EXISTS `localizacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) COLLATE utf8mb4_bin NOT NULL,
  `edificio` enum('Principal','Bloco B','Bloco C') COLLATE utf8mb4_bin NOT NULL,
  `piso` enum('0','1','2','3') COLLATE utf8mb4_bin NOT NULL,
  `servico_id` int NOT NULL,
  `sala` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `observacoes` text COLLATE utf8mb4_bin,
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  KEY `servico_id` (`servico_id`),
  CONSTRAINT `localizacoes_ibfk_1` FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.localizacoes: ~11 rows (aproximadamente)
INSERT INTO `localizacoes` (`id`, `codigo`, `edificio`, `piso`, `servico_id`, `sala`, `observacoes`, `ativo`) VALUES
	(1, 'LOC001', 'Principal', '1', 2, '103', '', 1),
	(2, 'LOC002', 'Principal', '1', 2, '101', NULL, 0),
	(3, 'LOC003', 'Principal', '3', 3, '301', NULL, 1),
	(4, 'LOC004', 'Principal', '2', 4, '210', NULL, 1),
	(5, 'LOC005', 'Principal', '2', 5, '123', '', 1),
	(6, 'LOC006', 'Bloco B', '2', 6, '121', NULL, 1),
	(7, 'LOC007', 'Principal', '0', 2, '005', NULL, 1),
	(8, 'LOC008', 'Bloco B', '1', 3, 'B101', NULL, 0),
	(9, 'LOC009', 'Bloco B', '2', 4, 'B205', NULL, 1),
	(11, 'LOC010', 'Principal', '1', 5, '218', NULL, 0),
	(13, 'LOC011', 'Bloco B', '2', 5, '202', NULL, 0);

-- A despejar estrutura para tabela db1240722.mensagens_contacto
DROP TABLE IF EXISTS `mensagens_contacto`;
CREATE TABLE IF NOT EXISTS `mensagens_contacto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_bin DEFAULT NULL,
  `assunto` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `mensagem` text COLLATE utf8mb4_bin NOT NULL,
  `data_envio` datetime NOT NULL DEFAULT (now()),
  `lida` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.mensagens_contacto: ~2 rows (aproximadamente)
INSERT INTO `mensagens_contacto` (`id`, `nome`, `email`, `telefone`, `assunto`, `mensagem`, `data_envio`, `lida`) VALUES
	(1, 'Rui Guimarães', 'ruimisimoes@yahoo.com.ar', '914396792', 'parceria', 'teste', '2026-06-19 23:17:41', 0),
	(2, 'Rui Guimarães', 'ruimisimoes@yahoo.com.ar', '914396792', 'parceria', 'teste', '2026-06-19 23:21:15', 0);

-- A despejar estrutura para tabela db1240722.ModalidadePagamento
DROP TABLE IF EXISTS `ModalidadePagamento`;
CREATE TABLE IF NOT EXISTS `ModalidadePagamento` (
  `codModalidadePagamento` decimal(10,0) NOT NULL,
  `descricao` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`codModalidadePagamento`),
  CONSTRAINT `ckTipoDocumentodescricaocheck` CHECK ((char_length(trim(`descricao`)) > 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.ModalidadePagamento: ~7 rows (aproximadamente)
INSERT INTO `ModalidadePagamento` (`codModalidadePagamento`, `descricao`) VALUES
	(1, 'MBWay'),
	(2, 'Transferência Bancária'),
	(3, 'Referência Multibanco'),
	(5, 'Débito em Conta'),
	(6, 'Dinheiro'),
	(9, 'Cheque'),
	(11, 'Cartão Débito');

-- A despejar estrutura para tabela db1240722.Resultado
DROP TABLE IF EXISTS `Resultado`;
CREATE TABLE IF NOT EXISTS `Resultado` (
  `codigo` int NOT NULL,
  `codigoAtleta` int NOT NULL,
  `codigoEvento` int NOT NULL,
  `tempoObtido` time NOT NULL,
  `posicaoProva` int NOT NULL,
  `distancia` varchar(30) COLLATE utf8mb4_bin NOT NULL,
  `classificacaoGeral` int NOT NULL,
  PRIMARY KEY (`codigo`),
  CONSTRAINT `ckResultadoclassificacaoGeral` CHECK ((`classificacaoGeral` > 0)),
  CONSTRAINT `ckResultadodistancia` CHECK ((lower(`distancia`) in (_utf8mb4'100m',_utf8mb4'200m',_utf8mb4'400m',_utf8mb4'800m',_utf8mb4'1500m',_utf8mb4'5000m',_utf8mb4'10000m',_utf8mb4'half-marathon',_utf8mb4'marathon'))),
  CONSTRAINT `ckResultadoposiçãoProva` CHECK ((`posicaoProva` > 0))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.Resultado: ~0 rows (aproximadamente)

-- A despejar estrutura para tabela db1240722.servicos
DROP TABLE IF EXISTS `servicos`;
CREATE TABLE IF NOT EXISTS `servicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `descricao` text COLLATE utf8mb4_bin,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.servicos: ~6 rows (aproximadamente)
INSERT INTO `servicos` (`id`, `nome`, `descricao`) VALUES
	(1, 'Unidade de Cuidados Intensivos (UCI)', NULL),
	(2, 'Urgência', NULL),
	(3, 'Bloco Operatório', NULL),
	(4, 'Medicina', NULL),
	(5, 'Pediatria', NULL),
	(6, 'Ortopedia', NULL);

-- A despejar estrutura para tabela db1240722.Socio
DROP TABLE IF EXISTS `Socio`;
CREATE TABLE IF NOT EXISTS `Socio` (
  `nrSocio` decimal(10,0) NOT NULL,
  `nrIDCivil` decimal(10,0) NOT NULL,
  `codModPagamento` decimal(10,0) DEFAULT NULL,
  `nome` varchar(50) COLLATE utf8mb4_bin NOT NULL,
  `dataInscricao` date NOT NULL,
  `dataAprovacao` date DEFAULT NULL,
  `contactoTelefonico` bigint NOT NULL,
  `enderecoEmail` varchar(60) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`nrSocio`),
  UNIQUE KEY `nrIDCivil` (`nrIDCivil`),
  UNIQUE KEY `enderecoEmail` (`enderecoEmail`),
  KEY `fkSociocodModalidadePag` (`codModPagamento`),
  CONSTRAINT `fkSociocodModalidadePag` FOREIGN KEY (`codModPagamento`) REFERENCES `ModalidadePagamento` (`codModalidadePagamento`),
  CONSTRAINT `ckSocioDataInscricaodataNascimento` CHECK ((`dataInscricao` < `dataAprovacao`)),
  CONSTRAINT `ckSocioenderecoEmail` CHECK (regexp_like(`enderecoEmail`,_utf8mb4'^([[:alnum:]]+.)+@([[:alnum:]]+.)+[[:alpha:]]{2,}$',_utf8mb4'i'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.Socio: ~20 rows (aproximadamente)
INSERT INTO `Socio` (`nrSocio`, `nrIDCivil`, `codModPagamento`, `nome`, `dataInscricao`, `dataAprovacao`, `contactoTelefonico`, `enderecoEmail`) VALUES
	(159837, 7654321, 9, 'Patrícia Sousa', '2023-03-09', '2023-03-15', 351912345678, 'patricia.sousa123@portugalmail.pt'),
	(274896, 87654321, 5, 'Sara Rodrigues', '2023-09-29', '2023-10-01', 351965432109, 'sara.rodrigues_456@mail.pt'),
	(295467, 712345678, 3, 'Francisco Barbosa', '2023-08-25', '2023-08-30', 351918273645, 'f.barbosa_789@sapo.pt'),
	(316725, 654321, 6, 'Diana Ribeiro', '2023-05-15', NULL, 351927364518, 'diana_ribeiro_10@live.pt'),
	(347189, 8901234, 9, 'Catarina Silva', '2023-01-01', '2023-01-07', 351933278192, 'catarina.silva_11@outlook.pt'),
	(423569, 76543210, 3, 'Hugo Pereira', '2023-12-23', '2023-12-27', 351939485726, 'h.pereira_12@gmx.pt'),
	(462198, 12345678, 6, 'Diogo Martins', '2023-11-06', NULL, 351961234567, 'diogomartins_13@icloud.pt'),
	(486532, 8765432, 6, 'Tiago Oliveira', '2023-04-12', '2023-04-17', 351977654321, 'tiago.oliveira.14@me.com'),
	(537810, 9876543, 6, 'Beatriz Dias', '2023-07-07', '2023-07-09', 351960987654, 'beatriz_dias15@yahoo.pt'),
	(580346, 123456789, 5, 'André Almeida', '2023-11-11', '2023-11-13', 351926543210, 'andre.almeida16@aol.pt'),
	(615894, 6543210, 1, 'Miguel Santos', '2023-05-15', '2023-05-21', 447456123456, 'miguel.santos17@icloud.co.uk'),
	(674932, 89012345, 3, 'Ricardo Ferreira', '2023-07-22', '2023-07-25', 447890123456, 'ricardo_ferreira18@live.co.uk'),
	(708524, 3456789, NULL, 'Andreia Carvalho', '2023-12-30', '2024-01-01', 447567890123, 'andreia_carvalho19@yahoo.co.uk'),
	(753219, 234567, 6, 'Marta Costa', '2023-06-19', '2023-06-21', 447789012345, 'marta.costa20@gmail.co.uk'),
	(768321, 1234567, 6, 'Ana Rita Santos', '2023-05-15', '2023-05-19', 447934567890, 'ana.santos_21@outlook.co.uk'),
	(820643, 7890123, NULL, 'Ricardo Fernandes', '2023-03-09', NULL, 351989012345, 'ricardo.fernandes22@icloud.com'),
	(891246, 5678901, 3, 'Carolina Lopes', '2023-10-03', '2023-10-04', 351917890123, 'carolina_lopes23@hotmail.com'),
	(908721, 456789, 5, 'Inês Pereira', '2023-12-13', '2023-12-15', 351910123456, 'ines_pereira24@yahoo.com'),
	(935617, 67890123, 9, 'Filipe Gonçalves', '2023-12-10', '2023-12-15', 351926789012, 'filipe.goncalves_25@gmail.com'),
	(982643, 98765432, 1, 'João Pedro Moreira', '2023-12-17', '2023-12-20', 351929876543, 'joao.moreira26@hotmail.com');

-- A despejar estrutura para tabela db1240722.tipos_contrato
DROP TABLE IF EXISTS `tipos_contrato`;
CREATE TABLE IF NOT EXISTS `tipos_contrato` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.tipos_contrato: ~4 rows (aproximadamente)
INSERT INTO `tipos_contrato` (`id`, `nome`) VALUES
	(2, 'Manutenção corretiva'),
	(1, 'Manutenção preventiva'),
	(3, 'Manutenção total'),
	(4, 'Sem contrato');

-- A despejar estrutura para tabela db1240722.tipos_documento
DROP TABLE IF EXISTS `tipos_documento`;
CREATE TABLE IF NOT EXISTS `tipos_documento` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `tem_validade` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.tipos_documento: ~7 rows (aproximadamente)
INSERT INTO `tipos_documento` (`id`, `nome`, `tem_validade`) VALUES
	(1, 'Manual de utilizador', 0),
	(2, 'Manual de serviço', 0),
	(3, 'Certificado de calibração', 1),
	(4, 'Contrato de manutenção', 1),
	(5, 'Fatura de aquisição', 0),
	(6, 'Declaração de conformidade', 0),
	(7, 'Relatório técnico', 0);

-- A despejar estrutura para tabela db1240722.tipos_fornecedor
DROP TABLE IF EXISTS `tipos_fornecedor`;
CREATE TABLE IF NOT EXISTS `tipos_fornecedor` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.tipos_fornecedor: ~4 rows (aproximadamente)
INSERT INTO `tipos_fornecedor` (`id`, `nome`) VALUES
	(3, 'Assistência técnica'),
	(2, 'Distribuidor / Fornecedor comercial'),
	(1, 'Fabricante'),
	(4, 'Fornecedor de consumíveis');

-- A despejar estrutura para tabela db1240722.tipos_garantia
DROP TABLE IF EXISTS `tipos_garantia`;
CREATE TABLE IF NOT EXISTS `tipos_garantia` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.tipos_garantia: ~3 rows (aproximadamente)
INSERT INTO `tipos_garantia` (`id`, `nome`) VALUES
	(1, 'Garantia do fabricante'),
	(2, 'Garantia estendida'),
	(3, 'Sem garantia');

-- A despejar estrutura para tabela db1240722.utilizadores
DROP TABLE IF EXISTS `utilizadores`;
CREATE TABLE IF NOT EXISTS `utilizadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) COLLATE utf8mb4_bin NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_bin NOT NULL,
  `perfil` enum('administrador','tecnico','profissional_saude') COLLATE utf8mb4_bin NOT NULL DEFAULT 'tecnico',
  `ativo` tinyint(1) NOT NULL DEFAULT '1',
  `password` varchar(255) COLLATE utf8mb4_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- A despejar dados para tabela db1240722.utilizadores: ~3 rows (aproximadamente)
INSERT INTO `utilizadores` (`id`, `nome`, `email`, `perfil`, `ativo`, `password`) VALUES
	(1, 'Joana Silva', 'admin@medinv.pt', 'administrador', 1, '$2y$10$SuXIm74vuC63OeRiP8ido.KYpSJ5GpiHe.gBKGBxbahh5yb.s6bsi'),
	(2, 'Carlos Mendes', 'tecnico@medinv.pt', 'tecnico', 1, '$2y$10$TXnPKzqEXeFiOl8qmjzse.WhSt59f5Tu61rKkjJI9zBLyhjGsWmlW'),
	(3, 'Rita Costa', 'saude@medinv.pt', 'profissional_saude', 1, '$2y$10$lUN1UYbiFL4sTJ7zX85pY.tdTZxQnZZZ/JQneKmvYOz1SVWzFVbtq');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
