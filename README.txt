 MEDINV — Sistema de Gestão de Equipamento Hospitalar
 README.txt


--------------------------------------------------------------------------------
 1. IDENTIFICAÇÃO DO PROJETO


Nome do projeto   : MEDINV — Sistema de Gestão de Equipamento Hospitalar
Unidade curricular: SIBDAS — Sistemas de Informação e Bases de Dados Aplicados
                    à Saúde
Curso             : Licenciatura em Engenharia Biomédica (LEBIOM)
Instituição       : ISEP — Instituto Superior de Engenharia do Porto
Ano letivo        : 2025/2026

--------------------------------------------------------------------------------
 2. AUTOR

Nome   : André Sarmento Santos Clara Guimarães
Número : 1240722

--------------------------------------------------------------------------------
 3. DESCRIÇÃO DA APLICAÇÃO


MEDINV é um sistema web desenvolvida em PHP e MySQL que simula uma solução
de gestão de inventário hospitalar..

A aplicação é composta por duas áreas distintas:

  - Área pública: acessível a qualquer visitante, apresenta informação
    institucional sobre a empresa (Início, Quem Somos, Serviços e Contactos),
    com conteúdos editáveis pelo administrador a partir da área privada.

  - Área privada: acessível apenas a utilizadores autenticados, permite gerir
    o inventário de equipamentos médicos, fornecedores, localizações, garantias,
    contratos e documentação técnica associada. Inclui ainda um dashboard com
    indicadores e gráficos do estado do inventário, registo de eventos (logs)
    e exportação de dados em CSV, JSON e PDF.

--------------------------------------------------------------------------------

 4. ESTRUTURA DE DIRETORIAS


medinv/
├── assets/
│   ├── bootstrap/
│   ├── chartjs/
│   ├── css/
│   │   └── 1240722.css                        Folha de estilos da área pública
│   ├── fontawesome/
│   ├── fonts/
│   ├── img/
│   └── js/
│       └── 1240722.js                         Scripts JavaScript globais
├── basedados/
├── config/
│   └── config.php                             Configuração da ligação à base de dados
├── private/
│   ├── assets/
│   │   ├── datatables/
│   │   ├── jQuery/
│   │   └── admin1240722.css                   Folha de estilos da área privada
│   ├── includes/
│   │   ├── footer.php                         Rodapé partilhado da área privada
│   │   ├── funcoes.php                        Funções auxiliares globais (sessão, logs, etc.)
│   │   ├── header.php                         Cabeçalho partilhado da área privada
│   │   ├── nav.php                            Barra de navegação superior
│   │   ├── sidebar.php                        Menu lateral
│   │   └── validacoes.php                     Funções de validação de formulários
│   ├── views/
│   │   ├── conteudos/
│   │   │   └── conteudos.php                  Gestão dos conteúdos da área pública
│   │   ├── equipamentos/
│   │   │   ├── detalhes-equipamentos.php      Visualização detalhada de um equipamento
│   │   │   ├── editar-equipamentos.php        Formulário de edição de equipamento
│   │   │   ├── equipamentos.php               Listagem e pesquisa de equipamentos
│   │   │   ├── exportar-equipamentos.php      Exportação em CSV, JSON e PDF
│   │   │   └── novo-equipamentos.php          Formulário de criação de equipamento
│   │   ├── fornecedores/
│   │   │   ├── detalhes-fornecedor.php        Visualização detalhada de um fornecedor
│   │   │   ├── editar-fornecedor.php          Formulário de edição de fornecedor
│   │   │   ├── fornecedor.php                 Listagem e pesquisa de fornecedores
│   │   │   └── novo-fornecedor.php            Formulário de criação de fornecedor
│   │   └── localizacoes/
│   │       ├── detalhes-localizacoes.php      Visualização detalhada de uma localização
│   │       ├── editar-localizacoes.php        Formulário de edição de localização
│   │       ├── localizacoes.php               Listagem e pesquisa de localizações
│   │       └── novo-localizacoes.php          Formulário de criação de localização
│   ├── dashboard.php                          Dashboard com indicadores e gráficos do inventário
│   ├── home.php                               Página inicial da área privada
│   ├── index-admin.php                        Página de redirecionamento pós-login
│   └── processa_login.php                     Processamento e validação do login
├── public/
│   ├── views/
│   │   ├── contactos/
│   │   │   └── contactos.php                  Página de contactos com formulário público
│   │   ├── quem-somos/
│   │   │   └── quem-somos.php                 Página institucional da empresa
│   │   └── servicos/
│   │       └── servicos.php                   Página de apresentação dos serviços
│   ├── index.php                              Página inicial da área pública
│   ├── login.php                              Formulário de autenticação
│   └── logout.php                             Terminar sessão e redirecionar para login
├── uploads/
│   ├── contratos/                             Ficheiros PDF de contratos
│   ├── documentos/                            Ficheiros PDF de documentação técnica
│   └── garantias/                             Ficheiros PDF de garantias
├── commits.txt
└── README.txt

