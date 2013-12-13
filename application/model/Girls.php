<?php	
	class Girls{
		private static $_conn;
		private static function _buildConnection(){
			$config = require (__DIR__ . "/../../config/Config.php");
			self::$_conn = mysqli_connect($config["host"], $config["username"], $config["password"], $config["dbname"]);
			/* check connection */
			if (mysqli_connect_errno()) {
				printf("Connect failed: %s\n", mysqli_connect_error());
				return false;
			}else{
				return true;
			}
		}
		public static function getProfileByName($name){
			self::_buildConnection();
			
			$ret = array();	
			if ($result = mysqli_query(self::$_conn, "SELECT * FROM girls where girls_name='{$name}';")) {
				while($row = mysqli_fetch_assoc($result)){
					$ret[] = $row;
				}
			}			
			
			mysqli_free_result($result);
			mysqli_close(self::$_conn);
			return $ret;
		}
		public static function getAll(){
			self::_buildConnection();
			
			$ret = array();	
			if ($result = mysqli_query(self::$_conn, "SELECT * FROM girls limit 5;")) {
				while($row = mysqli_fetch_assoc($result)){
					$ret[] = $row;
				}
			}			
			
			mysqli_free_result($result);
			mysqli_close(self::$_conn);
			return $ret;			
		}
		
		public static function getRandom($limit = 0){
			self::_buildConnection();
			
			$ret = array();	
			if($limit == 0 ){
				$qstr = "SELECT  g.*, count(s.id)as sitecount FROM girls g left join scenes s on s.girls_id = g.id group by g.id ORDER BY RAND();";
			}else{
				$qstr = "SELECT  g.*, count(s.id)as sitecount FROM girls g left join scenes s on s.girls_id = g.id group by g.id ORDER BY RAND() limit {$limit} ;";
			}			
			if ($result = mysqli_query(self::$_conn,$qstr)) {
				while($row = mysqli_fetch_assoc($result)){
					$ret[] = $row;
				}
			}			
			
			mysqli_free_result($result);
			mysqli_close(self::$_conn);
			return $ret;			
		}
	}