// ============================================================
// 1240722.js
// Ficheiro de scripts próprios do projeto MEDINV
// André Guimarães - 1240722
// ============================================================

// ------------------------------------------------------------
// 1. MODAL DE CONFIRMAÇÃO DE ELIMINAÇÃO (genérico)
// ------------------------------------------------------------
// Usado nas listagens de equipamentos, fornecedores e localizações.
// Lê os data-* attributes do botão clicado, preenche o texto de
// informação do modal, e define o link de confirmação com base no
// id do registo a eliminar.
//
// Parâmetros:
// - modalId: id do elemento modal (ex: "modalEliminar")
// - infoElementId: id do elemento onde mostrar a info do registo (ex: "modalEquipamentoInfo")
// - confirmLinkId: id do link/botão "Sim, eliminar" (ex: "btnConfirmarEliminar")
// - baseUrl: URL base para onde o link de confirmação deve apontar (ex: "equipamentos.php?eliminar=")
// - camposInfo: array com os nomes dos data-* a concatenar na info (ex: ["codigo", "designacao"])
function initModalEliminar(modalId, infoElementId, confirmLinkId, baseUrl, camposInfo) {
    const modal = document.getElementById(modalId);
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (e) {
        const btn = e.relatedTarget;
        const id = btn.dataset.id;

        const textoInfo = camposInfo
            .map(campo => btn.dataset[campo])
            .filter(Boolean)
            .join(' — ');

        document.getElementById(infoElementId).textContent = textoInfo;
        document.getElementById(confirmLinkId).href = baseUrl + id;
    });
}

// ------------------------------------------------------------
// 2. VALIDAÇÃO DO FORMULÁRIO "INFORMAÇÃO GERAL" (equipamentos)
// ------------------------------------------------------------
// Usado em novo-equipamentos.php e editar-equipamentos.php.
// Valida os campos da primeira tab do formulário de equipamento
// e devolve um array de mensagens de erro (vazio se tudo válido).
function validarGeral() {
    const erros = [];

    const codigo = document.querySelector('[name="codigo"]').value.trim();
    if (!codigo) {
        erros.push("O código interno é obrigatório.");
    } else if (!/^EQ\d{3,}$/.test(codigo)) {
        erros.push("O código deve começar por 'EQ' seguido de pelo menos 3 dígitos (ex: EQ001).");
    }

    const designacao = document.querySelector('[name="designacao"]').value.trim();
    if (!designacao) {
        erros.push("A designação é obrigatória.");
    } else if (designacao.length < 3) {
        erros.push("A designação deve ter pelo menos 3 caracteres.");
    } else if (!/[a-zA-ZÀ-ÿ]/.test(designacao)) {
        erros.push("A designação deve conter pelo menos uma letra.");
    }

    const categoria = document.querySelector('[name="categoria"]')?.value
        ?? document.querySelector('[name="categoria_id"]')?.value;
    if (!categoria) erros.push("A categoria é obrigatória.");

    const marca = document.querySelector('[name="marca"]').value.trim();
    if (!marca) {
        erros.push("A marca é obrigatória.");
    } else if (marca.length < 2) {
        erros.push("A marca deve ter pelo menos 2 caracteres.");
    } else if (!/[a-zA-ZÀ-ÿ]/.test(marca)) {
        erros.push("A marca deve conter pelo menos uma letra.");
    }

    const modelo = document.querySelector('[name="modelo"]').value.trim();
    if (!modelo) {
        erros.push("O modelo é obrigatório.");
    } else if (modelo.length < 2) {
        erros.push("O modelo deve ter pelo menos 2 caracteres.");
    }

    const numSerie = document.querySelector('[name="numero_serie"]').value.trim();
    if (!numSerie) {
        erros.push("O número de série é obrigatório.");
    } else if (numSerie.length < 2) {
        erros.push("O número de série deve ter pelo menos 2 caracteres.");
    }

    const fabricante = document.querySelector('[name="fabricante"]').value.trim();
    if (fabricante && fabricante.length < 2) {
        erros.push("O fabricante deve ter pelo menos 2 caracteres.");
    } else if (fabricante && !/[a-zA-ZÀ-ÿ]/.test(fabricante)) {
        erros.push("O fabricante deve conter pelo menos uma letra.");
    }

    const anoFabrico = document.querySelector('[name="ano_fabrico"]').value.trim();
    if (anoFabrico) {
        const ano = parseInt(anoFabrico);
        if (!/^\d{4}$/.test(anoFabrico) || ano < 1900 || ano > new Date().getFullYear()) {
            erros.push("O ano de fabrico deve estar entre 1900 e " + new Date().getFullYear() + ".");
        }
    }

    const criticidade = document.querySelector('[name="criticidade"]').value;
    if (!criticidade) erros.push("A criticidade é obrigatória.");

    const estado = document.querySelector('[name="estado"]').value;
    if (!estado) erros.push("O estado é obrigatório.");

    const custo = document.querySelector('[name="custo_aquisicao"]').value.trim();
    if (custo && (isNaN(custo) || parseFloat(custo) < 0)) {
        erros.push("O custo de aquisição deve ser um valor positivo.");
    }

    return erros;
}