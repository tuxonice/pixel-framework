<?php
namespace Tlab\Libraries;
use Tlab\AppBoot;


class Controller{
	
	protected $app = NULL;
	
public function	__construct(AppBoot $app){
	
	$this->app = $app;
	$langISO = $app->getLangISO();
	$this->app->setBlock('footerBlock', array('langISO'=>$langISO,'controller'=>$app->getController()));
    $this->app->setStatusMessageBlock();
    
                
}


}
