<?php 


class profile_controller extends controller{
	// permlevel = 1 : user is current user who is logged in. 2 : user is someone else 
	private $permlevel;
	
	public function __construct($args = array()){
		require_once('page.php');
		require_once(VIEW.'page.php');
		require_once(MODEL.'page.php');
		$this->model = new profile_model;
		$this->view = new profile_view;
		
		$this->check_login_final();
		
		if(!isset($args[0]) || empty($args[0])){
			$uid = $_SESSION['uid'];
		}
		if($args[0] != $_SESSION['uid']){
			$this->permlevel = 2;
			$uid = $args[0];
			if($this->validate_user_id($uid) == TRUE){
				$this->view->show_uid($uid);
			}else{
				errorocc("Invalid user id"); 
			}
		}else{
			$this->permlevel = 1;
			$uid = $_SESSION['uid'];
		}
		$this->show_content($uid);
		if(isset($args[1])){
			if($args[1] == "pages"){
				$this->show_pages($uid);
				
				
			}elseif($args[1] == "pagesliked"){
				
					$this->show_pages_voted($uid, '1');
				
			}elseif($args[1] == "pagesdisliked"){
				$this->show_pages_voted($uid, '0');
				
				
			}
		}
	}
	public function show_content($uid){
		
		$this->view->show_uid($uid);
		
		
	}
	public function show_pages($uid){
		$this->view->set_print_profile(TRUE);
		$page = new page_controller(array('showuserpages', $uid));
	}
	public function show_pages_voted($uid, $lod){
		$this->view->set_print_profile(TRUE);
		$page = new page_controller(array('showuserpagesvoted', $uid, $lod));
	}
	public function validate_user_id($uid){
		$uid = validate_user_id($uid);
		if($uid != FALSE){
			return $this->check_uid_exists($uid);
		}else{
			return FALSE;
		}
	}
}