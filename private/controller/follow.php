<?php
class follow_controller extends controller{
	public function __construct($args){
		// add validations 
		
		$this->view = new follow_view;
		$this->model = new follow_model;
		$this->check_login_final();
		if(!check_existince($args[0])){
			errorocc("Incorrect link");
		}else{
			if($args[0] == "viewfollowing"){
				$this->view_following(TRUE);
			}elseif($args[0] == "viewfollower"){
				$this->view_following(FALSE);
			}else{
				if(!validate_user_id($args[0]) || !$this->check_uid_exists($args[0])){
					errorocc("Incorrect user id");
				}
				if($args[0] != $_SESSION['uid']){
					errorocc("You cannot follow yourself!");
				}
				if(isset($args[1]) && $args[1] == "u"){
					$undo = TRUE;
				}else{
					$undo = FALSE;
				}
				$this->follow_user($args[0], $undo);
			}
		}
	}
	public function follow_user($uid, $undo){
		if($undo == FALSE){
			if($this->model->check_follow($uid) == TRUE){
				errorocc("You are already following this user!");
			}
		}else{
			if($this->model->check_follow($uid) == FALSE){
				errorocc("You aren't following this user!");
			}
		}
		if($this->model->add_follow($uid, $undo)){
			$this->view->done_follow($uid, $undo);
		}
		
	}
	public function view_following($foo){
		$this->view->show_following($foo);
		if($this->model->select_following($foo) == FALSE){
			$this->view->append_following("No one");
		}else{
			foreach($this->model->select_following($foo) as $row){
				$this->view->append_following($row['following']);
			}
			
		}
	}
}