<?php
namespace Tlab\Controllers;

use Tlab\AppBoot;
use Tlab\Libraries\Controller;
use Tlab\Libraries\View;

class errorController extends Controller{
	
	
	public function indexAction(){

		/** @var AppBoot $app */
		$app = AppBoot::getInstance();
		$app->getResponse()->setStatusCode(404, 'HTTP/1.0 404 Not found');
		
		$app->setBlock('footerBlock', array('langISO'=>$app->getLangISO(),'controller'=>$app->getController()));
		
    	$view = new View();
    	$view->langISO = $app->getLangISO();
    	$result = $view->render($app->getTemplate(),'index','error');
    	$app->setBody($result);
    	$app->setHeadTags($view->getHead());
				
}
	
	
}
