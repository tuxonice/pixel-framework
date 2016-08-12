<?php
define('_DS','/');

define('_CONFIG_LIVE_SITE', 'http://'.$_SERVER['HTTP_HOST']);
define('_CONFIG_TEMPLATE_PATH',__DIR__ . '/../templates/');
define('_CONFIG_TEMPLATE_CACHE_PATH',false);

define('_CONFIG_MID_RANGE',3);
define('_CONFIG_REG_PAGE',9);

define('_VARTYPE_UNKNOWN',0);
define('_VARTYPE_BOOLEAN',1);
define('_VARTYPE_INTEGER',2);
define('_VARTYPE_DOUBLE',3);
define('_VARTYPE_STRING',4);
define('_VARTYPE_ARRAY',5);
define('_VARTYPE_OBJECT',6);
define('_VARTYPE_RESOURCE',7);
define('_VARTYPE_NULL',8);

define('_SUCCESS_STATUS',0);
define('_WARNING_STATUS',1);
define('_ERROR_STATUS',2);
define('_INFO_STATUS',3);

define('_TITLE','Page Title');
define('_META_KEYWORDS','');
define('_META_DESCRIPTION','');

return array(
	'database' => array(
			'host' => 'localhost',
			'username' => 'root',
			'password' => 'xampp',
			'name' => 'pixel',
            'prefix' => '',
		),
	'email' => array(
			'host' => 'localhost',
			'username' => 'develop@example.com',
			'password' => 'xxx',
			'name' => 'TLAB',
			'email' => 'develop@example.com'),

    'settings' => array(
        'displayErrorDetails' => true,
        'default_lang_iso' => 'en',
        'timezone' => 'Europe/Lisbon',
        'page_suffix' => 'html',
        'main_path' => dirname($_SERVER['DOCUMENT_ROOT']),
        'tmp_path' => dirname($_SERVER['DOCUMENT_ROOT'])._DS.'tmp',
        'logs_path' => dirname($_SERVER['DOCUMENT_ROOT'])._DS.'logs',
        'cache_path' => $_SERVER['DOCUMENT_ROOT']._DS.'cache',
        'email_admin_email' => 'develop@example.com',
        'email_admin_name' => 'TLAB',
        'page_template' => 'default',
        
        
    ),
);
