<?php
define('_DS','/');

define('_CONFIG_LIVE_SITE', 'http://'.$_SERVER['HTTP_HOST']);
define('_CONFIG_TEMPLATE_PATH',dirname(__DIR__) . _DS . 'templates');
define('_CONFIG_TEMPLATE_CACHE_PATH',false);


define('_CONFIG_MID_RANGE',3);
define('_CONFIG_REG_PAGE',9);

define('_SUCCESS_STATUS',0);
define('_WARNING_STATUS',1);
define('_ERROR_STATUS',2);
define('_INFO_STATUS',3);

define('_TITLE','Page Title');
define('_META_KEYWORDS','');
define('_META_DESCRIPTION','');

return array(
	
	
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
        'page_template' => 'default'
        
    ),
);
