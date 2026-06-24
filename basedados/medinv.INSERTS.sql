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

-- A despejar dados para tabela db1240722.categorias_equipamento: ~7 rows (aproximadamente)
INSERT INTO `categorias_equipamento` (`id`, `nome`, `descricao`) VALUES
	(1, 'Monitorização', 'Equipamentos usados para monitorizar sinais vitais e parâmetros clínicos.'),
	(2, 'Suporte de vida', 'Equipamentos essenciais à manutenção das funções vitais.'),
	(3, 'Diagnóstico', 'Equipamentos utilizados para diagnóstico clínico e imagiológico.'),
	(4, 'Terapia', 'Equipamentos utilizados em tratamentos médicos.'),
	(5, 'Laboratório', 'Equipamentos usados em análises laboratoriais.'),
	(6, 'Esterilização', 'Equipamentos usados para limpeza, desinfeção e esterilização.'),
	(7, 'Reabilitação', 'Equipamentos usados em fisioterapia e recuperação funcional.');

-- A despejar dados para tabela db1240722.conteudos_site: ~8 rows (aproximadamente)
INSERT INTO `conteudos_site` (`id`, `chave`, `valor`) VALUES
	(1, 'titulo_principal', 'Sistema de Gestão MEDINV'),
	(5, 'telefone', '912345671'),
	(6, 'email', 'geral@medinv.pt'),
	(7, 'morada', 'Rua de António Bernardino, 421, Porto'),
	(8, 'codigo_postal', '4200-002'),
	(9, 'localidade', 'Porto'),
	(10, 'horario', '2ª–6ª: 7h — 21h | Sáb: 9h — 15h | Dom: Encerrado');

-- A despejar dados para tabela db1240722.contratos: ~0 rows (aproximadamente)
INSERT INTO `contratos` (`id`, `codigo`, `equipamento_id`, `tipo_id`, `data_inicio`, `data_fim`, `entidade_responsavel`, `periodicidade`, `estado`, `ficheiro_path`, `observacoes`) VALUES
	(1, 'CON00001', 1, 2, '2026-06-08', '2026-06-25', 'teste', 'semestral', 'ativo', 'uploads/contratos/contrato_6a3517aa04c94.pdf', '');

-- A despejar dados para tabela db1240722.documentos: ~2 rows (aproximadamente)
INSERT INTO `documentos` (`id`, `codigo`, `equipamento_id`, `fornecedor_id`, `tipo_id`, `nome`, `data_documento`, `data_validade`, `ficheiro_path`, `observacoes`) VALUES
	(4, 'DOC001-01', 1, NULL, 4, 'teste', '2026-06-15', '2026-06-25', 'uploads/documentos/doc_6a351514c8ba6.pdf', NULL),
	(5, 'DOC001-02', 1, NULL, 6, 'manual', '2026-06-15', '2026-06-27', 'uploads/documentos/doc_6a382b94ee2d9.pdf', NULL);

-- A despejar dados para tabela db1240722.equipamentos: ~28 rows (aproximadamente)
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

-- A despejar dados para tabela db1240722.garantias: ~1 rows (aproximadamente)
INSERT INTO `garantias` (`id`, `codigo`, `equipamento_id`, `tipo_id`, `data_inicio`, `data_fim`, `entidade_responsavel`, `estado`, `ficheiro_path`, `observacoes`) VALUES
	(2, 'GAR00001', 1, 2, '2026-06-16', '2026-06-17', 'teste', 'ativa', 'uploads/garantias/garantia_6a3518d62e4ad.pdf', NULL),
	(3, 'GAR002', 31, 1, '2024-03-15', '2026-03-15', 'Philips Healthcare', 'ativa', 'uploads/garantias/GAR_1782042142.pdf', NULL);

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

