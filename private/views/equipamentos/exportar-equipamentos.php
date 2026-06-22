<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../includes/funcoes.php';

redirect_if_not_logged();
redirect_if_not_allowed(['administrador', 'tecnico']);

// --------------------------------------------------------------------
// FORMATO DE EXPORTAÇÃO
// --------------------------------------------------------------------
$formato = $_GET['formato'] ?? 'csv';
if (!in_array($formato, ['csv', 'json', 'pdf'])) {
    $formato = 'csv';
}

// --------------------------------------------------------------------
// REUTILIZA OS MESMOS FILTROS DA LISTAGEM
// --------------------------------------------------------------------
$pesquisa    = $_GET['pesquisa']    ?? '';
$categoria   = $_GET['categoria']   ?? '';
$estado      = $_GET['estado']      ?? '';
$criticidade = $_GET['criticidade'] ?? '';
$servico     = $_GET['servico']     ?? '';
$fornecedor  = $_GET['fornecedor']  ?? '';
$ordenar     = $_GET['ordenar']     ?? 'codigo_asc';

try {
    $ligacao = new PDO(
        "mysql:host=" . MYSQL_HOST . ";port=" . MYSQL_PORT . ";dbname=" . MYSQL_DATABASE . ";charset=utf8",
        MYSQL_USERNAME,
        MYSQL_PASSWORD
    );
    $ligacao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "
        SELECT DISTINCT
            e.codigo,
            e.designacao,
            e.marca,
            e.modelo,
            e.numero_serie,
            e.fabricante,
            e.ano_fabrico,
            e.data_aquisicao,
            e.custo_aquisicao,
            e.tipo_entrada,
            e.estado,
            e.criticidade,
            e.observacoes,
            c.nome AS categoria_nome,
            s.nome AS servico_nome,
            l.edificio,
            l.sala
        FROM equipamentos e
        LEFT JOIN categorias_equipamento c ON e.categoria_id = c.id
        LEFT JOIN localizacoes l ON e.localizacao_id = l.id
        LEFT JOIN servicos s ON l.servico_id = s.id
        LEFT JOIN equipamento_fornecedor ef ON e.id = ef.equipamento_id
        LEFT JOIN fornecedores f ON ef.fornecedor_id = f.id
        WHERE 1 = 1
    ";

    $params = [];

    if (!empty($pesquisa)) {
        $sql .= " AND (
            e.codigo LIKE :pesquisa OR
            e.designacao LIKE :pesquisa OR
            e.marca LIKE :pesquisa OR
            e.modelo LIKE :pesquisa OR
            e.numero_serie LIKE :pesquisa OR
            c.nome LIKE :pesquisa OR
            s.nome LIKE :pesquisa OR
            f.nome LIKE :pesquisa
        )";
        $params[':pesquisa'] = '%' . $pesquisa . '%';
    }
    if (!empty($categoria)) {
        $sql .= " AND e.categoria_id = :categoria";
        $params[':categoria'] = $categoria;
    }
    if (!empty($estado)) {
        $sql .= " AND e.estado = :estado";
        $params[':estado'] = $estado;
    }
    if (!empty($criticidade)) {
        $sql .= " AND e.criticidade = :criticidade";
        $params[':criticidade'] = $criticidade;
    }
    if (!empty($servico)) {
        $sql .= " AND s.id = :servico";
        $params[':servico'] = $servico;
    }
    if (!empty($fornecedor)) {
        $sql .= " AND f.id = :fornecedor";
        $params[':fornecedor'] = $fornecedor;
    }

    switch ($ordenar) {
        case 'codigo_desc':      $sql .= " ORDER BY e.codigo DESC"; break;
        case 'designacao_asc':   $sql .= " ORDER BY e.designacao ASC"; break;
        case 'designacao_desc':  $sql .= " ORDER BY e.designacao DESC"; break;
        case 'estado':           $sql .= " ORDER BY e.estado ASC"; break;
        case 'criticidade':      $sql .= " ORDER BY e.criticidade ASC"; break;
        default:                 $sql .= " ORDER BY e.codigo ASC"; break;
    }

    $stmt = $ligacao->prepare($sql);
    $stmt->execute($params);
    $equipamentos = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die('Erro ao obter dados: ' . $e->getMessage());
}

$ligacao = null;

$colunas = [
    'codigo'         => 'Código',
    'designacao'     => 'Designação',
    'marca'          => 'Marca',
    'modelo'         => 'Modelo',
    'numero_serie'   => 'Nº Série',
    'fabricante'     => 'Fabricante',
    'ano_fabrico'    => 'Ano Fabrico',
    'data_aquisicao' => 'Data Aquisição',
    'custo_aquisicao'=> 'Custo (€)',
    'tipo_entrada'   => 'Tipo Entrada',
    'estado'         => 'Estado',
    'criticidade'    => 'Criticidade',
    'categoria_nome' => 'Categoria',
    'servico_nome'   => 'Serviço',
    'edificio'       => 'Edifício',
    'sala'           => 'Sala',
    'observacoes'    => 'Observações',
];

