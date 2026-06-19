<?php
// ============================================================
// Validações r
// ============================================================

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

function validar_categoria(string $categoria): array {
    $erros = [];
    if (empty($categoria)) {
        $erros[] = "A categoria é obrigatória.";
    } elseif (!filter_var($categoria, FILTER_VALIDATE_INT) || (int)$categoria <= 0) {
        $erros[] = "A categoria selecionada não é válida.";
    }
    return $erros;
}

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

function validar_tipo_entrada(string $tipo_entrada): array {
    $erros = [];
    $validos = ['compra', 'doacao', 'aluguer', 'emprestimo'];

    if (!empty($tipo_entrada) && !in_array(strtolower($tipo_entrada), $validos)) {
        $erros[] = "O tipo de entrada selecionado não é válido.";
    }

    return $erros;
}

function validar_localizacao(string $localizacao_id): array {
    $erros = [];
    if (empty($localizacao_id)) {
        $erros[] = "A localização é obrigatória.";
    } elseif (!filter_var($localizacao_id, FILTER_VALIDATE_INT) || (int)$localizacao_id <= 0) {
        $erros[] = "A localização selecionada não é válida.";
    }
    return $erros;
}

function validar_fornecedor(string $fornecedor_id): array {
    $erros = [];
    if (!empty($fornecedor_id)) {
        if (!filter_var($fornecedor_id, FILTER_VALIDATE_INT) || (int)$fornecedor_id <= 0) {
            $erros[] = "O fornecedor selecionado não é válido.";
        }
    }
    return $erros;
}

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
// ============================================================
// FORNECEDORES
// ============================================================

function validar_nome_empresa(string $nome): array {
    $erros = [];
    if ($nome === '') {
        $erros[] = "O nome da empresa é obrigatório.";
    }
    return $erros;
}

function validar_nif(string $nif): array {
    $erros = [];
    if ($nif === '') {
        $erros[] = "O NIF é obrigatório.";
    } elseif (!preg_match('/^\d{9}$/', $nif)) {
        $erros[] = "O NIF deve ter 9 dígitos.";
    }
    return $erros;
}

function validar_tipo_fornecedor(string $tipo_id): array {
    $erros = [];
    if ($tipo_id === '') {
        $erros[] = "O tipo de fornecedor é obrigatório.";
    } elseif (!is_numeric($tipo_id)) {
        $erros[] = "O tipo de fornecedor selecionado não é válido.";
    }
    return $erros;
}

function validar_telefone(string $telefone): array {
    $erros = [];
    if ($telefone === '') {
        $erros[] = "O telefone é obrigatório.";
    }
    return $erros;
}

function validar_email_geral(string $email): array {
    $erros = [];
    if ($email === '') {
        $erros[] = "O email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email geral não é válido.";
    }
    return $erros;
}

function validar_email_contacto(string $email_contacto): array {
    $erros = [];
    if (!empty($email_contacto) && !filter_var($email_contacto, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email direto não é válido.";
    }
    return $erros;
}
// ============================================================
// LOCALIZAÇÕES
// ============================================================

function validar_edificio(string $edificio): array {
    $erros = [];
    if ($edificio === '') {
        $erros[] = "O edifício é obrigatório.";
    } elseif (!in_array($edificio, ['Principal', 'Bloco B', 'Bloco C'])) {
        $erros[] = "O edifício selecionado não é válido.";
    }
    return $erros;
}

function validar_piso(string $piso): array {
    $erros = [];
    if ($piso === '') {
        $erros[] = "O piso é obrigatório.";
    } elseif (!in_array($piso, ['0', '1', '2', '3'])) {
        $erros[] = "O piso selecionado não é válido.";
    }
    return $erros;
}

function validar_sala(string $sala): array {
    $erros = [];
    if ($sala === '') {
        $erros[] = "A sala é obrigatória.";
    } elseif (strlen($sala) > 3) {
        $erros[] = "A sala deve ter no máximo 3 caracteres.";
    }
    return $erros;
}

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
// ============================================================

function validar_nome_contacto(string $nome): array {
    $erros = [];
    if ($nome === '') {
        $erros[] = "O nome é obrigatório.";
    } elseif (strlen($nome) > 100) {
        $erros[] = "O nome não pode ter mais de 100 caracteres.";
    }
    return $erros;
}

function validar_email_contacto_publico(string $email): array {
    $erros = [];
    if ($email === '') {
        $erros[] = "O email é obrigatório.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "O email não é válido.";
    }
    return $erros;
}

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

function validar_mensagem_contacto(string $mensagem): array {
    $erros = [];
    if ($mensagem === '') {
        $erros[] = "A mensagem é obrigatória.";
    }
    return $erros;
}

