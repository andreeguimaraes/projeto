<?php
// ============================================================
// Validações gerais
// ============================================================

// Valida um nome genérico: obrigatório e sem dígitos.
// Nota: atualmente não é usada em nenhum formulário do projeto
// (os formulários usam validações mais específicas, ex:
// validar_nome_empresa(), validar_nome_contacto()), mas fica
// disponível caso seja precisa no futuro.
function validar_nome(string $nome): array {
    $erros = [];
    if (empty(trim($nome))) {
        $erros[] = "O campo Nome é obrigatório.";
    } elseif (preg_match('/\d/', $nome)) {
        $erros[] = "O campo Nome não pode conter números.";
    }
    return $erros;
}

// ============================================================
// EQUIPAMENTOS
// ============================================================

// Valida o código interno do equipamento.
// Regras: obrigatório, tem de seguir o formato "EQ" + pelo menos
// 3 dígitos (ex: EQ001), e não pode exceder 50 caracteres.
function validar_codigo(string $codigo): array {
    $erros = [];
    if (empty($codigo)) {
        $erros[] = "O código interno é obrigatório.";
    } elseif (!preg_match('/^EQ\d{3,}$/', $codigo)) {
        $erros[] = "O código interno deve começar por 'EQ' seguido de pelo menos 3 dígitos (ex: EQ001).";
    } elseif (strlen($codigo) > 50) {
        $erros[] = "O código interno não pode ter mais de 50 caracteres.";
    }
    return $erros;
}

// Valida a designação (nome) do equipamento.
// Regras: obrigatória, entre 3 e 150 caracteres, e tem de conter
// pelo menos uma letra (evita designações só com números/símbolos).
function validar_designacao(string $designacao): array {
    $erros = [];
    if (empty($designacao)) {
        $erros[] = "A designação é obrigatória.";
    } elseif (strlen($designacao) < 3) {
        $erros[] = "A designação deve ter pelo menos 3 caracteres.";
    } elseif (!preg_match('/[a-zA-ZÀ-ÿ]/', $designacao)) {
        $erros[] = "A designação deve conter pelo menos uma letra.";
    } elseif (strlen($designacao) > 150) {
        $erros[] = "A designação não pode ter mais de 150 caracteres.";
    }
    return $erros;
}

// Valida o ID da categoria do equipamento (usado em editar-equipamentos.php,
// onde a categoria é escolhida por ID numérico vindo de um <select>).
// Regras: obrigatória e tem de ser um número inteiro positivo.
function validar_categoria(string $categoria): array {
    $erros = [];
    if (empty($categoria)) {
        $erros[] = "A categoria é obrigatória.";
    } elseif (!filter_var($categoria, FILTER_VALIDATE_INT) || (int)$categoria <= 0) {
        $erros[] = "A categoria selecionada não é válida.";
    }
    return $erros;
}

// Valida a marca do equipamento.
// Regras: obrigatória, entre 2 e 100 caracteres, e tem de conter
// pelo menos uma letra.
function validar_marca(string $marca): array {
    $erros = [];
    if (empty($marca)) {
        $erros[] = "A marca é obrigatória.";
    } elseif (strlen($marca) < 2) {
        $erros[] = "A marca deve ter pelo menos 2 caracteres.";
    } elseif (!preg_match('/[a-zA-ZÀ-ÿ]/', $marca)) {
        $erros[] = "A marca deve conter pelo menos uma letra.";
    } elseif (strlen($marca) > 100) {
        $erros[] = "A marca não pode ter mais de 100 caracteres.";
    }
    return $erros;
}

// Valida o modelo do equipamento.
// Regras: obrigatório, entre 2 e 100 caracteres.
function validar_modelo(string $modelo): array {
    $erros = [];
    if (empty($modelo)) {
        $erros[] = "O modelo é obrigatório.";
    } elseif (strlen($modelo) < 2) {
        $erros[] = "O modelo deve ter pelo menos 2 caracteres.";
    } elseif (strlen($modelo) > 100) {
        $erros[] = "O modelo não pode ter mais de 100 caracteres.";
    }
    return $erros;
}