-- A despejar dados para tabela db1240722.logs: ~9 rows (aproximadamente)
INSERT INTO `logs` (`id`, `data_hora`, `utilizador_id`, `utilizador_email`, `acao`, `modulo`, `descricao`, `ip`, `sucesso`) VALUES
	(1, '2026-06-22 09:15:26', NULL, NULL, 'login_falhado', 'autenticacao', 'Tentativa de login falhada para o email: admin@medind.pt', '127.0.0.1', 0),
	(2, '2026-06-22 09:15:33', NULL, NULL, 'login', 'autenticacao', 'Login bem-sucedido: admin@medinv.pt (perfil: administrador)', '127.0.0.1', 1),
	(3, '2026-06-22 09:20:40', 1, 'admin@medinv.pt', 'logout', 'autenticacao', 'Sessão terminada: admin@medinv.pt', '127.0.0.1', 1),
	(4, '2026-06-22 09:32:01', 1, 'admin@medinv.pt', 'login', 'autenticacao', 'Login bem-sucedido: admin@medinv.pt (perfil: administrador)', '127.0.0.1', 1),
	(5, '2026-06-22 09:46:12', 1, 'admin@medinv.pt', 'logout', 'autenticacao', 'Sessão terminada: admin@medinv.pt', '127.0.0.1', 1),
	(6, '2026-06-22 09:46:18', 3, 'saude@medinv.pt', 'login', 'autenticacao', 'Login bem-sucedido: saude@medinv.pt (perfil: profissional_saude)', '127.0.0.1', 1),
	(7, '2026-06-22 09:46:31', 3, 'saude@medinv.pt', 'login', 'autenticacao', 'Login bem-sucedido: saude@medinv.pt (perfil: profissional_saude)', '127.0.0.1', 1),
	(8, '2026-06-22 11:01:06', 3, 'saude@medinv.pt', 'logout', 'autenticacao', 'Sessão terminada: saude@medinv.pt', '127.0.0.1', 1),
	(9, '2026-06-22 11:01:14', 1, 'admin@medinv.pt', 'login', 'autenticacao', 'Login bem-sucedido: admin@medinv.pt (perfil: administrador)', '127.0.0.1', 1),
	(10, '2026-06-23 06:43:53', 1, 'admin@medinv.pt', 'login', 'autenticacao', 'Login bem-sucedido: admin@medinv.pt (perfil: administrador)', '127.0.0.1', 1);

-- A despejar dados para tabela db1240722.mensagens_contacto: ~2 rows (aproximadamente)
INSERT INTO `mensagens_contacto` (`id`, `nome`, `email`, `telefone`, `assunto`, `mensagem`, `data_envio`, `lida`) VALUES
	(1, 'Rui Guimarães', 'ruimisimoes@yahoo.com.ar', '914396792', 'parceria', 'teste', '2026-06-19 23:17:41', 0),
	(2, 'Rui Guimarães', 'ruimisimoes@yahoo.com.ar', '914396792', 'parceria', 'teste', '2026-06-19 23:21:15', 0);

-- A despejar dados para tabela db1240722.servicos: ~6 rows (aproximadamente)
INSERT INTO `servicos` (`id`, `nome`, `descricao`) VALUES
	(1, 'Unidade de Cuidados Intensivos (UCI)', NULL),
	(2, 'Urgência', NULL),
	(3, 'Bloco Operatório', NULL),
	(4, 'Medicina', NULL),
	(5, 'Pediatria', NULL),
	(6, 'Ortopedia', NULL);

-- A despejar dados para tabela db1240722.tipos_contrato: ~4 rows (aproximadamente)
INSERT INTO `tipos_contrato` (`id`, `nome`) VALUES
	(2, 'Manutenção corretiva'),
	(1, 'Manutenção preventiva'),
	(3, 'Manutenção total'),
	(4, 'Sem contrato');

-- A despejar dados para tabela db1240722.tipos_documento: ~7 rows (aproximadamente)
INSERT INTO `tipos_documento` (`id`, `nome`, `tem_validade`) VALUES
	(1, 'Manual de utilizador', 0),
	(2, 'Manual de serviço', 0),
	(3, 'Certificado de calibração', 1),
	(4, 'Contrato de manutenção', 1),
	(5, 'Fatura de aquisição', 0),
	(6, 'Declaração de conformidade', 0),
	(7, 'Relatório técnico', 0);

-- A despejar dados para tabela db1240722.tipos_fornecedor: ~4 rows (aproximadamente)
INSERT INTO `tipos_fornecedor` (`id`, `nome`) VALUES
	(3, 'Assistência técnica'),
	(2, 'Distribuidor / Fornecedor comercial'),
	(1, 'Fabricante'),
	(4, 'Fornecedor de consumíveis');

-- A despejar dados para tabela db1240722.tipos_garantia: ~3 rows (aproximadamente)
INSERT INTO `tipos_garantia` (`id`, `nome`) VALUES
	(1, 'Garantia do fabricante'),
	(2, 'Garantia estendida'),
	(3, 'Sem garantia');

-- A despejar dados para tabela db1240722.utilizadores: ~2 rows (aproximadamente)
INSERT INTO `utilizadores` (`id`, `nome`, `email`, `perfil`, `ativo`, `password`) VALUES
	(1, 'Joana Silva', 'admin@medinv.pt', 'administrador', 1, '$2y$10$SuXIm74vuC63OeRiP8ido.KYpSJ5GpiHe.gBKGBxbahh5yb.s6bsi'),
	(2, 'Carlos Mendes', 'tecnico@medinv.pt', 'tecnico', 1, '$2y$10$TXnPKzqEXeFiOl8qmjzse.WhSt59f5Tu61rKkjJI9zBLyhjGsWmlW'),
	(3, 'Rita Costa', 'saude@medinv.pt', 'profissional_saude', 1, '$2y$10$lUN1UYbiFL4sTJ7zX85pY.tdTZxQnZZZ/JQneKmvYOz1SVWzFVbtq');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
