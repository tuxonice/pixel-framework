<?php
namespace Tlab\Libraries;

class Registry{


private $_registry = NULL;	
static $_instance;
	

private function __construct(){
	
if(is_null($this->_registry))
	$this->_registry = array();	

}


public static function getInstance($config = NULL){
	
		
	if((self::$_instance instanceof self))
			return self::$_instance;
		else
			self::$_instance = new self;
			
	return self::$_instance;
}


public function setData($key, $value){
	
	$this->_registry[$key] = $value;	
}


public function getData($key, $default = NULL){
	
	if(isset($this->_registry[$key]))
		return $this->_registry[$key];
	else
		return $default;	
}


}