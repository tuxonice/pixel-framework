<?php
namespace Tlab\Libraries;
use Symfony\Component\HttpFoundation\Response;

use Tlab\AppBoot;


class Controller{
	
	protected $app = null;
	protected $httpResponse = null;
	
public function	__construct(AppBoot $app){
	
	$this->app = $app;
	$this->httpResponse = new Response();
	                
}

protected function Render($file, $params = null){
	
	$content = $this->app->render($file,$params);
	return $this->httpResponse->setContent($content);
	
}

}