// Valida o número de série do equipamento.
// Regras: obrigatório, entre 2 e 100 caracteres.
function validar_numero_serie(string $numero_serie): array {
    $erros = [];
    if (empty($numero_serie)) {
        $erros[] = "O número de série é obrigatório.";
    } elseif (strlen($numero_serie) < 2) {
        $erros[] = "O número de série deve ter pelo menos 2 caracteres.";
    } elseif (strlen($numero_serie) > 100) {
        $erros[] = "O número de série não pode ter mais de 100 caracteres.";
    }
    return $erros;
}

// Valida o fabricante do equipamento.
// Campo opcional: só valida se algo tiver sido preenchido.
// Regras (quando preenchido): entre 2 e 150 caracteres, com pelo
// menos uma letra.
function validar_fabricante(string $fabricante): array {
    $erros = [];
    if (!empty($fabricante)) {
        if (strlen($fabricante) < 2) {
            $erros[] = "O fabricante deve ter pelo menos 2 caracteres.";
        } elseif (!preg_match('/[a-zA-ZÀ-ÿ]/', $fabricante)) {
            $erros[] = "O fabricante deve conter pelo menos uma letra.";
        } elseif (strlen($fabricante) > 150) {
            $erros[] = "O fabricante não pode ter mais de 150 caracteres.";
        }
    }
    return $erros;
}

// Valida o ano de fabrico do equipamento.
// Campo opcional: só valida se preenchido.
// Regras (quando preenchido): exatamente 4 dígitos, e tem de estar
// entre 1900 e o ano atual (não permite anos no futuro).
function validar_ano_fabrico(string $ano_fabrico): array {
    $erros = [];
    if (!empty($ano_fabrico)) {
        if (!preg_match('/^\d{4}$/', $ano_fabrico)) {
            $erros[] = "O ano de fabrico deve ter 4 dígitos (ex: 2022).";
        } elseif ((int)$ano_fabrico < 1900 || (int)$ano_fabrico > (int)date('Y')) {
            $erros[] = "O ano de fabrico deve estar entre 1900 e " . date('Y') . ".";
        }
    }
    return $erros;
}

// Valida a criticidade clínica do equipamento.
// Regras: obrigatória, e tem de corresponder a um dos valores
// permitidos (aceita tanto "media" como "média", para cobrir
// pequenas variações de acentuação vindas do formulário).
function validar_criticidade(string $criticidade): array {
    $erros = [];
    $validas = ['baixa', 'media', 'média', 'alta', 'suporte_de_vida'];
    if (empty($criticidade)) {
        $erros[] = "A criticidade é obrigatória.";
    } elseif (!in_array(strtolower($criticidade), $validas)) {
        $erros[] = "O valor de criticidade selecionado não é válido.";
    }
    return $erros;
}

// Valida o estado atual do equipamento (ativo, em manutenção, etc.).
// Regras: obrigatório, e tem de corresponder a um dos valores
// permitidos. Aceita tanto a versão com underscore (ex:
// "em_manutencao", usada internamente na BD) como a versão com
// espaço e acentos (ex: "em manutenção", como pode vir do formulário).
function validar_estado(string $estado): array {
    $erros = [];
    $validos = [
        'ativo', 'inativo', 'abatido',
        'em_manutencao', 'em manutenção',
        'em_calibracao', 'em calibração',
        'em_quarentena', 'em quarentena'
    ];
    if (empty($estado)) {
        $erros[] = "O estado é obrigatório.";
    } elseif (!in_array(strtolower($estado), $validos)) {
        $erros[] = "O valor de estado selecionado não é válido.";
    }
    return $erros;
}

