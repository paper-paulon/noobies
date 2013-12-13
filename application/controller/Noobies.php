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
			$this->view['search_by'] = @$params[1];
			switch(@$params[1]){
				case "alpha":
					if(isset($params[2]) && $params[2] != ""){
						$searchBy = array();
						$searchBy = explode("-",$params[2]);
						if(count($searchBy) > 0){
							$qstr = "select g.*, count(s.id)as sitecount from girls g left join scenes s on s.girls_id = g.id where g.girls_name";
							foreach($searchBy as $k=>$v){
								$qstr .= " like '" . $v . "%'";
								if(($k+1) < count($searchBy)){
									$qstr .= " or g.girls_name";
								}
							}					
							$qstr .= " group by g.id ORDER BY g.girls_name limit 20;";
							$this->view['content_girls'] = Girls::getByAlpha($qstr);
						}else{
							$this->view['content_girls'] = Girls::getRandom(20);
						}
					}else{
						$this->view['content_girls'] = Girls::getRandom(20);
					}
					break;
				case "tags":
					if(isset($params[2]) && $params[2] != ""){
						
						$searchBy = $params[2];
						
						if(trim($searchBy) != ""){
							$qstr = "select g.*, count(s.id)as sitecount from girls g left join scenes s on s.girls_id = g.id where g.tags";
							
							$qstr .= " like '%" . $searchBy . "%'";
								
							$qstr .= " group by g.id limit 20;";
							
							$this->view['content_girls'] = Girls::getByTags($qstr);
						}else{
							$this->view['content_girls'] = Girls::getRandom(20);
						}						
					}else{
						$this->view['content_girls'] = Girls::getRandom(20);
					}
					break;
				case "sites":
					if(isset($params[2]) && $params[2] != ""){
						
						$searchBy = $params[2];
						
						if(trim($searchBy) != ""){
							$qstr = "select g.*, count(s.id)as sitecount from girls g left join scenes s on s.girls_id = g.id where g.primary_photo_site_abbr";
							
							$qstr .= " = '" . $searchBy . "'";
								
							$qstr .= " group by g.id limit 20;";
							
							$this->view['content_girls'] = Girls::getBySites($qstr);
						}else{
							$this->view['content_girls'] = Girls::getRandom(20);
						}						
					}else{
						$this->view['content_girls'] = Girls::getRandom(20);
					}
					break;
				case "search":
					if(isset($params[2]) && $params[2] != ""){
						$get = $_GET;
						if(isset($get['gname'])){						
							$searchBy = $get['gname'];
							$this->view['search_by'] = "Name - " . $searchBy;						
						}else {
							$searchBy = "";
							$this->view['search_by'] = "Invalid Parameter";						
						}	
													
						if(trim($searchBy) != ""){
							$qstr = "select g.*, count(s.id)as sitecount from girls g left join scenes s on s.girls_id = g.id where g.girls_name";
							
							$qstr .= " like '%" . $searchBy . "%'";
								
							$qstr .= " group by g.id limit 20;";
							
							$this->view['content_girls'] = Girls::getByAlpha($qstr);
							
						}else{
							$this->view['content_girls'] = Girls::getRandom(20);
						}	
						
					}else{
						$this->view['content_girls'] = Girls::getRandom(20);
					}
					
					break;
				default:
					$this->view['content_girls'] = Girls::getRandom(20);
					break;
			}
			
		}
		public function profile($params){
			include __DIR__ . "/../model/Girls.php";
			$this->view['data'] = Girls::getProfileByName(urldecode($params[1]));
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