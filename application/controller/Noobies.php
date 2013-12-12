<?php	
	class Noobies{		
		private $default_action = 'home';
		private $data;
		private $_action;
		public function __construct($params){			
			if(count($params) == 0) $this->_action = $this->default_action;
			else $this->_action = $params[0];
			
			preg_match('/[?]/', $this->_action, $matches);				
			if(count($matches)>0) $this->_action = $this->default_action;

			if(method_exists($this, $this->_action)){
				$action = $this->_action;
				$this->$action($params);
			}else{
				echo "Invalid action";
			}
		}
		public function home($params)
		{			
			include __DIR__ . "/../model/Scenes.php";
			include __DIR__ . "/../model/Girls.php";
			
			$this->view['carousel_scenes'] = Scenes::getScenes(3);
			$this->view['random_girls'] = Girls::getRandom(2);
			$this->view['content_scenes'] = Scenes::getScenes(20);				
			
		}		
		public function girls($params){
					
			include __DIR__ . "/../model/Scenes.php";
			include __DIR__ . "/../model/Girls.php";
			
			$this->view['carousel_scenes'] = Scenes::getScenes(3);
			$this->view['random_girls'] = Girls::getRandom(2);
			$this->view['content_girls'] = Girls::getRandom(20);
		}
		public function __destruct(){			
			$content = $this->_loadView($this->view, true);
			include(__DIR__ . "/../layout/default.phtml");
		}
		private function _loadView($view, $store = false){			
			extract($view);
			ob_start();
			include(__DIR__ . "/../views/{$this->_action}.phtml");
			if($store)  return ob_get_clean();
			else        ob_end_flush();			
		}
	}