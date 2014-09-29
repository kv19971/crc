<?php
include('literature.php');
		
		
class comment_controller extends literature_controller{
	public $page_obj;
	public $lit_id;
	public function __construct($args){
		$this->model = new comment_model;
		$this->check_login_final();
		$this->view = new comment_view;
		if($this->validate_lit_id($args[0]) == FALSE){
				errorocc("The lit does not exist");
		}
		$this->lit_id = $args[0];
		if(!isset($args[1])){
			$args[1] = "view";
		}
		if(!check_existince($args[0])){
				errorocc("Invalid Request");
		}
		$this->pgs = $this->page_generate($args);
		//print_r($this->pgs);
		
			
			if($args[1] == "add"){
				if(!isset($args[2]) || $args[2] == "new"){
					$args[2] = "new"; 
				}else{
					if($this->retrieve_single_comment($args[2]) == FALSE){
						errorocc("Invalid Request");
					}
				}
				if(empty($_POST)){
					$this->view->ae_comment_form("add", array("lit_id"=>$args[0], "reply_id"=>$args[2]));
				}else{
					if(strlen($_POST['comment']) > 300){
						$this->print_err_msg("Your comment is too long!");
					}
					// generate comment id
					$comment_id = $_SESSION['uid']."_".time()."_ltcmnt";
					if($this->model->comment_op("insert", array("comment_id"=>$comment_id, "lit_id"=>$args[0],"comment"=>$_POST['comment'], "reply_id"=>$args[2]))){
						$this->view->print_sc_msg("Success!");
					}
				}
			}elseif($args[1] == "edit"){
				if(!check_existince($args[2])){
					errorocc("Nothing to edit!!");
				}
				$prev_cmnt_data = $this->retrieve_single_comment($args[2]);
				if($prev_cmnt_data == FALSE){
					errorocc("Invalid Request");
				}
				
				if($prev_cmnt_data['uid'] != $_SESSION['uid']){
					errorocc("Not your comment!");
				}
				if(!isset($_POST) || empty($_POST)){
					$this->view->ae_comment_form("edit", array("lit_id"=>$args[0], 'comment_id'=>$args[2], 'prev_comment'=>$prev_cmnt_data['comment']));
				}else{
					if(empty($_POST['comment'])){
						$this->print_err_msg("Please enter a comment!");
					}
					if(strlen($_POST['comment']) > 300){
						$this->print_err_msg("Comment is too long");
					}
					if($this->model->comment_op("edit", array("comment"=>$_POST['comment'], "comment_id"=>$args[2]))){
						$this->print_sc_msg("Comment edited!");
					}
				}
			}elseif($args[1] == "delete"){
				if(!check_existince($args[2])){
					errorocc("Nothing to delete!!");
				}
				$cmnt = $this->retrieve_single_comment($args[2]);
				if($cmnt == FALSE){
					errorocc("Invalid Request");
				}
				if($cmnt['uid'] != $_SESSION['uid']){
					errorocc("This isn't your comment!");
				}
				if($this->model->comment_op("delete", array("comment_id"=>$args[2]))){
						$this->print_sc_msg("Comment deleted!");
				}
			}elseif($args[1] == "view"){
				$this->view->print_comment_head($args[0]);
				if(!isset($args[2]) || empty($args[2]) || !$this->retrieve_single_comment($args[2])){
					$this->get_comments(array('lit_id'=>$args[0]), $this->pgs);
				}else{
					$this->get_comments(array('comment_id'=>$args[2]), $this->pgs);
				}
				
				
				/*
				if(check_existince($args[3])){
					if($this->retrieve_single_comment($args[3]) == FALSE){
						// show all comments 
						//$this->print_err_msg("Invalid Request");
					}
					// navigate to comment with that id
				}else{
					// show all comments 
				}
				*/
			}elseif($args[1] == "vote"){
				if(!check_existince($args[2])){
					$args[2] = 2;
				}
				if(!check_existince($args[3])){
					errorocc("Invalid request");
				}
				$cmnt = $this->retrieve_single_comment($args[3]);
				if($cmnt == FALSE){
					errorocc("Invalid Request");
				}
				
				if($this->model->comment_vote("chk", $args[3])){
					$this->model->comment_vote("rm", $args[3]);
					$this->view->print_sc_msg("Your vote has been removed from this content");
				}else{
					if($this->model->comment_vote($args[2], $args[3])){
						if($args[2] == 2){
							$sc = "You have upvoted this content";
						}else{
							$sc = "You have downvoted this content";
						}
						$this->view->print_sc_msg($sc);
					}
				}
				
			}
			
			
		}
	private function get_comments($data, $pgs = array('st'=>0, 'et'=>5, 'nxt_st'=>6, 'limit'=>5)){
		
		$sql = $this->model->retrieve_comments($data, array('st'=>$pgs['st'], 'et'=>$pgs['nxt_st']));
		if($sql != FALSE){
			
		$this->view->print_comment_list('start');
		
		
		$inc = 0;
		if(isset($data['lit_id'])){
			
			foreach($sql as $row){
				$inc = $inc+1;
				$this->view->print_comment($row);
				$this->get_comments(array('comment_id'=>$row['comment_id']));
				if($inc>=$pgs['limit']){
					break;
				}
			}
		}elseif(isset($data['comment_id'])){
		
			foreach($sql as $row){
				$inc = $inc+1;
				$this->view->print_comment($row);
				$this->get_comments(array('comment_id'=>$row['comment_id']));
				if($inc>=$pgs['limit']){
					break;
				}
			}
		}
		
		$this->view->print_comment_list('end');
		if($inc>=$pgs['limit']){
			if(isset($data['comment_id'])){
				
				$_SERVER['REQUEST_URI'] = "/crc/comment/{$this->lit_id}/view/{$data['comment_id']}";
			}
			$this->view->page_nxt_generate($pgs['nxt_st']);
		}else{
			$this->view->page_no_results();
		}
		}
	}
	
	private function retrieve_single_comment($id){
		
		if(substr($id, -6) != "ltcmnt" || strlen($id) >43){
			$rtn= FALSE;
		}else{
			$cmnt = $this->model->retrieve_single_comment($id);
			if($cmnt == FALSE){
				$rtn = FALSE;
			}else{
				$rtn = $cmnt;
			}
		}
		return $rtn;
		
	}
}
	
