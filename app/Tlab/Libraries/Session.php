<?php
namespace Tlab\Libraries;

class Session{
	
private $_namespace = NULL;
	
	
public function __construct($namespace){
	
$this->_namespace = $namespace;
	if(session_id() == '')
		session_start();
	
	if(!isset($_SESSION[$namespace]))
		$_SESSION[$namespace] = array();
	
}	
	

public function getData($key, $default = NULL){
	
if(isset($_SESSION[$this->_namespace][$key]))
	return 	$_SESSION[$this->_namespace][$key];
else
	return $default;
}


public function setData($key, $value){
	
	$_SESSION[$this->_namespace][$key] = $value;
}


public function clearData($key = NULL){
	
if(is_null($key)){
	foreach($_SESSION[$this->_namespace] as $k=>$v)
		unset($_SESSION[$this->_namespace][$k]);
}else
	unset($_SESSION[$this->_namespace][$key]);
	

	
}

}



