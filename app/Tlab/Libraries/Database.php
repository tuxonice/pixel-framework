<?php
namespace Tlab\Libraries;
use Illuminate\Database\Capsule\Manager as Capsule;


class Database {

	
	protected $dbConnection = null;
	
	

	public function __construct($arguments)
	{

		$capsule = new Capsule;

		$capsule->addConnection([
			'driver'    => $arguments['driver'],
			'host'      => $arguments['host'],
			'database'  => $arguments['dbname'],
			'username'  => $arguments['username'],
			'password'  => $arguments['password'],
			'charset'   => $arguments['charset'],
			'collation' => $arguments['collation'],
			'prefix'    => $arguments['prefix']
		]);

		// Make this Capsule instance available globally via static methods... (optional)
		//$capsule->setAsGlobal();

		// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
		$capsule->bootEloquent();

		$this->dbConnection = $capsule->getConnection();

	}
	
	public function getInstance(){
		
		return $this;
	}

	public function getConnection(){

		return $this->dbConnection;
	}
    
}
