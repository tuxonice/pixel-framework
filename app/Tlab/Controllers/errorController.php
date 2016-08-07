<?php
namespace Tlab\Controllers;

use Tlab\Libraries\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class errorController extends Controller{
	
	
	public function indexAction(Request $request, Response $response){

		$response->setStatusCode(404, 'HTTP/1.0 404 Not found');
		
		$templateData = array();
    	return $this->app->render('default/error/index.twig', $templateData);
}
	
	
}
