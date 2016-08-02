<?php
namespace Tlab\Controllers;

use Tlab\AppBoot;
use Tlab\Libraries\Controller;
use Tlab\Libraries\View;

class indexController extends Controller
{


public function indexAction(AppBoot $app, $request, $response){

	 /** @var AppBoot $app */
     //$app = AppBoot::getInstance();
     $database = $app->getDatabaseInstace();
     $langISO = $app->getLangISO();

     
     var_dump($request);
     echo('-----------------');
     var_dump($response);
          
     $view = new View();
     $view->langISO = $langISO;
     $result = $view->render($app->getTemplate(),'index','index');
     $app->setBody($result);
     $app->setHeadTags($view->getHead());

}

    
}

