<?php 

class home_controller extends controller{
	public function __construct($args = array()){
		$this->view = new home_view;
		$this->model = new home_model;
		$this->inst_sess();
		$this->pgs = $this->page_generate($args);
		if(isset($args[0]) && $args[0] == 'logout'){
			$this->logout();
		}
		if($this->check_login() == TRUE){
			$this->homehandle();
		}else{
			$this->loginhandle();
		}	
	}
	private function loginhandle(){
		
		if(!isset($_POST['login'])){
			$this->view->show_login();
		}else{
			if(in_array(FALSE, $_POST)){
				$this->print_err_msg("You left one of the fields blank!");
			}
			$_POST['uid'] = validate_user_id($_POST['uid']);
			if($_POST['uid'] == FALSE){
				$this->print_err_msg('Please enter a valid user id');
			}
			$_POST['pwd'] = SHA1($_POST['pwd']);
			print_r($_POST);
			if($this->model->login_check($_POST['uid'], $_POST['pwd']) == TRUE){
				$this->log_in($_POST['uid'], $_POST['pwd']);
			}else{
				$this->print_err_msg("Wrong username and password. Please try again");
				
			}
		}
	}
	private function homehandle(){
		$this->view->show_home();
		$this->recommended_lits();
	}
	private function recommended_lits(){
		// put this in the literature class 
		$this->view->print_head();
		$st = $this->model->recommended_lits(array('st'=>$this->pgs['st'], 'et'=>$this->pgs['nxt_st']));
		
		// abstract from here
		$this->print_items($st, $this->pgs['limit'], $this->pgs['nxt_st'], "lt");
		// to here 
	}
	private function log_in($uid, $pwd){
		session_regenerate_id(true);
		
		if($this->model->rpt_check() >= 3){
			errorocc("You cannot log in");
		}else{
			$_SESSION['uid'] = $uid;
		}
		header('Location: '.SERVER_ROOT_ONLY.'home');
	}
	private function logout(){
		session_destroy();
		header('Location: '.SERVER_ROOT_ONLY.'home');
	}
	private function display_feedbacks_complete(){
		$chk = $this->model->check_feedbacks($_SESSION['uid']);
		return $chk;
	}
}