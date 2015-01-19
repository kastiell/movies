<?php	
	
	//���� �� �������� ����������� ��� ���������������� �����������
	abstract class Controller{
		
		protected $request;
		public $layout = 'default';
		public $defaultAction = 'list';
		public $currentUrl;
		
		function __construct(Request $request){
			$this->request = $request;
			$this->currentUri = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		}
		
		function getCurrentUri(){
			return $this->currentUri;
		}
		
		function getDefaultAction(){
			return $this->defaultAction.'Action';
		}
		
		function getRequest(){
			return $this->request;
		}
		
		//����� ��� ������ � ���������������
		//������������ �������� ������� � ���� � �������������, ������� ���������� app_name/views/
		function render($view,$data = null,$output = true,$useLayout = true){
			try{
				$basePath = App::get('base_path').'view';
				$secondPartPath = preg_replace('/\./',DIRECTORY_SEPARATOR,$view);
				$fullPath = $basePath.DIRECTORY_SEPARATOR.$secondPartPath.'.php';
				if(file_exists($fullPath)){
					if($useLayout){
						ob_start();
							include $fullPath;
							$content = ob_get_contents();
						ob_end_clean();
						$pathToLayout = $basePath.DIRECTORY_SEPARATOR.'layouts'.DIRECTORY_SEPARATOR;
						$pathToLayout.= $this->layout.'.php';
						/*
						*	Layout ������ ����� ���������� - $content!!!
						*/
						if(!file_exists($pathToLayout)){
							throw new Exception('�� ������� layout '.$pathToLayout);
						}
						ob_start();
							include $pathToLayout;
							$return = ob_get_contents();
						ob_end_clean();
						
						if($output){
							echo $return;
						}else{
							return $return;
						}
					}else{
						if($output){
							include $fullPath;
						}else{
							ob_start();
								include($fullPath);
								$include = ob_get_contents();
							ob_end_clean();
							return $include;
						}
					}
				}else{
					throw new Exception('�� ������� ������������� '.$fullPath);
				}
			}catch(Exception $e){
				echo $e->getMessage();
			}
		}
		
		public function redirect($url){
			$str = '?';
			if(is_array($url)){
				if(isset($url[0]))
					$str .= 'controller='.$url[0].'&';
				if(isset($url[1]))
					$str .= 'action='.$url[1];
					$str ='http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].$str;
			}else if($url){
				$str = $url;
			}else{
				$str = $_SERVER['HTTP_REFERER'];
			}
			if($this->getRequest()->getFeedBack()){
				$str .= '&feedback='.$this->getRequest()->getFeedBack();
			}
			header("Location: ".$str);
		}
	}
	
	
	
	
	