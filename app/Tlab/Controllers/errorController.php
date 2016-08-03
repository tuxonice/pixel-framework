<?php
namespace Tlab\Controllers;

use Tlab\Libraries\Controller;
use Tlab\Libraries\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class errorController extends Controller{
	
	
	public function indexAction(Request $request, Response $response){

		$response->setStatusCode(404, 'HTTP/1.0 404 Not found');
		
		$this->app->setBlock('footerBlock', array('langISO'=>$this->app->getLangISO(),'controller'=>$this->app->getController()));
		
    	$view = new View();
    	$view->langISO = $this->app->getLangISO();
    	$result = $view->render($this->app->getTemplate(),'index','error');
    	$this->app->setBody($result);
    	$this->app->setHeadTags($view->getHead());
				
}
	
	
}
