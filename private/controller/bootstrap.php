<?php
//bootstrap.php
		
		$rqst = addslashes($_SERVER['QUERY_STRING']);
		
		$rqst = htmlspecialchars($rqst);
		// prepping the query string 
		
		$rqst = trim($rqst, '/');
		$purl = explode('/', $rqst);
		// extracting required methods 
		$page = array_shift($purl);
		
		// call the home controller if query string is empty 
		if(empty($page)){
			$page = "home";
		}else{
		// if not call other controller
			$page = str_replace("url=", "", $page);
			
		}
		require_once(LIBS.'cmn_functions.php');
		if(isset($_POST) && !empty($_POST)){
			if(is_array($_POST)){
				$_POST = array_common_sanitize($_POST);
			}else{
				errorocc("Invalid data input");
			}
		}
		if(file_exists(CONTROLLER.$page.'.php')){
			require_once(VIEW.$page.'.php');
			require_once(MODEL.$page.'.php');
			$class = $page."_controller";
			$controller = new $class($purl);
		}else{
			errorocc("404: File not found");
		}
		
			
		

