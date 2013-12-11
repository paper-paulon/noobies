<?php	
	class Noobies{		
		private $default_action = 'Home';
		private $data;
		public function __construct($params){			
			if(count($params) == 0) $action = $this->default_action;
			else $action = $params[0];
			
			preg_match('/[?]/', $action, $matches);				
			if(count($matches)>0) $action = $this->default_action;

			if(method_exists($this, $action)){
				$this->$action($params);
			}else{
				echo "Invalid action";
			}
		}
		public function home($params)
		{
			$this->view['data'] = 'Noobies home';
		}		
		public function subindex($params){
			echo "subindex";
		}
		public function __destruct(){			
			$content = $this->_loadView($this->view, true);
			include(__DIR__ . "/../layout/default.phtml");
		}
		private function _loadView($data, $store = false){			
			extract($data);
			ob_start();
			include(__DIR__ . "/../views/noobies.phtml");
			if($store)  return ob_get_clean();
			else        ob_end_flush();			
		}
	}