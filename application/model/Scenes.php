<?php	
	class Scenes{
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
		public static function getScenes($limit = 3){
			self::_buildConnection();
			if($limit <= 3) $limit = 3;
			$ret = array();	
			if ($result = mysqli_query(self::$_conn, "SELECT s.*, g.girls_name FROM scenes s left join girls g on g.id = s.girls_id ORDER BY RAND() limit {$limit};")) {
				while($row = mysqli_fetch_assoc($result)){
					$ret[] = $row;
				}
			}			
			
			mysqli_free_result($result);
			mysqli_close(self::$_conn);
			return $ret;			
		}
	}