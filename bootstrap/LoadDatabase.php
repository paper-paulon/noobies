<?php
	
	class LoadDatabase {
		public $_mysqli;
		private $config;
		public function __construct(){
			$this->config = require (__DIR__ . "/../config/Config.php");
						
			$this->_mysqli = new mysqli($this->config['host'], $this->config["username"], $this->config["password"], $this->config["dbname"]);
			
			if ($this->_mysqli->connect_errno) {
				echo "Failed to connect to MySQL: (" . $this->_mysqli->connect_errno . ") " . $this->_mysqli->connect_error;
			}
		
			return $this->_mysqli;
		}
	}