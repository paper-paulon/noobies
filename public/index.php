<?php

	error_reporting(E_ALL);
	
	defined('CONTROLLER_START') or define('CONTROLLER_START', 1);
	defined('ACTION_START') or define('ACTION_START', 2);
	defined('BASE_INDEX') or define('BASE_INDEX', '/v1/public');
	defined('BASE_URL') or define('BASE_URL', 'http://paperskeetnoobie.local');
	defined('BASE_VERSION') or define('BASE_VERSION','/v1');
	
	//require __DIR__ . '/../bootstrap/Autoload.php';
	//$auto = new Autoload();		
	
	
	$path = dirname($_SERVER['PHP_SELF']);
	$position = strrpos($path,'/') + 1;
	
	$root_folder = substr($path,$position);
	$server_uri = $_SERVER['REQUEST_URI']; 
	if (!isset($_SERVER['REQUEST_URI'])){
		$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'],1 );
		if (isset($_SERVER['QUERY_STRING'])) { $_SERVER['REQUEST_URI'].='?'.$_SERVER['QUERY_STRING']; }
	} 
	$navString = strtolower(trim($_SERVER['REQUEST_URI'])); // Returns "/Mod_rewrite/edit/1/"
	
	if($root_folder != "")	{
		$myvar = @trim(end(explode($root_folder,$navString)));  		
	}else{
		$myvar = trim($navString);   
	}
	$myvar = trim($myvar,"/");
	$parts = explode('/', $myvar); // Break into an array	
	$page = (isset($parts[CONTROLLER_START])? filter_var($parts[CONTROLLER_START], FILTER_SANITIZE_STRING) : "noobies");
	$params = array();
	for($a=ACTION_START; $a<count($parts); $a++){
		$params[] = $parts[$a];
	}

	class Route{		
		public function  __construct($page,$params) {			
		$page = ucfirst($page);
            if(file_exists(__DIR__ . '/../application/controller/'.$page.".php")){
		
				include __DIR__ . '/../application/controller/'.$page.'.php';				
				return new $page($params);
			}else{
				echo "Controller not found";
			}
		}		
	}
    new Route($page,$params);