<?php
namespace Tlab\Libraries;
use Tlab\AppBoot;


class Controller{
	

	
public function	__construct(){
	
	$app = AppBoot::getInstance();
	$langISO = $app->getLangISO();
	$app->setBlock('footerBlock', array('langISO'=>$langISO,'controller'=>$app->getController()));
    $app->setStatusMessageBlock();
    
                
}


}
