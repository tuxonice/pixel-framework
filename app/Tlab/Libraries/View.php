<?php
namespace Tlab\Libraries;

class View extends \ArrayObject {
	
public $_head = NULL;
public $_footer = NULL;	
	
	public function __construct() {
	parent::__construct(array(), \ArrayObject::ARRAY_AS_PROPS);
}

public function render($templateName, $fileName, $controller = NULL) {
	ob_start();
	if(is_null($controller))
		include(_CONFIG_TEMPLATE_PATH._DS.$templateName._DS.'blocks'._DS.$fileName.'.phtml');
	else
		include(_CONFIG_TEMPLATE_PATH._DS.$templateName._DS.'views'._DS.$controller._DS.$fileName.'.phtml');
	return ob_get_clean();
}

public function getHead(){
	return $this->_head;	
}


public function setHead($str){
	
$this->_head = $str;
	
} 



public function getFooter(){
	return $this->_footer;
}


public function setFooter($str){

	$this->_footer = $str;

}

}

