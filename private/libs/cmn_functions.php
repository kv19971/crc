 <?php
 function check_existince($string){
		if($string == NULL || $string == FALSE || $string == "" || !isset($string) || empty($string)){
			
			$string = FALSE;
		}else{
			$string = TRUE;
			
		}
		return $string;
	}
	function check_array_existince($array){
		array_map('check_existince', $array);
		return $array;
	}
	 function validate_user_id($string){
		
		$string = filter_var($string, FILTER_SANITIZE_STRING);
		if(strlen($string) >25){
			$string = FALSE;
		}
		return $string;
	}
	function validate_user_name($string){
		
		$string = filter_var($string, FILTER_SANITIZE_STRING);
		if(strlen($string) >90){
			
			$string = FALSE;
		}
		return $string;
	}
	 function validate_user_email($string){
	
		$string = filter_var($string, FILTER_SANITIZE_EMAIL);
		if(filter_var($string, FILTER_VALIDATE_EMAIL) == FALSE || strlen($string) > 90){
		
			$string = FALSE;
		
		}
		return $string;
	}
	
	/*
	 function validate_page_id($string){
		$string = addslashes( $string);
		$string = htmlspecialchars($string);
		$string = filter_var($string, FILTER_SANITIZE_STRING);
		if(strlen($string) >37){
		
			$string = FALSE;
		}
		return $string;
	}
	
	 function validate_page_link($string){
		$string = addslashes( $string);
		if(strpos($string, 'http://') === false && strpos($string, 'https://') === false && strpos($string, 'ftp://') === false){
			$string = 'http://'.$string;
		}
		
		$string = filter_var($string, FILTER_SANITIZE_URL);
		if(filter_var($string, FILTER_VALIDATE_URL) == FALSE){
			$istrue = FALSE;
			$errstr .="There is something wrong with the link you have given<br />";
			$string = FALSE;
		}else{		
			$istrue = TRUE;
		}	
		return $string;
		
	}
	
	
	 function check_page_id_exists($string){
		$sql = new paged_database("paged_");
		if(mysqli_num_rows($sql->run_mysql_query("SELECT page_id FROM paged_page_main WHERE page_id='$string'")) == 1){
			$istrue = TRUE;
		}else{
			$istrue = FALSE;
		}
		return $istrue;
	}
	*/
	 function validate_page_link($string){
		$string = addslashes( $string);
		if(strpos($string, 'http://') === false && strpos($string, 'https://') === false && strpos($string, 'ftp://') === false){
			$string = 'http://'.$string;
		}
		
		$string = filter_var($string, FILTER_SANITIZE_URL);
		if(filter_var($string, FILTER_VALIDATE_URL) == FALSE){
			$istrue = FALSE;
			$errstr .="There is something wrong with the link you have given<br />";
			$string = FALSE;
		}else{		
			$istrue = TRUE;
		}	
		return $string;
		
	}
	 function check_page_link_exists($string){
		$sql = new paged_database("paged_");
		if(mysqli_num_rows($sql->run_mysql_query("SELECT link FROM paged_page_main WHERE link='$string'")) == 1){
			$istrue = TRUE;
		}else{
			$istrue = FALSE;
		}
		return $istrue;
	}
	 function field_common_sanitize($string){
		if(is_array($string)){
			return array_common_sanitize($string);
		}else{
			$string = trim($string);
			$string = addcslashes($string, "%_");
			$string = htmlspecialchars($string);
			return $string;
		}
	}
	function array_common_sanitize($array){
		$array = array_map('field_common_sanitize', $array);
		return $array;
	}
	function check_login(){
		if(!isset($_SESSION['uid']) || !$_SESSION['uid']){
			return FALSE;
		}else{
			// check if uid valid 
			return TRUE;
		}
	}
	
?>