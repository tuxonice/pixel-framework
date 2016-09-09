<?php
namespace Tlab\Libraries;


use Tlab\AppBoot;

class Authentication{
	
	protected $loginEmail = null;
	protected $loginPassword = null;
	protected $loginAt = null;
	protected $isLogged = null;
	protected $authSession = null;
	protected $databaseConnection = null;
	
	public function __construct(Database $database){
		
		$this->authSession = new Session('authPixel');
		$this->loginEmail = $this->authSession->getData('loginEmail');
		$this->databaseConnection = $database->getConnection();
		
	}
	
	
	public function isLogged(){
		
		if(is_null($this->loginEmail))
			return false;
		
		return true;
	}
	
	
	public function checkUserLogin($email, $password){
		
		if(!$this->isLogged()){
			if($this->Validate($email,$password)){
				$this->authSession->setData('loginEmail', $email);
				return true;
			}else{
				$this->loginEmail = null;
				return false;
			}
			
		}
		return true;
	}
	
	

	
	protected function Validate($email, $password){
	
		$password = md5($password);
		
		$user = $this->databaseConnection->table('users')->select('id')->where('email','=',$email)->where('password','=',$password)->first();

		if(!is_null($user) && is_numeric($user->id)){
			return true;
		}

		return false;
	}
	
}