<?php
namespace Tlab\Controllers;

use Tlab\Libraries\Controller;
use Tlab\Libraries\View;
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

     echo('<pre>');
     var_dump($request);
     echo('-----------------');
     var_dump($response);
     echo('</pre>');
     
     $view = new View();
     $view->langISO = $langISO;
     $result = $view->render($this->app->getTemplate(),'index','index');
     $this->app->setBody($result);
     $this->app->setHeadTags($view->getHead());

}

    
}

