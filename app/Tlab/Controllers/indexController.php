<?php
namespace Tlab\Controllers;


use Tlab\Libraries\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Capsule\Manager as Capsule;

class indexController extends Controller
{

/**
 * 
 * @param Request $request
 * @return Response
 */
public function indexAction(Request $request){

	 $database = $this->app->getDatabaseInstace();
     $langISO = $this->app->getLangISO();


     $templateData = array('langISO'=>$langISO,'name'=>'Fabien');
     return $this->Render('default/index/index.twig', $templateData);
         
}

    /**
     * @param Request $request
     * @param array $params
     * @return Response
     */
    public function menuAction(Request $request, $params = array()){
    
    
    $templateData = array();
     return $this->Render('default/menu.twig', $templateData);
    
    

    }
    
    
    public function testAction(Request $request){
        
        
        Capsule::schema()->create('orders', function($table)
        {
            $table->increments('id');
            $table->string('title');
        });
        
        exit;
    }
    
}