// Valida a data de aquisição do equipamento.
// Campo opcional: só valida se preenchido.
// Regras (quando preenchido): formato AAAA-MM-DD, tem de ser uma
// data real (checkdate() rejeita ex: 2024-02-30), e não pode ser
// uma data no futuro (não faz sentido comprar algo "amanhã").
function validar_data_aquisicao(string $data): array {
    $erros = [];
    if (!empty($data)) {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
            $erros[] = "Formato da data de aquisição inválido.";
        } else {
            $partes = explode('-', $data);
            if (!checkdate((int)$partes[1], (int)$partes[2], (int)$partes[0])) {
                $erros[] = "A data de aquisição não é uma data válida.";
            } elseif ($data > date('Y-m-d')) {
                $erros[] = "A data de aquisição não pode ser no futuro.";
            }
        }
    }
    return $erros;
}

// Valida o custo de aquisição do equipamento (em euros).
// Campo opcional: só valida se preenchido.
// Regras (quando preenchido): tem de ser numérico, não negativo, e
// não pode ultrapassar 9999999.99 (limite de segurança contra
// valores absurdos introduzidos por engano).
function validar_custo_aquisicao(string $custo): array {
    $erros = [];
    if (!empty($custo)) {
        if (!is_numeric($custo) || (float)$custo < 0) {
            $erros[] = "O custo de aquisição deve ser um valor numérico positivo.";
        } elseif ((float)$custo > 9999999.99) {
            $erros[] = "O custo de aquisição introduzido é demasiado elevado.";
        }
    }
    return $erros;
}

// Valida o tipo de entrada do equipamento (compra, doação, etc.).
// Campo opcional: só valida se preenchido, e nesse caso tem de
// corresponder a um dos valores permitidos.
function validar_tipo_entrada(string $tipo_entrada): array {
    $erros = [];
    $validos = ['compra', 'doacao', 'aluguer', 'emprestimo'];

    if (!empty($tipo_entrada) && !in_array(strtolower($tipo_entrada), $validos)) {
        $erros[] = "O tipo de entrada selecionado não é válido.";
    }

    return $erros;
}

// Valida o ID da localização associada ao equipamento.
// Regras: obrigatória, tem de ser um número inteiro positivo
// (corresponde ao ID de um registo existente na tabela localizacoes).
function validar_localizacao(string $localizacao_id): array {
    $erros = [];
    if (empty($localizacao_id)) {
        $erros[] = "A localização é obrigatória.";
    } elseif (!filter_var($localizacao_id, FILTER_VALIDATE_INT) || (int)$localizacao_id <= 0) {
        $erros[] = "A localização selecionada não é válida.";
    }
    return $erros;
}

// Valida o ID de um fornecedor associado ao equipamento.
// Campo opcional: só valida se preenchido, e nesse caso tem de ser
// um número inteiro positivo.
function validar_fornecedor(string $fornecedor_id): array {
    $erros = [];
    if (!empty($fornecedor_id)) {
        if (!filter_var($fornecedor_id, FILTER_VALIDATE_INT) || (int)$fornecedor_id <= 0) {
            $erros[] = "O fornecedor selecionado não é válido.";
        }
    }
    return $erros;
}

// Valida os dados da garantia associada ao equipamento (tipo, data de
// início e data de fim). A garantia no seu todo é opcional, mas se
// QUALQUER um dos três campos for preenchido, os outros passam a ser
// obrigatórios (não faz sentido ter só a data de início sem o tipo,
// por exemplo). Valida também que a data de fim é posterior à de início.
function validar_garantia(string $tipo, string $inicio, string $fim): array {
    $erros = [];
    if (!empty($tipo) || !empty($inicio) || !empty($fim)) {
        if (empty($tipo))  $erros[] = "O tipo de garantia é obrigatório quando a garantia está preenchida.";
        if (empty($inicio)) $erros[] = "A data de início da garantia é obrigatória.";
        if (empty($fim))    $erros[] = "A data de fim da garantia é obrigatória.";
        if (!empty($inicio) && !empty($fim) && $fim <= $inicio) {
            $erros[] = "A data de fim da garantia deve ser posterior à data de início.";
        }
    }
    return $erros;
}

