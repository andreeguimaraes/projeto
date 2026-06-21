CREATE TABLE `utilizadores` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) UNIQUE NOT NULL,
  `perfil` enum(administrador,tecnico,profissional_saude) NOT NULL DEFAULT 'tecnico',
  `ativo` boolean NOT NULL DEFAULT true,
  `password` varchar(255) NOT NULL
);

CREATE TABLE `localizacoes` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `codigo` varchar(20) UNIQUE NOT NULL,
  `edificio` enum(Principal,Bloco B,Bloco C) NOT NULL,
  `piso` enum(0,1,2,3) NOT NULL,
  `servico_id` int NOT NULL,
  `sala` varchar(50) NOT NULL,
  `observacoes` text,
  `ativo` boolean NOT NULL DEFAULT true
);

CREATE TABLE `fornecedores` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `codigo` varchar(20) UNIQUE NOT NULL,
  `nome` varchar(150) NOT NULL,
  `nif` varchar(20) UNIQUE NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(150) NOT NULL,
  `morada` text NOT NULL,
  `website` varchar(255),
  `pessoa_contacto` varchar(100) NOT NULL,
  `telefone_contacto` varchar(20) NOT NULL,
  `email_contacto` varchar(150),
  `tipo_id` int NOT NULL,
  `observacoes` text,
  `ativo` boolean NOT NULL DEFAULT true
);

CREATE TABLE `equipamentos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `codigo` varchar(50) UNIQUE NOT NULL,
  `designacao` varchar(150) NOT NULL,
  `categoria_id` int NOT NULL,
  `marca` varchar(100) NOT NULL,
  `modelo` varchar(100) NOT NULL,
  `numero_serie` varchar(100) NOT NULL,
  `fabricante` varchar(150),
  `data_aquisicao` date,
  `ano_fabrico` year,
  `custo_aquisicao` decimal(10,2),
  `tipo_entrada` enum(compra,doacao,aluguer,emprestimo) NOT NULL DEFAULT 'compra',
  `estado` enum(ativo,em_manutencao,inativo,em_calibracao,em_quarentena,abatido) NOT NULL DEFAULT 'ativo',
  `criticidade` enum(baixa,media,alta,suporte_de_vida) NOT NULL,
  `localizacao_id` int NOT NULL,
  `observacoes` text
);

CREATE TABLE `equipamento_fornecedor` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int NOT NULL,
  `tipo_id` int NOT NULL,
  `observacoes` text
);

CREATE TABLE `documentos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `codigo` varchar(20) UNIQUE NOT NULL,
  `equipamento_id` int NOT NULL,
  `fornecedor_id` int,
  `tipo_id` int NOT NULL,
  `nome` varchar(200) NOT NULL,
  `data_documento` date,
  `data_validade` date,
  `ficheiro_path` varchar(500),
  `observacoes` text
);

CREATE TABLE `garantias` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `codigo` varchar(20) UNIQUE NOT NULL,
  `equipamento_id` int NOT NULL,
  `tipo_id` int NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `entidade_responsavel` varchar(150),
  `estado` enum(ativa,expirada,cancelada) NOT NULL DEFAULT 'ativa',
  `ficheiro_path` varchar(500),
  `observacoes` text
);

CREATE TABLE `contratos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `codigo` varchar(20) UNIQUE NOT NULL,
  `equipamento_id` int NOT NULL,
  `tipo_id` int NOT NULL,
  `data_inicio` date NOT NULL,
  `data_fim` date NOT NULL,
  `entidade_responsavel` varchar(150),
  `periodicidade` enum(mensal,trimestral,semestral,anual),
  `estado` enum(ativo,expirado,cancelado) NOT NULL DEFAULT 'ativo',
  `ficheiro_path` varchar(500),
  `observacoes` text
);

CREATE TABLE `conteudos_site` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `chave` varchar(100) UNIQUE NOT NULL,
  `valor` text NOT NULL
);

CREATE TABLE `mensagens_contacto` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telefone` varchar(20),
  `assunto` varchar(100) NOT NULL,
  `mensagem` text NOT NULL,
  `data_envio` datetime NOT NULL DEFAULT (now()),
  `lida` boolean NOT NULL DEFAULT false
);

CREATE TABLE `tipos_documento` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) UNIQUE NOT NULL,
  `tem_validade` boolean NOT NULL
);

CREATE TABLE `servicos` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) UNIQUE NOT NULL,
  `descricao` text
);

CREATE TABLE `tipos_garantia` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) UNIQUE NOT NULL
);

CREATE TABLE `tipos_contrato` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) UNIQUE NOT NULL
);

CREATE TABLE `tipos_fornecedor` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) UNIQUE NOT NULL
);

CREATE TABLE `categorias_equipamento` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `nome` varchar(100) UNIQUE NOT NULL,
  `descricao` text
);

ALTER TABLE `localizacoes` ADD FOREIGN KEY (`servico_id`) REFERENCES `servicos` (`id`);

ALTER TABLE `equipamentos` ADD FOREIGN KEY (`localizacao_id`) REFERENCES `localizacoes` (`id`);

ALTER TABLE `equipamentos` ADD FOREIGN KEY (`categoria_id`) REFERENCES `categorias_equipamento` (`id`);

ALTER TABLE `equipamento_fornecedor` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `equipamento_fornecedor` ADD FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`);

ALTER TABLE `equipamento_fornecedor` ADD FOREIGN KEY (`tipo_id`) REFERENCES `tipos_fornecedor` (`id`);

ALTER TABLE `documentos` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `documentos` ADD FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedores` (`id`);

ALTER TABLE `documentos` ADD FOREIGN KEY (`tipo_id`) REFERENCES `tipos_documento` (`id`);

ALTER TABLE `garantias` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `garantias` ADD FOREIGN KEY (`tipo_id`) REFERENCES `tipos_garantia` (`id`);

ALTER TABLE `contratos` ADD FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`);

ALTER TABLE `contratos` ADD FOREIGN KEY (`tipo_id`) REFERENCES `tipos_contrato` (`id`);

ALTER TABLE `fornecedores` ADD FOREIGN KEY (`tipo_id`) REFERENCES `tipos_fornecedor` (`id`);
