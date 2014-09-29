<?php
class paged_validate{
	public $istrue;
	public $db;
	public $errstr = "Sorry! <br />";
	public function __construct(){
		$this->db = new paged_database();
	}
	public function check_existince($string){
		if($string == NULL || $string == FALSE || $string == "" || !isset($string) || empty($string)){
			$this->istrue = FALSE;
			$this->errstr .="You left a box blank<br />";
			$string = FALSE;
		}else{
			$this->istrue = TRUE;
			
		}
		return $string;
	}
	public function validate_user_id($string){
		$string = mysqli_real_escape_string($this->db->con, $string);
		$string = htmlspecialchars($string);
		$string = filter_var($string, FILTER_SANITIZE_STRING);
		if(strlen($string) >20){
			$this->istrue = FALSE;
			$string = FALSE;
			$this->errstr .="The user id you have entered is not right.<br />";
		}else{
			$this->istrue = TRUE;
			
		}
		return $string;
	}
	public function validate_page_id($string){
		$string = mysqli_real_escape_string($this->db->con, $string);
		$string = htmlspecialchars($string);
		$string = filter_var($string, FILTER_SANITIZE_STRING);
		if(strlen($string) >37){
			$this->istrue = FALSE;
			$string = FALSE;
			$this->errstr .="There is something wrong with the page id<br />";
		}else{
			$this->istrue = TRUE;
			}
		return $string;
	}
	public function validate_page_link($string){
		$string = mysqli_real_escape_string($this->db->con, $string);
		if(strpos($string, 'http://') === false && strpos($string, 'https://') === false && strpos($string, 'ftp://') === false){
			$string = 'http://'.$string;
		}
		
		$string = filter_var($string, FILTER_SANITIZE_URL);
		if(filter_var($string, FILTER_VALIDATE_URL) == FALSE){
			$this->istrue = FALSE;
			$this->errstr .="There is something wrong with the link you have given<br />";
			$string = FALSE;
		}else{		
			$this->istrue = TRUE;
		}	
		return $string;
		
	}
	public function validate_user_email($string){
		$string = mysqli_real_escape_string($this->db->con, $string);
		$string = filter_var($string, FILTER_SANITIZE_EMAIL);
		if(filter_var($string, FILTER_VALIDATE_EMAIL) == FALSE){
			$this->istrue = FALSE;
			$string = FALSE;
			$this->errstr .="There is something wrong with the email you have given<br />";
		}else{
			$this->istrue = TRUE;
		}
		return $string;
	}
	public function check_page_link_exists($string){
		$sql = new paged_database("paged_public");
		if(mysqli_num_rows($sql->run_mysql_query("SELECT link FROM paged_page_main WHERE link='$string'")) == 1){
			$this->istrue = TRUE;
		}else{
			$this->istrue = FALSE;
		}
		return $this->istrue;
	}
	public function check_page_id_exists($string){
		$sql = new paged_database("paged_public");
		if(mysqli_num_rows($sql->run_mysql_query("SELECT page_id FROM paged_page_main WHERE page_id='$string'")) == 1){
			$this->istrue = TRUE;
		}else{
			$this->istrue = FALSE;
		}
		return $this->istrue;
	}
	public function check_user_id_exists($string){
		$sql = new paged_database("paged_public");
		if(mysqli_num_rows($sql->run_mysql_query("SELECT user_id FROM paged_user_main WHERE user_id='$string'")) == 1){
			$this->istrue = True;
		}else{
			$this->istrue = FALSE;
		}
		return $this->istrue;
	}
	public function check_user_email_exists($string){
		$sql = new paged_database("paged_public");
		if(mysqli_num_rows($sql->run_mysql_query("SELECT email FROM paged_user_main WHERE email='$string'")) == 1){
			$this->istrue = True;
		}else{
			$this->istrue = FALSE;
		}
		return $this->istrue;
	}
	public function field_common_validate($string, $type = "STR", $maxlength = NULL, $minlength = NULL, $consists_of = NULL, $regexp = NULL){
		switch($type){
			case("INT"):
				if(filter_var($string, FILTER_VALIDATE_INT)){
					$this->istrue = TRUE;
				}else{
					$this->istrue = FALSE;
				}
			break;
			case("STR"):
				$string = filter_var($string, FILTER_SANITIZE_STRING);
				break;
			default:
				$string = filter_var($string, FILTER_SANITIZE_STRING);
				break;
		}
		if($maxlength != NULL && $minlength != NULL){
			if(strlen($string) >= $minlength && strlen($string) <= $maxlength){
				$this->istrue = TRUE;
			}else{
				$this->istrue = FALSE;
			}
		}
		if($consists_of != NULL){
			if(strpos($string, $consists_of)){
				$this->istrue = true;
			}else{
				$this->istrue = FALSE;
			}
		}
		if($regexp != NULL){
			if(preg_match($regexp, $string )){
				$this->istrue = true;
			}else{
				$this->istrue = false;
			}
		}
		if($this->istrue == FALSE){
			$this->errstr .="There is something wrong with the box you have filled. <br />";
		}
		
	}
	public function field_common_sanitize($string){
		$string = mysqli_real_escape_string($this->db->con, $string);
		$string = htmlspecialchars($string);
		return $string;
	}
	public function image_validate($filepath, $maxsize = 100000){
		if(getimagesize($filepath) == false){
			$this->istrue = FALSE;
		}else{
			if(filesize($filepath) > $maxsize){
				$this->istrue = FALSE;
			}else{
				$this->istrue = TRUE;
			}
		}
		if($this->istrue == false){
			unlink($filepath);
			$this->errstr .="There is something wrong with the image you have given us.<br />";
		}
		return $this->istrue;
			
	}
	public function check_page_link_online($link){
		if (!$fp = curl_init($link)){ 
			return false;
		}else{
			return true;
		}
	}
	public function get_tags($uid, $text, $pid = ""){
		preg_match_all("/\#(.*?)\ /",$text , $caption);
		$caption = $caption[1];
		$caption = implode("'), ('{$uid}', '{$pid}' , '", $caption);
		$caption = "('{$uid}', '{$pid}' ,'".$caption." ')";
		return $caption;
	}
	public function get_uid($text){
		preg_match_all("/\@(.*?)\ /",$text , $caption);
		$caption = $caption[1];
		return $caption[0];
	}
	
	
	
	
	

}



?>