// Valida os dados do contrato associado ao equipamento (tipo, datas e
// entidade responsável). Mesma lógica da garantia: o contrato no seu
// todo é opcional, mas se algum campo for preenchido, os restantes
// tornam-se obrigatórios. Valida também que a data de fim é posterior
// à de início.
function validar_contrato(string $tipo, string $inicio, string $fim, string $entidade): array {
    $erros = [];
    if (!empty($tipo) || !empty($inicio) || !empty($fim) || !empty($entidade)) {
        if (empty($tipo))   $erros[] = "O tipo de contrato é obrigatório quando o contrato está preenchido.";
        if (empty($inicio)) $erros[] = "A data de início do contrato é obrigatória.";
        if (empty($fim))    $erros[] = "A data de fim do contrato é obrigatória.";
        if (!empty($inicio) && !empty($fim) && $fim <= $inicio) {
            $erros[] = "A data de fim do contrato deve ser posterior à data de início.";
        }
    }
    return $erros;
}

// Valida a categoria do equipamento quando é indicada pelo NOME
// (em vez do ID), usada em novo-equipamentos.php onde o <select>
// envia o nome da categoria diretamente.
function validar_categoria_nome(string $categoria): array {
    $erros = [];
    if (empty($categoria)) {
        $erros[] = "A categoria é obrigatória.";
    }
    return $erros;
}

// ============================================================
// FORNECEDORES
// ============================================================

// Valida o nome da empresa fornecedora.
// Regra: obrigatório (apenas verifica que não está vazio).
function validar_nome_empresa(string $nome): array {
    $erros = [];
    if ($nome === '') {
        $erros[] = "O nome da empresa é obrigatório.";
    }
    return $erros;
}

// Valida o NIF do fornecedor.
// Regras: obrigatório, e tem de ter exatamente 9 dígitos.
function validar_nif(string $nif): array {
    $erros = [];
    if ($nif === '') {
        $erros[] = "O NIF é obrigatório.";
    } elseif (!preg_match('/^\d{9}$/', $nif)) {
        $erros[] = "O NIF deve ter 9 dígitos.";
    }
    return $erros;
}

// Valida o ID do tipo de fornecedor (fabricante, distribuidor, etc.).
// Regras: obrigatório, e tem de ser numérico.
function validar_tipo_fornecedor(string $tipo_id): array {
    $erros = [];
    if ($tipo_id === '') {
        $erros[] = "O tipo de fornecedor é obrigatório.";
    } elseif (!is_numeric($tipo_id)) {
        $erros[] = "O tipo de fornecedor selecionado não é válido.";
    }
    return $erros;
}

// Valida o telefone geral do fornecedor.
// Regra: obrigatório (apenas verifica que não está vazio; não há
// validação de formato aqui — ver validar_telefone_contacto2() para
// uma versão com validação de formato).
function validar_telefone(string $telefone): array {
    $erros = [];
    if ($telefone === '') {
        $erros[] = "O telefone é obrigatório.";
    }
    return $erros;
}

// Valida o email geral do fornecedor.
// Regras: obrigatório, e tem de ter um formato de email válido.
function validar_email_geral(string $email): array {
    $erros = [];
    if ($email === '') {
        $erros[] = "O email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email geral não é válido.";
    }
    return $erros;
}

