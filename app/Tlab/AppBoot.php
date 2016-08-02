<?php
namespace Tlab;

use Tlab\Controllers;
use Tlab\Libraries\View;
use Tlab\Libraries\Session;
use Tlab\Libraries\Database;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class AppBoot {
	

protected $_controller, $_action, $_params, $_body;
protected $_blockList = NULL;
protected $_database = NULL;
protected $_headTags = NULL;
protected $_footerTags = NULL;

protected $_template = NULL;
protected $_layout = NULL; //layout actual
protected $_langISO = NULL; //ISO code da lingua actual
protected $_settings = NULL;

protected $_metaTags = NULL; //array
protected $_pageTitle = NULL;

protected $_httpRequest = NULL;
protected $_httpResponse = NULL;	

static $_instance;


public static function create($settings = NULL){
	
	if( ! (self::$_instance instanceof self) )
		self::$_instance = new self($settings);
	
return self::$_instance;
}


public static function getInstance(){
	
	if((self::$_instance instanceof self))
		return self::$_instance;
	
	throw new \Exception('Application Boot dont exists!');
	
}

private function __construct($settings) {

	$this->_httpRequest = Request::createFromGlobals();
	$this->_httpResponse = new Response();
	
	if(!is_null($settings)){
		$this->_settings = $settings;
	}

    $this->init();

	$splits = explode('/', trim($_SERVER['REQUEST_URI'],'/'));

    $validLang = false;
	if(!empty($splits[0]))
		$validLang = $this->processLanguage($splits);

	$this->_controller = $this->processController($splits);
    $this->_action = $this->processAction($splits);
    $this->processParams($splits);
		
	if(!$validLang){
		$this->_controller = 'errorController';
		$this->_action = 'indexAction';
	}
		
	$this->_connectDB();
	
	$this->_blocksList = array();
	$this->_headTags = array();
	$this->_layout = $this->getConfig('settings.page_layout');
	$this->_template = $this->getConfig('settings.page_template');
	
	
}

    private function processLanguage($splits)
    {
        $splits[0] = preg_replace("/[^A-Za-z0-9\-]/", '', urldecode($splits[0]));
        $validLang = true;
        switch($splits[0]){
            case 'en':
                $this->_langISO = 'en';
                break;
            case '':
                $this->_langISO = $this->getConfig('settings.default_lang_iso');
                break;
            default:
                $this->_langISO = $this->getConfig('settings.default_lang_iso');
                $validLang = false;

        }

        return $validLang;
    }

    private function processController($splits)
    {
       return  !empty($splits[1])?$splits[1].'Controller':'indexController';
    }
    
    
    private function processAction($splits)
    {

        $action = 'indexAction';
        if(!empty($splits[2])){
            $temp = explode('?',$splits[2]);
            if(count($temp) == 2)
                $splits[2] = $temp[0];

            if(!empty($splits[2]))
                $splits[2] = preg_replace("/[^A-Za-z0-9_\-\.]/", '', urldecode($splits[2]));

            $action = str_replace('.'.$this->getConfig('settings.page_suffix'),'',$splits[2]);
            $action = !empty($action)?$action.'Action':'indexAction';
        }

        return $action;

    }

    private function processParams($splits)
    {
        if(!empty($splits[3])) {
            $keys = $values = array();
            for($idx=3, $cnt = count($splits); $idx<$cnt; $idx++) {
                if(($idx-1) % 2 == 0) {
                    //Is even, is key
                    $keys[] = $splits[$idx];
                } else {
                    //Is odd, is value;
                    $values[] = $splits[$idx];
                }
            }
            if(count($keys) == count($values))
                $this->_params = array_combine($keys, $values);
            else{
                $this->_controller = 'errorController';
                $this->_action = 'indexAction';
            }

        }
    }

    private function init()
    {
        $this->sessionStart();
        date_default_timezone_set($this->getConfig('settings.timezone'));
        error_reporting(E_ALL);
		ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        ini_set('error_log', $this->getConfig('settings.logs_path')._DS.'error_log.txt');
    }


private function _connectDB(){
	
	$params = array();
	$params['host'] = $this->getConfig('database.host');
	$params['username'] = $this->getConfig('database.username');
	$params['password'] = $this->getConfig('database.password');
	$params['dbname'] = $this->getConfig('database.name');
	$params['dbprefix'] = $this->getConfig('database.prefix');
	
	$this->_database = Database::getInstance($params);
	
	
	
}
    
	public function getRequest()
	{
		return $this->_httpRequest;
	}
	
	
	public function getResponse()
	{
		return $this->_httpResponse;
	}

protected function sessionStart() {

	$session_name = 'PIXELSESSION';   // Set a custom session name
	$secure = false;

	// This stops JavaScript being able to access the session id.
	$httponly = true;

	// Forces sessions to only use cookies.
	if (ini_set('session.use_only_cookies', 1) === FALSE) {
		exit('Could not initiate a safe session (ini_set)');
	}

	// Gets current cookies params.
	$cookieParams = session_get_cookie_params();
	session_set_cookie_params($cookieParams["lifetime"],
	$cookieParams["path"],
	$cookieParams["domain"],
	$secure,
	$httponly);

	// Sets the session name to the one set above.
	session_name($session_name);
	session_start();            // Start the PHP session
	session_regenerate_id();    // regenerated the session, delete the old one.
}


    public function getConfig($key)
    {
        
        $keys = explode('.',$key);
        if(!is_null($this->_settings)){

            $settings = $this->_settings;
            for($i=0;$i<count($keys);$i++){

                if(isset($settings[$keys[$i]])){
                    $settings = $settings[$keys[$i]];
                }else{
                    throw new \Exception('Invalid setting');
                }


            }

            return $settings;

        }

        throw new \Exception('Invalid setting');
        
    }




public function closeDB(){
	
	if(!is_null($this->_database)){
		$this->_database->dbh = NULL;
		$this->_database = NULL;
	}
	
	
}



public function getLangISO(){
	return $this->_langISO;
}




public function setMessage( $msg, $status){
	
	$message = new Session('statusMessage');	
	$message->setData('message', $msg);
	$message->setData('status',$status);
	
}


public function setStatusMessageBlock(){

	
	$messageBox = new Session('statusMessage');
	
	$status = $messageBox->getData('status');
	$message = $messageBox->getData('message');
	$messageBox->clearData();
	
	if(!is_null($status) && !is_null($message))
		$this->setBlock('messageBlock',array('message'=>$message,'status'=>$status));
	
}



public function route(){
	
	
	if(class_exists('Tlab\\Controllers\\'.$this->getController())) {
       $rc = new \ReflectionClass('Tlab\\Controllers\\'.$this->getController());
       if($rc->isSubclassOf('Tlab\\Libraries\\Controller') && $rc->hasMethod($this->getAction())) {
       		$this->invokeAction($rc);
		}else{
			$this->invokeNotFoundAction();
		}
	}else{
		$this->invokeNotFoundAction();
	}

}


private function invokeAction($rc)
{
	$controller = $rc->newInstance();
	$method = $rc->getMethod($this->getAction());
	$method->invoke($controller,$this, $this->_httpRequest, $this->_httpResponse);
}


private function invokeNotFoundAction()
{
	//NÃO EXISTE ACTION
	$this->_controller = 'errorController';
	$this->_action = 'indexAction';
	$this->_httpCode = 'HTTP/1.0 404 Not found';
	$rc = new \ReflectionClass('Tlab\\Controllers\\' . $this->getController());
	$controller = $rc->newInstance();
	$method = $rc->getMethod($this->getAction());
	$method->invoke($controller);
}

public function getController() {
	return $this->_controller;
}

public function getAction() {
	return $this->_action;
}

public function getBody() {
	return $this->_body;
}

public function setBody($body) {
	$this->_body = $body;
}

public function getDatabaseInstace(){
	return $this->_database;
}

function setBlock($blockName, $arg = NULL){

	$view = new View();
	if(!is_null($arg))
		foreach($arg as $key=>$value)
			$view->$key = $value;		

	$result = $view->render($this->_template, $blockName);
	$this->setHeadTags($view->getHead());
	$this->_blockList[$blockName] = $result;
}


function getBlock($blockName){
	
	if(isset($this->_blockList[$blockName]))
		return $this->_blockList[$blockName];
	else
		return '';
	
}

function clearBlock($blockName){

	if(isset($this->_blockList[$blockName]))
        unset($this->_blockList[$blockName]);
	
}


public function setToken(){

	$token = uniqid(rand(1000,9999));
	$tokenSession = new Session('securityToken');
	$tokenSession->setData('token', $token);
	return $token;

}


public function getToken(){

	$tokenSession = new Session('securityToken');
	$token = $tokenSession->getData('token');
	return $token;

}


public function jumpTo($controller, $action = NULL, $params = NULL){
	
	$this->_controller = $controller;
	if(is_null($action))
		$this->_action = 'index';
	else
		$this->_action = $action;

	if(!is_null($params) && is_array($params) && count($params) > 0 && (count($params)%2) == 0)
		$params = '/'.implode('/', $params);	
	else
		$params = '';	

	$this->closeDB();
	header("LOCATION: "._CONFIG_LIVE_SITE."/".$this->_langISO."/".$this->_controller."/".$this->_action.$params);
	exit;	

}


public function linkTo($controller, $action = NULL, $params = NULL){
	
	if(is_null($action))
		$action = 'index';
	
	if(!is_null($params) && is_array($params) && count($params) > 0 && (count($params)%2) == 0)
		$params = '/'.implode('/', $params);
	else
		$params = '';
	
	return _CONFIG_LIVE_SITE.'/'.$this->_langISO.'/'.$controller.'/'.$action.$params;
	
}


public function setHeadTags($str)
{
	$this->_headTags[] = $str;
} 

public function getHeadTags()
{
	
	$result = '';
	if(count($this->_headTags))	
		foreach($this->_headTags as $item)
			$result .= $item;
		
	return $result;
}


public function setFooterTags($str){

	$this->_footerTags[] = $str;

}

public function getFooterTags(){

	$result = '';
	if(count($this->_footerTags))
		foreach($this->_footerTags as $item)
		$result .= $item;

	return $result;
}



public function setMetaTags($name, $content){
	
    $this->_metaTags[$name] = $content;	
}


public function getMetaTags(){
	
	$str = '';
	if(is_null($this->_metaTags) || !is_array($this->_metaTags) || !count($this->_metaTags)){
		$this->_metaTags['keywords'] = _META_KEYWORDS;
		$this->_metaTags['description'] = _META_DESCRIPTION;
	}
	
	foreach($this->_metaTags as $k=>$v)
	      $str .= '<meta name="'.$k.'" content="'.$v.'" />'.chr(10);
		
		
		
	return $str;
}


public function setTitle($title){
	
	$this->_pageTitle = $title;
	
}



public function getTitle(){
	
	if(is_null($this->_pageTitle))
	   return _TITLE;
	elseif(trim($this->_pageTitle) == '')
        return _TITLE;
    else
        return $this->_pageTitle; //.' - '._TITLE;
	
}


public function setTemplate($template){
	$this->_template = $template;		
}

public function getTemplate(){
	return $this->_template;
}


public function setLayout($layout){

	$this->_layout = $layout;
	
}


public function getLayout(){
	
	return $this->_layout;	
	
}






public function run()
{
	$this->route();
	$this->Output();
	$this->closeDB();
}






public function Output(){
	
	if(! $this->_httpResponse instanceof Response){
		throw new \Exception('Bad Response Object');
	}
	
	ob_start();
	include(_CONFIG_TEMPLATE_PATH._DS.$this->_template._DS.$this->_layout.'.phtml');
	$content = ob_get_clean();
	
	$this->_httpResponse->setContent($content);
	$this->_httpResponse->send();
		
	
}

    
}
