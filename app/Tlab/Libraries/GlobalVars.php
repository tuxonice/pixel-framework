<?php
namespace Tlab\Libraries;

class GlobalVars{
    
private $vars = NULL;   
    
    
    
static $_instance;
    
    
public static function getInstance($files = NULL){
    
        
        if((self::$_instance instanceof self))
            return self::$_instance;
        elseif(!is_null($files))
            self::$_instance = new self($files);
        else
            die('Error!');
            
    return self::$_instance;
}




private function __construct($files){
    
foreach($files as $file){
	if(file_exists($file))
		include($file);
}    


$this->vars = get_defined_vars();

    
}
    


public function getData($key = NULL){
    
if(is_null($key))
    return $this->vars;
 
if(isset($this->vars[$key]))
    return $this->vars[$key];
else
    return NULL;    
        
}
}