$nome_ficheiro = 'medinv_equipamentos_' . date('Ymd_His');

// ====================================================================
// CSV
// ====================================================================
if ($formato === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $nome_ficheiro . '.csv"');

    $output = fopen('php://output', 'w');

    // BOM para Excel reconhecer UTF-8
    fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

    // Cabeçalho
    fputcsv($output, array_values($colunas), ';');

    // Dados
    foreach ($equipamentos as $eq) {
        $linha = [];
        foreach (array_keys($colunas) as $campo) {
            $linha[] = $eq[$campo] ?? '';
        }
        fputcsv($output, $linha, ';');
    }

    fclose($output);
    exit;
}

// ====================================================================
// JSON
// ====================================================================
if ($formato === 'json') {
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $nome_ficheiro . '.json"');

    $dados = [];
    foreach ($equipamentos as $eq) {
        $linha = [];
        foreach (array_keys($colunas) as $campo) {
            $linha[$colunas[$campo]] = $eq[$campo] ?? null;
        }
        $dados[] = $linha;
    }

    echo json_encode([
        'exportado_em' => date('Y-m-d H:i:s'),
        'total'        => count($dados),
        'equipamentos' => $dados,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

    exit;
}

// ====================================================================
// PDF — página HTML com impressão automática via browser
// ====================================================================
if ($formato === 'pdf') {
?>
<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>MEDINV — Listagem de Equipamentos</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 11px; color: #222; padding: 20px; }
        h1 { font-size: 16px; color: #1d5c7f; margin-bottom: 4px; }
        .subtitulo { font-size: 10px; color: #666; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        thead tr { background-color: #1d5c7f; color: #fff; }
        thead th { padding: 6px 8px; text-align: left; font-size: 10px; }
        tbody tr:nth-child(even) { background-color: #edf2f7; }
        tbody td { padding: 5px 8px; border-bottom: 1px solid #ddd; font-size: 10px; }
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom:16px;">
        <button onclick="window.print()" style="padding:8px 16px; background:#1d5c7f; color:#fff; border:none; border-radius:4px; cursor:pointer; font-size:13px;">
            🖨️ Imprimir / Guardar como PDF
        </button>
        <button onclick="window.close()" style="padding:8px 16px; background:#ccc; border:none; border-radius:4px; cursor:pointer; font-size:13px; margin-left:8px;">
            Fechar
        </button>
    </div>

    <h1>MEDINV — Listagem de Equipamentos</h1>
    <p class="subtitulo">
        Exportado em: <?= date('d/m/Y H:i:s') ?> &nbsp;|&nbsp;
        Total: <?= count($equipamentos) ?> equipamentos
    </p>

    <table>
        <thead>
            <tr>
                <th>Código</th>
                <th>Designação</th>
                <th>Marca</th>
                <th>Categoria</th>
                <th>Serviço</th>
                <th>Estado</th>
                <th>Criticidade</th>
                <th>Edifício</th>
                <th>Sala</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipamentos as $eq): ?>
            <tr>
                <td><?= htmlspecialchars($eq['codigo'] ?? '—') ?></td>
                <td><?= htmlspecialchars($eq['designacao'] ?? '—') ?></td>
                <td><?= htmlspecialchars($eq['marca'] ?? '—') ?></td>
                <td><?= htmlspecialchars($eq['categoria_nome'] ?? '—') ?></td>
                <td><?= htmlspecialchars($eq['servico_nome'] ?? '—') ?></td>
                <td><?= htmlspecialchars(match($eq['estado'] ?? '') {
                    'ativo'          => 'Ativo',
                    'em_manutencao'  => 'Em manutenção',
                    'inativo'        => 'Inativo',
                    'em_calibracao'  => 'Em calibração',
                    'em_quarentena'  => 'Em quarentena',
                    'abatido'        => 'Abatido',
                    default          => $eq['estado'] ?? '—'
                }) ?></td>
                <td><?= htmlspecialchars(match($eq['criticidade'] ?? '') {
                    'suporte_de_vida' => 'Suporte de vida',
                    'alta'            => 'Alta',
                    'media'           => 'Média',
                    'baixa'           => 'Baixa',
                    default           => $eq['criticidade'] ?? '—'
                }) ?></td>
                <td><?= htmlspecialchars($eq['edificio'] ?? '—') ?></td>
                <td><?= htmlspecialchars($eq['sala'] ?? '—') ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
        // Abre o diálogo de impressão automaticamente ao carregar a página
        window.onload = function() { window.print(); };
    </script>

</body>
</html>
<?php
    exit;
}