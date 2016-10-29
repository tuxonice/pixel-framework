<?php
namespace Tlab\Libraries;

use Symfony\Component\HttpFoundation\Request;
use Ospinto\dBug;

class Routing {
    
    protected $controller = null;
    protected $action = null;
    protected $defaultlangISO = NULL;
    
    
    public function __construct(Request $request, $defaultlangISO){
        
        $this->defaultlangISO = $defaultlangISO;
                
        $splits = explode('/', trim($_SERVER['REQUEST_URI'],'/'));
        
        $validLang = false;
        if(array_key_exists(0, $splits))
            $validLang = $this->processLanguage($splits);
        
            $this->controller = $this->processController($splits);
            $this->action = $this->processAction($splits);
            
        
            if(!$validLang){
                $this->controller = 'errorController';
                $this->action = 'indexAction';
            }
        
        
        
    }
    
    
    
    protected function processLanguage($splits)
    {
        $splits[0] = preg_replace("/[^A-Za-z0-9\-]/", '', urldecode($splits[0]));
        $validLang = true;
        switch($splits[0]){
            case 'en':
                $this->_langISO = 'en';
                break;
            case '':
                $this->_langISO = $this->defaultlangISO;
                break;
            default:
                $this->_langISO = $this->defaultlangISO;
                $validLang = false;
    
        }
    
        return $validLang;
    }
    
    protected function processController($splits)
    {
        return  !empty($splits[1])?$splits[1].'Controller':'indexController';
    }
    
    
    protected function processAction($splits)
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
    
    
    public function getController(){
        return $this->controller;
    }
    
    public function getAction(){
        return $this->action;
    }
    
    
}