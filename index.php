<?php

//apenas inclui os arquivos necessarios
require_once 'config.php';

// evita que usuários acesse este arquivo diretamente
if (!defined('ABSPATH'))
    exit;

// Inicia a sessão
session_start();

// Verifica o modo para debugar
if (!defined('DEBUG') || DEBUG === false) {
    error_reporting(0);
    ini_set("display_errors", 0);
} else {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
}

// funções globais
require_once ABSPATH . '/functions/global-functions.php';

// carrega a aplicação
$aplicacao = new Principal();
?>