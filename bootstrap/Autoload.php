<?php	
	require "LoadDatabase.php";
	class Autoload extends LoadDatabase{
		public $conn;
		public function __construct(){

			$this->conn = new LoadDatabase; 			
			
		}
	}