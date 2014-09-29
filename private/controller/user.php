<?php
class user_controller extends controller{
	public function __construct($args = array()){
		$this->model = new user_model;
		$this->view = new user_view;
		if($args[0] == "user_stats"){
			$this->user_stats_nav();
		}
		else{
		$this->check_login_final();
		$this->pgs = $this->page_generate($args);
		if(!isset($args[0]) || empty($args[0])){
			$args[0] = "profile";
			$this->show_profile($_SESSION['uid']);
		}
		if($args[0] == "profile"){
			if(!isset($args[1]) || empty($args[1])){
				$uid = $_SESSION['uid'];
			}elseif($this->validate_uid($args[1]) == FALSE){
				$uid = $_SESSION['uid'];
			}else{
				$uid = $args[1];
			}
			$this->show_profile($uid);
		}elseif($args[0] == "settings"){
			$this->show_settings();
		}elseif($args[0] == "favourite"){
			if(!isset($args[1]) || empty($args[1])){
				$this->print_err_msg("Invalid request");
			}
			if($this->validate_uid($args[1]) == FALSE){
				errorocc("Invalid request");
			}
			$this->favourite_user($args[1], "add");
		}
		elseif($args[0] == "unfavourite"){
			if(!isset($args[1]) || empty($args[1])){
				$this->print_err_msg("Invalid request");
			}
			if($this->validate_uid($args[1]) == FALSE){
				errorocc("Invalid request");
			}
			$this->favourite_user($args[1], "rm");
		}elseif($args[0] == "showfavourites"){
			if(!isset($args[1]) || empty($args[1])){
				$ob = "byuser";
			}else{
				$ob = $args[1];
			}
			if(!isset($args[2]) || empty($args[2]) || $this->validate_uid($args[2]) == FALSE){
				$uid = $_SESSION['uid'];
			}else{
				$uid = $args[2];

			}
			
			$this->show_favourites($ob, $uid);
		}else{
			errorocc("404: Link not found");
		}
		}
	}
	private function show_favourites($ob, $uid){
		$this->view->print_fav_head($ob, $uid);
		$st = $this->model->retrieve_favourites(array('st'=>$this->pgs['st'], 'et'=>$this->pgs['et']), $ob, "default", $uid);
		if($st == FALSE){
			$this->view->page_no_results();
		}else{
			$this->print_items($st, $this->pgs['limit'], $this->pgs['nxt_st'], "us");
		}
	
	}
	private function show_profile($uid){
		$data = $this->model->retrieve_user($uid);
		
		$this->view->show_profile($uid, $data['bio'], $this->model->retrieve_favourites(FALSE, "user", "count", $uid), $this->model->retrieve_favourites(FALSE, "byuser", "count", $uid));
		$this->view->print_lits_head();
		$st = $this->model->retrieve_lits($uid, array('st'=>$this->pgs['st'], 'et'=>$this->pgs['et']));
		if($st->rowcount() == 0){
			$this->view->no_results();
		}else{
			$this->print_items($st, $this->pgs['limit'], $this->pgs['nxt_st'], "lt");
		}
		$this->view->print_end($uid);
	}
	private function show_settings(){
		$data = $this->model->retrieve_user($_SESSION['uid']);
		if(!isset($_POST) || empty($_POST)){
			$data['pass'] = NULL;
			$this->view->show_settings_form($data);
		}else{
			if(!isset($_POST['mail']) || empty($_POST['mail'])){
				$this->print_err_msg("Please give us a mail id");
			}
			if(validate_user_email($_POST['mail']) == FALSE){
				$this->print_err_msg("Please enter a valid mail!");
			}
			if((empty($_POST['pass1']) || !isset($_POST['pass1'])) xor (empty($_POST['pass2']) || !isset($_POST['pass2']))){
				$this->print_err_msg("Please fill in both password fields!");
			}
			if((empty($_POST['pass1']) || !isset($_POST['pass1'])) && (empty($_POST['pass2']) || !isset($_POST['pass2']))){
				$pass = $data['pass'];
			}else{
				$pass = SHA1($_POST['pass2']);
			}
			if($_POST['pass1'] !== $_POST['pass2']){
				$this->print_err_msg("Your passwords do not match!");
			}
			$_POST['private'] = 0;
			
			if(!isset($_POST['shownsfw']) || empty($_POST['shownsfw'])){
				$_POST['shownsfw'] = 0;
			} 
			if(!isset($_POST['bio']) || empty($_POST['bio'])){
				$_POST['bio'] = "";
			}
			if($data['mail'] === $_POST['mail']){
				$chk_mail = FALSE;
			}else{
				$chk_mail = TRUE;
			}
			if($this->model->update_user($_POST, $pass, $chk_mail) == TRUE){
				$this->view->print_sc_msg();
				//print_r($_POST);
			}
			
		}
	}
	protected function validate_uid($uid){
		$rtn = TRUE;
		if(strlen($uid) >25){
			$rtn = FALSE;
		}
		if($this->check_uid_exists($uid) == FALSE){
			$rtn = FALSE;
		}
		return $rtn;
	}
	private function favourite_user($uid, $type){
		if($type == "add"){
			if($uid == $_SESSION['uid']){
				$this->print_err_msg("You cannot favourite yourself!");
			}
			if($this->model->check_fav($uid) == TRUE){
				if($this->model->fav($uid, "undo")){
					$this->view->print_sc_msg("You have un-favourited this user");
				}
				//$this->favourite_user($uid, "rm");
				//$this->print_err_msg("You've already favourited this user");
			}else{
				if($this->model->fav($uid, "do")){
					$this->view->print_sc_msg("You have favourited this user");
				}
			}
		}elseif($type == "rm"){
			if($uid == $_SESSION['uid']){
				$this->print_err_msg("You cannot unfavourite yourself!");
			}
			if($this->model->check_fav($uid) == FALSE){
				$this->print_err_msg("You havent favourited this user");
			}
			
		}
	}
	private function user_stats_nav(){
		$data = array(
			'nolits'=>$this->model->ustats('nolits'),
			'nocritiqued'=>$this->model->ustats('nocritiqued'),
			'noreportsagainst'=>$this->model->ustats('noreportsagainst'),
			'noreportsby'=>$this->model->ustats('noreportsby'),
			'nofeedbacksrequested'=>$this->model->ustats('nofeedbacksrequested'),
			'nofeedbacksrequestedby'=>$this->model->ustats('nofeedbacksrequestedby')
		);
		$this->view->stats_nav_generate($data);
	}
}