--------------------------------------------------------------------------------
 5. INSTRUÇÕES DE ACESSO À APLICAÇÃO

Para executar a aplicação localmente (Laragon):

  1. Copiar a pasta do projeto (medinv/) para o diretório www do Laragon,
     respeitando a seguinte estrutura:
       C:\laragon\www\sibdas\1240722\medinv\
  2. Iniciar o Laragon (botão "Start All")
  3. Aceder no browser ao seguinte endereço:
       http://127.0.0.1/sibdas/1240722/medinv/public
  4. Garantir que o ficheiro config/config.php tem as credenciais corretas
     da base de dados

Base de dados:
  Servidor      : vsgate-s1.dei.isep.ipp.pt
  Porto         : 10464
  Base de dados : db1240722

Nota: O script SQL para criação e população da base de dados encontra-se
na pasta basedados/. Deve ser executado no servidor indicado antes de
iniciar a aplicação.

--------------------------------------------------------------------------------
 6. CREDENCIAIS DE ACESSO


Existem três perfis de utilizador na aplicação:

  ADMINISTRADOR
    Email   : admin@medinv.pt
    Password: admin123
    Acesso  : Todas as páginas da área privada, incluindo gestão de conteúdos
              públicos, equipamentos, fornecedores e localizações.

  TÉCNICO
    Email   : tecnico@medinv.pt
    Password: tecnico123
    Acesso  : Todas as páginas exceto a gestão de conteúdos públicos.

  PROFISSIONAL DE SAÚDE
    Email   : saude@medinv.pt
    Password: saude123
    Acesso  : Consulta (só leitura) de equipamentos, fornecedores e
              localizações. Sem permissão para criar, editar ou desativar.

--------------------------------------------------------------------------------
 7. INSTRUÇÕES PARA TESTE DA APLICAÇÃO


Recomenda-se a seguinte sequência de testes:

  1. Aceder ao endereço público e navegar pelas páginas Início, Quem Somos,
     Serviços e Contactos. Submeter uma mensagem pelo formulário de contacto.

  2. Aceder à Área Restrita (botão no canto superior direito) e autenticar com
     o perfil de ADMINISTRADOR.

  3. No dashboard, verificar os indicadores e os gráficos de distribuição.

  4. Em Equipamentos, testar:
       - Pesquisa e filtros combinados
       - Criação de um novo equipamento 
       - Edição de um equipamento existente
       - Desativação de um equipamento (botão do caixote do lixo)
       - Exportação em CSV, JSON e PDF

  5. Em Fornecedores, testar criação, edição e desativação.

  6. Em Localizações, testar criação e edição.

  7. Em Gestão de Conteúdos, alterar o telefone ou horário e verificar que
     a alteração se reflete nas páginas públicas (footer e página Contactos).

  8. Terminar sessão e autenticar com o perfil de TÉCNICO. Confirmar que
     a opção de Gestão de Conteúdos não está disponível.

  9. Terminar sessão e autenticar com o perfil de PROFISSIONAL DE SAÚDE.
     Confirmar que os botões de criação, edição e desativação não aparecem.

  10. Tentar aceder com credenciais inválidas e verificar a mensagem de erro.

--------------------------------------------------------------------------------
 8. INFORMAÇÃO ADICIONAL


  - Todos os ficheiros enviados (garantias, contratos, documentação) são
    guardados na pasta uploads/ com nome único gerado automaticamente.

  - A base de dados inclui dados de exemplo pré-carregados (29 equipamentos,
    12 fornecedores, 11 localizações) para facilitar os testes.

  - O sistema implementa soft delete: os equipamentos, fornecedores e
    localizações desativados não são eliminados da base de dados, mantendo
    o histórico de registos.

  - Os logs de autenticação (login, login falhado e logout) são registados
    automaticamente na tabela `logs` da base de dados.

  - A exportação de dados está disponível na listagem de equipamentos em
    três formatos: CSV (compatível com Excel), JSON e PDF (via impressão
    do browser).

