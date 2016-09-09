<?php
namespace Tlab\Libraries;
use Symfony\Component\HttpFoundation\Response;

use Tlab\AppBoot;


class Controller{
	
	protected $app = null;
	protected $httpResponse = null;
	protected $authentication = null;
	
public function	__construct(AppBoot $app){
	
	$this->app = $app;
	$this->httpResponse = new Response();
	                
}

protected function Render($file, $params = null){
	
	$content = $this->app->render($file,$params);
	return $this->httpResponse->setContent($content);
	
}


	protected function gateKeeper($role = array())
	{

		$this->authentication = $this->app->getContainer()->get(Authentication::class);
		return $this->authentication->isLogged();


	}


	protected function authenticate($email, $password)
	{

		if(is_null($this->authentication)){
			$this->authentication = $this->app->getContainer()->get(Authentication::class);
		}

		return $this->authentication->checkUserLogin($email, $password);
	}


}
