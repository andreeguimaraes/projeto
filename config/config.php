<?php

// Configurações gerais da aplicação

// Caminho base (raiz) da aplicação no servidor. Usado em redirecionamentos
// e em links absolutos (ex: header('Location: ' . BASE_URL . '/public/login.php')).
define('BASE_URL', '/sibdas/1240722/medinv');

// Nome da aplicação, usado por exemplo no <title> das páginas (ex: header.php).
define('APP_NAME', 'MEDINV');

// Número da versão atual da aplicação (referência interna/documentação).
define('APP_VERSION', '1.0.0');

// Texto de direitos de autor, normalmente mostrado no rodapé das páginas.
define('APP_COPYRIGHT', '© 2026 MEDINV');

// Endereço do servidor da base de dados. No caso de localhost, indica que o servidor corre na mesma máquina.
// Aqui aponta para o servidor remoto da escola (requisito do enunciado do projeto).
define('MYSQL_HOST', 'vsgate-s1.dei.isep.ipp.pt');

// Porta de ligação ao servidor MySQL (porta não-standard, definida pela escola).
define('MYSQL_PORT', '10464');

// Nome da base de dados a usar dentro do servidor MySQL.
define('MYSQL_DATABASE', 'db1240722');

// Nome de utilizador para autenticação no servidor MySQL.
define('MYSQL_USERNAME', '1240722');

// Palavra-passe do utilizador da base de dados.
define('MYSQL_PASSWORD', 'guimarães_722');

// Chave opcional para operações de encriptação, útil em sistemas que armazenam dados sensíveis.
// (Atualmente não usada em encriptação ao nível SQL, ex: AES_ENCRYPT/AES_DECRYPT do MySQL.)
define('MYSQL_AES_KEY',  'guimarães_722');


// --------------------------------------------------------------------
// Segurança – Encriptação com OpenSSL
// Usadas pelas funções aes_encrypt()/aes_decrypt() (em funcoes.php) para
// encriptar/desencriptar IDs enviados por URL (ex: ?id_equipamento=...).
// --------------------------------------------------------------------

// Algoritmo de encriptação simétrica usado (AES com chave de 256 bits, modo CBC).
define('OPENSSL_METHOD', 'AES-256-CBC'); // Algoritmo simétrico robusto

// Chave privada usada para encriptar/desencriptar (tem de ter 32 caracteres,
// exigido pelo AES-256). Deve manter-se secreta e nunca ser exposta publicamente.
define('OPENSSL_KEY', 'H0SDRQzIGqclX2kbYBk9xspdn9U5f3Wa'); // Chave  privada de 32 caracteres

// Vetor de inicialização (IV) usado pelo modo CBC do AES (tem de ter 16 caracteres).
// Nota: este IV é fixo/reutilizado em todas as operações — não é o ideal em termos
// de segurança (o recomendado seria gerar um IV novo a cada encriptação), mas foi
// uma decisão consciente para este projeto, já que não é requisito do guia de submissão.
define('OPENSSL_IV', 'BzKAbjuREsHgnw56'); // Vetor de inicialização (16caracteres)