<?php
	error_reporting(E_ALL);
	require __DIR__ . '/../bootstrap/autoload.php';
	
	$auto = new Autoload();	
	
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
	$page = (isset($parts[1])? filter_var($parts[1], FILTER_SANITIZE_STRING) : "noobies");
	$params = array();
	for($a=2; $a<count($parts); $a++){
		$params[] = $parts[$a];
	}
	
	class Route{		
		public function  __construct($page,$params) {			
            if(file_exists(__DIR__ . '/../application/controller/'.$page.".php")){
				$page = ucfirst($page);
				include __DIR__ . '/../application/controller/'.$page.'.php';				
				return new $page($params);
			}else{
				echo "Controller not found";
			}
		}		
	}
    new Route($page,$params);