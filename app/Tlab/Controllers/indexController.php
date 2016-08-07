<?php
namespace Tlab\Controllers;

use Tlab\Libraries\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class indexController extends Controller
{

/**
 * 
 * @param Request $request
 * @param Response $response
 */
public function indexAction(Request $request, Response $response){

	 $database = $this->app->getDatabaseInstace();
     $langISO = $this->app->getLangISO();

     /*
     echo('<pre>');
     var_dump($request);
     echo('-----------------');
     var_dump($response);
     echo('</pre>');
     */
     
     $templateData = array('langISO'=>$langISO,'name'=>'Fabien');
     return $this->app->render('default/index/index.twig', $templateData);
         
}

    

}

