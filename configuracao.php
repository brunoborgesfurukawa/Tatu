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
$conexoes['development']['host'] = '172.16.0.1';
$conexoes['development']['db'] = 'tatu';
$conexoes['development']['user'] = 'tatu';
$conexoes['development']['pass'] = 'cRFTTVwFyPPd1JpH';

/**
 * Conexão com o banco usado nos testes unitários (test).
 */
$conexoes['test'] = array();
$conexoes['test']['host'] = 'hector.cep';
$conexoes['test']['db'] = 'tatu_test';
$conexoes['test']['user'] = 'tatu';
$conexoes['test']['pass'] = 'cRFTTVwFyPPd1JpH';

$conexoes['production'] = array();
$conexoes['production']['host'] = 'localhost';
$conexoes['production']['db'] = 'tatu';
$conexoes['production']['user'] = 'tatu';
$conexoes['production']['pass'] = '3aHBpVrgkFH9giJ8';


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