// Valida o email direto da pessoa de contacto do fornecedor.
// Campo opcional: só valida o formato se algo tiver sido preenchido.
function validar_email_contacto(string $email_contacto): array {
    $erros = [];
    if (!empty($email_contacto) && !filter_var($email_contacto, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email direto não é válido.";
    }
    return $erros;
}

// Valida a morada do fornecedor.
// Regras: obrigatória, entre 5 e 255 caracteres.
function validar_morada($morada)
{
    $erros = [];
    $morada = trim($morada ?? '');

    if (empty($morada)) {
        $erros[] = "A morada é obrigatória.";
    } elseif (strlen($morada) < 5) {
        $erros[] = "A morada deve ter pelo menos 5 caracteres.";
    } elseif (strlen($morada) > 255) {
        $erros[] = "A morada não pode ter mais de 255 caracteres.";
    }

    return $erros;
}

// Valida o website do fornecedor.
// Campo opcional: só valida o formato (URL válido) se preenchido.
function validar_website($website)
{
    $erros = [];
    $website = trim($website ?? '');

    if (!empty($website) && !filter_var($website, FILTER_VALIDATE_URL)) {
        $erros[] = "O website não é válido.";
    }

    return $erros;
}

// Valida o nome da pessoa de contacto do fornecedor.
// Regras: obrigatório, entre 2 e 100 caracteres, com pelo menos
// uma letra.
function validar_pessoa_contacto($pessoa_contacto)
{
    $erros = [];
    $pessoa_contacto = trim($pessoa_contacto ?? '');

    if (empty($pessoa_contacto)) {
        $erros[] = "O nome da pessoa de contacto é obrigatório.";
    } elseif (strlen($pessoa_contacto) < 2) {
        $erros[] = "O nome da pessoa de contacto deve ter pelo menos 2 caracteres.";
    } elseif (!preg_match('/[a-zA-ZÀ-ÿ]/', $pessoa_contacto)) {
        $erros[] = "O nome da pessoa de contacto deve conter pelo menos uma letra.";
    } elseif (strlen($pessoa_contacto) > 100) {
        $erros[] = "O nome da pessoa de contacto não pode ter mais de 100 caracteres.";
    }

    return $erros;
}

// Valida o telefone direto da pessoa de contacto do fornecedor.
// Regras: obrigatório, formato com 9 a 20 dígitos (aceita espaços e
// um "+" inicial opcional, para cobrir indicativos internacionais).
function validar_telefone_contacto2($telefone_contacto)
{
    $erros = [];
    $telefone_contacto = trim($telefone_contacto ?? '');

    if (empty($telefone_contacto)) {
        $erros[] = "O telefone direto é obrigatório.";
    } elseif (!preg_match('/^\+?[0-9\s]{9,20}$/', $telefone_contacto)) {
        $erros[] = "O telefone direto não é válido.";
    }

    return $erros;
}

// ============================================================
// LOCALIZAÇÕES
// ============================================================

// Valida o edifício da localização.
// Regras: obrigatório, e tem de ser um dos três edifícios
// pré-definidos (Principal, Bloco B, Bloco C) — não é texto livre,
// corresponde às opções fixas do <select> no formulário.
function validar_edificio(string $edificio): array {
    $erros = [];
    if ($edificio === '') {
        $erros[] = "O edifício é obrigatório.";
    } elseif (!in_array($edificio, ['Principal', 'Bloco B', 'Bloco C'])) {
        $erros[] = "O edifício selecionado não é válido.";
    }
    return $erros;
}

// Valida o piso da localização.
// Regras: obrigatório, e tem de ser um dos pisos pré-definidos
// (0 a 3), correspondendo às opções fixas do <select>.
function validar_piso(string $piso): array {
    $erros = [];
    if ($piso === '') {
        $erros[] = "O piso é obrigatório.";
    } elseif (!in_array($piso, ['0', '1', '2', '3'])) {
        $erros[] = "O piso selecionado não é válido.";
    }
    return $erros;
}

// Valida o código da sala/gabinete da localização.
// Regras: obrigatória, no máximo 3 caracteres (ex: "201", "B12").
// Nota: o placeholder do formulário foi ajustado para "Ex.: 201"
// (em vez de "Ex.: Sala 201") precisamente para não sugerir um
// valor mais longo do que esta validação permite.
function validar_sala(string $sala): array {
    $erros = [];
    if ($sala === '') {
        $erros[] = "A sala é obrigatória.";
    } elseif (strlen($sala) > 3) {
        $erros[] = "A sala deve ter no máximo 3 caracteres.";
    }
    return $erros;
}

// Valida o ID do serviço/departamento associado à localização.
// Regras: obrigatório, e tem de ser numérico.
function validar_servico(string $servico_id): array {
    $erros = [];
    if ($servico_id === '') {
        $erros[] = "O serviço é obrigatório.";
    } elseif (!is_numeric($servico_id)) {
        $erros[] = "O serviço selecionado não é válido.";
    }
    return $erros;
}

// ============================================================
// CONTACTOS (formulário público)
// Validações usadas no formulário de contacto da área pública
// (contactos.php), preenchido por visitantes não autenticados.
// ============================================================

// Valida o nome de quem preenche o formulário de contacto público.
// Regras: obrigatório, no máximo 100 caracteres.
function validar_nome_contacto(string $nome): array {
    $erros = [];
    if ($nome === '') {
        $erros[] = "O nome é obrigatório.";
    } elseif (strlen($nome) > 100) {
        $erros[] = "O nome não pode ter mais de 100 caracteres.";
    }
    return $erros;
}

// Valida o email de quem preenche o formulário de contacto público.
// Regras: obrigatório, formato de email válido.
function validar_email_contacto_publico(string $email): array {
    $erros = [];
    if ($email === '') {
        $erros[] = "O email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email não é válido.";
    }
    return $erros;
}

// Valida o telefone de quem preenche o formulário de contacto público.
// Campo opcional: só valida se preenchido. Remove tudo o que não for
// dígito (espaços, traços, etc.) com preg_replace e confirma que
// restam exatamente 9 dígitos (formato de número português).
function validar_telefone_contacto(string $telefone): array {
    $erros = [];
    if ($telefone !== '') {
        $digitos = preg_replace('/\D/', '', $telefone);
        if (strlen($digitos) !== 9) {
            $erros[] = "O telefone deve ter 9 dígitos.";
        }
    }
    return $erros;
}

// Valida o assunto selecionado no formulário de contacto público.
// Regras: obrigatório, e tem de corresponder a uma das opções
// pré-definidas do <select> (dúvida, demonstração, orçamento, etc.).
function validar_assunto_contacto(string $assunto): array {
    $erros = [];
    $assuntos_validos = ['duvida', 'demonstracao', 'orcamento', 'suporte', 'parceria', 'outro'];
    if ($assunto === '') {
        $erros[] = "O assunto é obrigatório.";
    } elseif (!in_array($assunto, $assuntos_validos)) {
        $erros[] = "O assunto selecionado não é válido.";
    }
    return $erros;
}

// Valida a mensagem do formulário de contacto público.
// Regra: obrigatória (apenas verifica que não está vazia, sem
// limite mínimo/máximo de caracteres).
function validar_mensagem_contacto(string $mensagem): array {
    $erros = [];
    if ($mensagem === '') {
        $erros[] = "A mensagem é obrigatória.";
    }
    return $erros;
}
// ============================================================
// CONTEÚDOS DO SITE (área pública, editável pelo administrador)
// ============================================================

// Valida o email de contacto apresentado no site público.
// Campo opcional: só valida o formato se preenchido.
function validar_email_conteudo(string $email): array {
    $erros = [];
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email não é válido.";
    }
    return $erros;
}

// Valida o código postal apresentado no site público.
// Campo opcional: só valida o formato (XXXX-XXX, padrão português)
// se preenchido.
function validar_codigo_postal(string $codigo_postal): array {
    $erros = [];
    if (!empty($codigo_postal) && !preg_match('/^\d{4}-\d{3}$/', $codigo_postal)) {
        $erros[] = "O código postal deve seguir o formato XXXX-XXX (ex: 4200-072).";
    }
    return $erros;
}
// Valida o telefone de contacto apresentado no site público.
// Campo opcional: só valida se preenchido. Remove tudo o que não for
// dígito (espaços, traços, etc.) e confirma que restam exatamente
// 9 dígitos (formato de número português), mesma lógica de
// validar_telefone_contacto() usada no formulário de contacto público.
function validar_telefone_conteudo(string $telefone): array {
    $erros = [];
    if (!empty($telefone)) {
        $digitos = preg_replace('/\D/', '', $telefone);
        if (strlen($digitos) !== 9) {
            $erros[] = "O telefone deve ter 9 dígitos.";
        }
    }
    return $erros;
}