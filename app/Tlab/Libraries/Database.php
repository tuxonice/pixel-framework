<?php
namespace Tlab\Libraries;
use Tlab\Libraries\TlabPDO;


class Database {
	
	public $dbh = NULL;
	static $_instance;
	
	
	
	public static function getInstance($config = NULL){
	
		if((self::$_instance instanceof self))
			return self::$_instance;
		elseif(!is_null($config))
			self::$_instance = new self($config['host'],
				 $config['username'],
				 $config['password'], 
				 $config['dbname'],
				 $config['dbprefix']);
		else
			die('Error Connecting to DB!');
			
	return self::$_instance;
	}
	
	

    private	function __construct( $host='localhost', $user, $pass, $db='', $table_prefix='') {
		
		
	   try{
	       $this->dbh = new TlabPDO("mysql:host=$host;dbname=$db", $user, $pass, $driver_options = array(), $table_prefix);
	       $this->dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
	   }catch(PDOException $e){
	   	
	   	echo($e->getMessage());
	   	exit;
	   	
	   }
		
        $this->dbh->exec("SET NAMES 'utf8'");
		
	}
	

	
    
    
}
