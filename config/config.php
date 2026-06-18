<?php

// Configurações gerais da aplicação
define('BASE_URL', '/MEDINV');

define('APP_NAME', 'MEDINV');
define('APP_VERSION', '1.0.0');
define('APP_COPYRIGHT', '© 2026 MEDINV');
//Endereço do servidor da base de dados. No caso de localhost, indica que o servidor corre na mesma máquina. 
define('MYSQL_HOST', 'vsgate-s1.dei.isep.ipp.pt');
define('MYSQL_PORT', '10464');
define('MYSQL_DATABASE', 'db1240722');
define('MYSQL_USERNAME', '1240722');
//Palavra-passe do utilizador da base de dados.  
define('MYSQL_PASSWORD', 'guimarães_722');
//Chave opcional para operações de encriptação, útil em sistemas que armazenam dados sensíveis. 
define('MYSQL_AES_KEY',  'guimarães_722');


// --------------------------------------------------------------------
// Segurança – Encriptação com OpenSSL
// --------------------------------------------------------------------
define('OPENSSL_METHOD', 'AES-256-CBC'); // Algoritmo simétrico robusto
define('OPENSSL_KEY', 'H0SDRQzIGqclX2kbYBk9xspdn9U5f3Wa'); // Chave  privada de 32 caracteres
define('OPENSSL_IV', 'BzKAbjuREsHgnw56'); // Vetor de inicialização (16caracteres)
