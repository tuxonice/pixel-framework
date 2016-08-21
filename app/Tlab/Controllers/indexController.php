<?php
namespace Tlab\Controllers;


use Tlab\Libraries\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Capsule\Manager as Capsule;

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
     
     //Capsule::schema()
     /*
     Capsule::schema::create('flights', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('airline');
            $table->timestamps();
        });
     */
     //$tags = $database->table('tags')->where('id', '>', 100)->get();
     //new dBug($tags);
     
     
     $templateData = array('langISO'=>$langISO,'name'=>'Fabien');
     return $this->Render('default/index/index.twig', $templateData);
         
}

public function menuAction(Request $request, $params = array()){
    
    
    $templateData = array();
     return $this->Render('default/menu.twig', $templateData);
    
    

}
}
