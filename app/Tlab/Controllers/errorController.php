<?php
namespace Tlab\Controllers;

use Tlab\Libraries\Controller;
use Symfony\Component\HttpFoundation\Request;


class errorController extends Controller{
	
	
	public function indexAction(Request $request){

		$templateData = array();
    	return $this->Render('default/error/index.twig', $templateData)->setStatusCode(404, 'HTTP/1.0 404 Not found');
}
	
	
}
