<?php
/**
 * ============================================================================
 * Arquivo de configuração do Sistema Tatu
 * ============================================================================
 */

/**
 * Perfil ativo atualmente. Pode ser qualquer um dos definidos no
 * array $conexoes (development ou test).
 */
$perfil = 'test';

/**
 * Pasta do sistema no servidor. Se o servidor é acessado via
 * http://servidor.com/tatu, a variável deve conter '/tatu'.
 */
$url = '/tatu';

/**
 * locale que deve ser usado para o sistema. Esta configuração não afeta
 * a formatação de números.
 */
$locale = 'pt_BR.utf8';

/**
 * Array com as informações das conexões com o banco de dados.
 */
$conexoes = array();

/**
 * Conexão com o banco de desenvolvimento (development).
 */
$conexoes['development'] = array();
$conexoes['development']['host'] = '';
$conexoes['development']['db'] = '';
$conexoes['development']['user'] = '';
$conexoes['development']['pass'] = '';

/**
 * Conexão com o banco usado nos testes unitários (test).
 */
$conexoes['test'] = array();
$conexoes['test']['host'] = '';
$conexoes['test']['db'] = '';
$conexoes['test']['user'] = '';
$conexoes['test']['pass'] = '';

/**
 * Configurações do Google, usado pela biblioteca Zend.
 */
$googleUsuario = '';
$googleSenha = '';

/**
 * Configurações do Facebook, usado para integração com o Facebook e no login.
 */
$fb_app_id = '';
$fb_app_secret = '';

/**
 * Configurações do Windows Live, usado no login.
 */
$wl_app_id = '';
$wl_app_secret = '';

/**
 * Configurações regionais
 */
setlocale(LC_ALL, 'pt_BR.utf8');
setlocale(LC_NUMERIC, 'en_US.utf8');
date_default_timezone_set('America/Sao_Paulo');
?>
