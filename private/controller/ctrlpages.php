<?php
// this set of scripts is for adding, editing and deleting pages ONLY 
class page_controller extends controller{
	public function __construct($args){
		if($this->check_login() == FALSE){
			errorocc("Please log in to continue");		
		}
		$this->view = new page_view;
		$this->model = new page_model;
		if(!isset($args[0])){
			errorocc("404: Link not found");
		}
		if($args[0] == "add"){
			$this->ae_page("add");
		}
		elseif($args[0] == "edit"){
			if(check_existince($args[1]) == FALSE){
				errorocc("404: Link not found");
			}
			$args[1] = $this->validate_page_id($args[1]);
			if($args[1] == FALSE){
				errorocc("Invalid Page id");
			}
			$this->ae_page("edit", $args[1]);
		}
		elseif($args[0] == "delete"){
			if(check_existince($args[1]) == FALSE){
				errorocc("404: Link not found");
			}
			$args[1] = $this->validate_page_id($args[1]);
			if($args[1] == FALSE){
				errorocc("Invalid Page id");
			}
			$this->del_page();
		}
		else{
			errorocc("404: Link not found");
		}
	}
	private function ae_page($type, $pid){
		if(!isset($_POST) || empty($_POST)){
			if($type == "edit"){
				$this->view->ae_page_view($type, $this->model->page_vals($pid));
			}else{
				$this->view->ae_page_view($type);
			}
		}
		else{
		if(in_array(FALSE, $_POST)){
			errorocc("You left one of the fields blank");
		}
		$_POST['link'] = $this->validate_page_link($_POST['link']);
		if($_POST['link'] == FALSE){
			errorocc("The URL is not valid");
		}
		if($this->check_link_exists_redir($_POST['link']) == FALSE){
			errorocc("Invalid URL");
		}
		
		if(strlen($_POST['caption'] > 300)){
			errorocc("Your page caption is too long");
		}
		$uid = $_SESSION['uid'];
		if($type == "add"){
			if($this->check_link_in_db($_POST['link']) == TRUE){
				errorocc("This URL is already in our DB");
			}
			$pid = $_SESSION['uid']."_page_".time();
			$this->extract_tags($_POST['caption'], $_SESSION['uid'], $pid);
			if($this->model->ae_page_model("add", $_POST['caption'], $pid) == TRUE){
				$this->view->print_sc_msg();
			}
		}
		elseif($type == "edit"){
			$_POST['pid'] = $this->validate_page_id($_POST['pid']);
			if($_POST['pid'] == FALSE){
				errorocc("Invalid Page id");
			}
			$pid = $_POST['pid'];
			$this->extract_tags($_POST['caption'], $_SESSION['uid'], $pid);
			if($this->model->ae_page_model("edit", $_POST['caption'], $pid) == TRUE){
				$this->view->print_sc_msg();
			}
			
			
		}
		}
		
	}
	
	private function del_page($id){
		if($this->model->check_page_id($id) != 1){
			errorocc("This page does not belong to you");
		}
		if($this->model->del_page_model($id) == TRUE){
			echo "All Done!";
		}
		
	}
	private function extract_tags($capt, $uid, $pid){
		preg_match_all("/\#(.*?)\ /",$capt." ", $caption);
		$caption = $caption[1];
		$caption = implode("'), ('{$uid}', '{$pid}' , '", $caption);
		$caption = "('{$uid}', '{$pid}' ,'".$caption." ')";
		return $caption;
			
	}
	
	private function validate_page_link($string){
		$string = addslashes($string);
		if(strpos($string, 'http://') === false && strpos($string, 'https://') === false && strpos($string, 'ftp://') === false){
			$string = 'http://'.$string;
		}
		
		$string = filter_var($string, FILTER_SANITIZE_URL);
		if(filter_var($string, FILTER_VALIDATE_URL) == FALSE){
			$string = FALSE;
		}	
		return $string;
	}
	private function check_link_exists_redir($string){
		$ch = curl_init($string);
		if(!$ch){
			return FALSE;
		}else{
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if($code == 301 || $code == 302 || $code == 303 || $code = 307){
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	private function check_link_in_db($string){
		if($this->model->link_in_db($string)< 1){
			return FALSE;
		}else{
			return TRUE;
		}
	}
	private function validate_page_id($string){
		$string = filter_var($string, FILTER_SANITIZE_STRING);
		if(strlen($string) >42){
			$string = FALSE;
		}
		if($this->model->check_page_id($string) != 1){
			$string = FALSE;
		}
		return $string;
	}
	
	
}