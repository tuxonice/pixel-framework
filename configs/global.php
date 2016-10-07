<?php
define('_DS',DIRECTORY_SEPARATOR);

define('_CONFIG_DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT']);
define('_CONFIG_LIVE_SITE', 'http://'.$_SERVER['HTTP_HOST']);
define('_CONFIG_BASE_PATH', dirname(__DIR__));
define('_CONFIG_TEMPLATE_PATH', _CONFIG_BASE_PATH . _DS . 'templates');
define('_CONFIG_TEMPLATE_CACHE_PATH',false);
define('_CONFIG_TEMP_PATH', _CONFIG_BASE_PATH . _DS . 'tmp');
define('_CONFIG_LOGS_PATH', _CONFIG_BASE_PATH . _DS . 'logs');
define('_CONFIG_CACHE_PATH', _CONFIG_BASE_PATH . _DS . 'cache');

define('_SUCCESS_STATUS',0);
define('_WARNING_STATUS',1);
define('_ERROR_STATUS',2);
define('_INFO_STATUS',3);



