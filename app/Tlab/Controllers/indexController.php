<?php
namespace Tlab\Controllers;

use Ospinto\dBug;

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
public function indexAction(Request $request){

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
     return $this->Render('default/index/index.twig', $templateData);
         
}

public function menuAction(Request $request, $params = array()){
    
    
    $templateData = array();
     return $this->Render('default/menu.twig', $templateData);
    
    

}
}
