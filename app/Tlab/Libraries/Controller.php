<?php
namespace Tlab\Libraries;
use Tlab\AppBoot;


class Controller{
	
	protected $app = NULL;
	
public function	__construct(AppBoot $app){
	
	$this->app = $app;
	                
}


}
