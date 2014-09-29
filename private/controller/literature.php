<?php
class literature_controller extends controller{
	public function __construct($args = array()){
		$this->model = new literature_model;
		$this->view = new literature_view;
		$this->check_login_final();
		$this->pgs = $this->page_generate($args);
		if(empty($args)){
			$this->print_err_msg("404: Link not found");
		}
		if($args[0] == "add"){
			if(isset($args[1]) && !empty($args[1])){	
				$args[1] = 2;
			}else{
				$args[1] = 1;
			}
			$this->lit_add($args[1]);
			
		}elseif($args[0] == "delete"){
			if(!isset($args[1]) || empty($args[1])){
				errorocc("Invalid request");
			}
			if($this->validate_lit_id($args[1]) == FALSE){
				errorocc("Invalid request");
			}
			$this->lit_rm($args[1]);
		}elseif($args[0] == "feedback"){
			if(!isset($args[1]) || empty($args[1]) ){
				errorocc("Invalid request");
			}
				if(isset($args[2]) && !empty($args[2])){
				$args[2] = 2;
			}else{
				$args[2] = 1;
			}
			if($args[1] == "rand"){
				$lid = $this->lit_select_rand();
				if($lid == FALSE){
					$this->print_err_msg("No lits for you sir");
				}
			
			$this->lit_feedback($lid, $args[2]);
			}else{
				if($this->validate_lit_id($args[1]) == FALSE){
					errorocc("Invalid request");
				}
			
				$this->lit_feedback($args[1], $args[2]);
			}
		}elseif($args[0] == "view"){
			if(!isset($args[1]) || empty($args[1])){
				errorocc("Invalid request");
			}
			if($this->validate_lit_id($args[1]) == FALSE){
				errorocc("Invalid request");
			}
			$this->lit_view($args[1]);
		}elseif($args[0] == "upvote"){
			if(check_existince($args[1]) == FALSE){
				errorocc("404: Link not found");
			}
			$args[1] = $this->validate_lit_id($args[1]);
			if($args[1] == FALSE){
				errorocc("Invalid request");
			}
			$this->vote_page($args[1], "1");
		}
		elseif($args[0] == "downvote"){
			if(check_existince($args[1]) == FALSE){
				errorocc("404: Link not found");
			}
			$args[1] = $this->validate_lit_id($args[1]);
			if($args[1] == FALSE){
				errorocc("Invalid request");
			}
			$this->vote_page($args[1], "0");
		}
		elseif($args[0] == "edit"){
			if(!isset($args[1]) || empty($args[1])){
				errorocc("Invalid request");
			}
			if($this->validate_lit_id($args[1]) == FALSE){
				errorocc("Invalid request");
			}
			if(isset($args[2]) && !empty($args[2])){
				$args[2] = 2;
			}else{
				$args[2] = 1;
			}
			$this->edit_lit($args[1], $args[2]);
		}elseif($args[0] == "viewfeedbacks"){
			if(!isset($args[1]) || empty($args[1])){
				errorocc("Invalid request");
			}
			if($this->validate_lit_id($args[1]) == FALSE){
				errorocc("Invalid request");
			}
			$st = $this->model->lit_retrieve($args[1]);
			if($st['uid'] != $_SESSION['uid']){
				errorocc("Not your Lit!");
			}
			if(!isset($args[2]) || empty($args[2])){
				$this->show_feedback($args[1], "all");
			}else{
				if($this->validate_fdbk_id($args[2]) == TRUE){
					$this->show_feedback($args[1], $args[2]);
				}else{
					errorocc("Invalid Request");
				}
			}
		}elseif($args[0] == "extendfeedback"){
			if(!isset($args[1]) || empty($args[1])){
				errorocc("Invalid request");
			}
			if($args[1] == "viewrequests"){
				if(!isset($args[2]) || empty($args[2]) || $args[2] != "by"){
					$args[2] = "for";
				}else{
					$args[2] = "by";
				}
				$this->exfd_requests($args[2]);
			}elseif($args[1] == "respond"){
				if(!isset($args[3]) || empty($args[3]) || $args[3] != 2){
					$this->extend_feedback($args[2], TRUE, "RP");
				}else{
					$this->extend_feedback($args[2], FALSE, "RP");
				}
			}else{
			if(!isset($args[2]) || empty($args[2]) || $args[2] != 2){
				$this->extend_feedback($args[1]);
			}else{
				$this->extend_feedback($args[1], FALSE);
			}
			}
		}
	}
	private function exfd_requests($bf){
		$st = $this->model->retrieve_all_exfd($_SESSION['uid'], $this->pgs, $bf);
		$this->view->print_exfd_head($bf);
		$this->print_items($st, $this->pgs['limit'], $this->pgs['nxt_st'], "fdrq");
	}
	private function extend_feedback($fid, $sf = TRUE, $rp = "NO"){
		if($this->validate_fdbk_id($fid) == TRUE){
			$st = $this->model->retrieve_feedback($fid);
			if($rp == "NO"){
				if(stristr($st['lit_id'], "_", TRUE) != $_SESSION['uid']){
					errorocc("This isn't feedback for any of your lits");
				}
				if($st['uid'] == $_SESSION['uid']){
					errorocc("You cannot extend feedback by you");
				}
			}
			if($rp == "RP"){
				if($st['uid'] != $_SESSION['uid']){
					errorocc("This isn't your feedback");
				}
			}
			if(!isset($_POST) || empty($_POST) || $sf == TRUE){
				$this->view->print_exfd_form($st, $rp);
			}else{
				// process this fucker here 
				if(!isset($_POST['comment']) || empty($_POST['comment'])){
					$this->print_err_msg("Please fill in all the fields!");
				}
				// insert the fucker here
				if($this->model->insert_fd_request($_POST, $fid, $rp)){
					if($rp == "RP"){
						$this->print_sc_msg("You have posted your response");
					}else{
						$this->print_sc_msg("Your query regarding the feedback on your lit will be answered by ".$st['uid']);
					}
				}
			}
		}else{
			errorocc("Invalid Request");
		}
	}
	private function show_feedback($lid, $fdid = "all"){
		$stl = $this->model->lit_retrieve($lid);
			if($stl['uid'] != $_SESSION['uid']){
				errorocc("Not your Lit!");
			}
		if($fdid == "all"){
			$this->view->print_feedback_head("m", $stl['title']);
			$st = $this->model->retrieve_all_feedbacks($lid, array('st'=>$this->pgs['st'], 'et'=>$this->pgs['nxt_st']));
			$this->print_items($st, $this->pgs['limit'], $this->pgs['nxt_st'], "fd", $lid);
		}else{
			$this->view->print_feedback_head("s", $stl['title']);
			$this->view->print_s_feedback($this->model->retrieve_feedback($fdid), $stl['title']); 
		}
	}
	private function edit_lit($id, $sub){

		$st = $this->model->lit_retrieve($id);
		//echo $st['uid'].",  ".$_SESSION['uid'];
		if($st['uid'] != $_SESSION['uid']){
			errorocc("This isn't your lit!");
		}
		if(!isset($_POST) || empty($_POST) || $sub != 2){
			$st_tags = $this->model->retrieve_lit_tags($id);
			$st_tags = implode(" ", array_map(function($e){return $e['tag'];}, $st_tags));
			$st['tags'] = $st_tags;
			echo $st['tags'];
			$this->view->show_lit_form("edit", $st);
		}else{
			if(!isset($_POST['content']) || empty($_POST['content'])){
				$this->print_err_msg("Please give your lit some content!");
			}
			if(!isset($_POST['tags']) || empty($_POST['tags'])){
				$this->print_err_msg("Give your lit some tags!");
			}
			if(!isset($_POST['content']) || empty($_POST['content'])){
				$this->print_err_msg("Give your lit some content!");
			}
			
			if(!isset($_POST['nsfw']) || empty($_POST['nsfw'])){
				$_POST['nsfw'] = '0';
			}
			if(is_int(strpos($st['title'], "[Revised]"))){
				$_POST['title'] = str_ireplace("[Revised]", "[Further Revised]", $st['title']);
			}elseif(is_int(strpos($st['title'], "[Further Revised]"))){
				$_POST['title'] = $st['title'];
			}else{
				$_POST['title'] = "[Revised]".$st['title'];
			}
			$_POST['lit_id'] = $st['lit_id'];
			$_POST['tags'] = "('{$_POST['lit_id']}', '{$_SESSION['uid']}', '".str_ireplace(" ", "'), ('{$_POST['lit_id']}', '{$_SESSION['uid']}', '", $_POST['tags'])."')";
			
			if($this->model->update_lit($_POST) == TRUE){
				$this->view->print_sc_msg();
			}
			
		}
		
	}
	private function vote_page($id, $aos){
		if($this->model->check_voted($id, $aos) == TRUE){
			$aor = "REMOVE";
			$sc = "You have removed your vote from this content";
		}else{
			$aor = "ADD";
			if($aos == "0"){
				$sc = "You have downvoted this content";
			}else{
				$sc = "You have upvoted this content";
			}
			
		}
		$st = $this->model->vote($id, $aos, $aor);
		if($st == TRUE){
			$this->view->print_sc_msg($sc);
		}
	}
	private function lit_feedback($id, $sub){
		if($this->model->rpt_check() >= 2){
			errorocc("You cannot post any lits or feedbacks");
		}
		if($this->model->lit_feedback($id, $_SESSION['uid']) == TRUE){
			errorocc("You have already given this lit feedback");
		}
		$st = $this->model->lit_retrieve($id, '*');
		if($st['llock'] == '1'){
			errorocc("Sorry! This lit is locked");
		}
		if($st['uid'] == $_SESSION['uid']){
			errorocc("You cannot give your own lit feedback");
		}
		if(!isset($_POST) || empty($_POST) || $sub != 2){
			$this->view->show_feedback_form($st);
		}else{
			if(!isset($_POST['feedback']) || empty($_POST['feedback'])){
				$this->print_err_msg("Please give some feedback");
			}
			if(!isset($_POST['rate']) || empty($_POST['rate'])){
				$this->print_err_msg("Please give a score to this lit");
			}
			// rating 
			$_POST['rate'] = (int)$_POST['rate'];
			if(!is_int($_POST['rate'])){
				$this->print_err_msg("Please give a score to this lit");
			}
			if($_POST['rate'] < 1 || $_POST['rate'] > 10){
				$this->print_err_msg("Please give a valid score");
			}
			// generate feedback id 
			$_POST['fdbk_id'] = $_SESSION['uid']."_".time()."_ltfdbk";
			if($this->model->lit_feedback_insert($id, $_SESSION['uid'], $_POST) == TRUE){
				$this->view->print_sc_msg("Your feedback has been posted!");
			}
		}
	}
	private function lit_view($id){
		$sth = $this->model->lit_retrieve($id);
		$this->view->show_ind_lit($sth);
		$more = $this->model->lit_further_read($id);
		if($more == FALSE){
			$this->view->append_lit_further_nofound();
		}else{
			$this->print_items($more, 3, 0, "lt");
		}
	}
	protected function validate_lit_id($id){
		$string = TRUE;
		if(strlen($id) >40){
			$string = FALSE;
		}
		if($this->model->check_lit_id($id) != 1){
			$string = FALSE;
		}
		return $string;
	}
	protected function validate_fdbk_id($id){
		$string = TRUE;
		if(strlen($string) >44){
			$string = FALSE;
		}
		if($this->model->check_fdbk_id($id) != 1){
			$string = FALSE;
		}
		return $string;
	}
	private function lir_rm($id){
		if($this->model->lit_rm($id)){
			$this->view->print_sc_msg();
		}
	}
	private function lit_feedback_check($uid){
		$st = $this->model->retrieve_latest($uid);
		if($st == FALSE){
			return 0;
		}else{
			$num = $this->model->check_least_feedback($_SESSION['uid'], $st);
			$num = 3-$num;
			return $num;
		}
	}
	private function lit_select_rand($redir = FALSE){
		$temp = $this->model->lit_select_rand();
		if($redir == TRUE){
			header('Location: '.SERVER_ROOT_ONLY.'literature/feedback/'.$temp['lit_id']);
			
		}else{
			return $temp['lit_id'];
		}
	}
	private function lit_add($sub){
		if($this->model->rpt_check() >= 1){
			errorocc("You cannot post any lits");
		}
		$fdbk = $this->lit_feedback_check($_SESSION['uid']);
		if($fdbk > 0){
			errorocc("You need to give feedback to at least ".$fdbk." more lit(s)");
		}
		if(!isset($_POST) || empty($_POST) || $sub != 2){
			$this->view->show_lit_form();
		}else{
			if(!isset($_POST['title']) || empty($_POST['title'])){
				$this->print_err_msg("Give your lit a title!");
			}
			if(!isset($_POST['tags']) || empty($_POST['tags'])){
				$this->print_err_msg("Give your lit some tags!");
			}
			if(!isset($_POST['content']) || empty($_POST['content'])){
				$this->print_err_msg("Give your lit some content!");
			}
			if($this->model->check_title_exists($_POST['title']) == TRUE){
				$this->print_err_msg("Please choose another title");
			}
			if(!isset($_POST['nsfw']) || empty($_POST['nsfw'])){
				$_POST['nsfw'] = '0';
			}
			//generating the lit_id
			$lit_id = $_SESSION['uid']."_".time()."_lit";
			// processing the tags 
			$_POST['uid'] = $_SESSION['uid'];
			$_POST['lit_id'] = $lit_id;
			$tags = "('{$lit_id}', '{$_SESSION['uid']}', '".str_ireplace(" ", "'), ('{$lit_id}', '{$_SESSION['uid']}', '", $_POST['tags'])."')";
			if($this->model->lit_insert($_POST, $tags) == TRUE){
				$this->view->print_sc_msg("Your Lit has been posted!");
			}
			
		}
	}
	private function lit_add_rand_temp($count){
			$_POST['title'] = "Do you remember a time";
			$_POST['content'] = "Some content";
			$_POST['tags'] = 'feels';
			$inc = 0;
			$_POST['uid'] = "some";
			$_POST['nsfw'] = 0;
			while($inc<$count){
			// processing the tags 
				$inc = $inc + 1;
				$_POST['lit_id'] = $_POST['uid']."_".time()."_lit";
				$lit_id = $_POST['lit_id'];
				$tags = "('{$_POST['lit_id']}', '{$_SESSION['uid']}', '".str_ireplace(" ", "'), ('{$lit_id}', '{$_SESSION['uid']}', '", $_POST['tags'])."')";
				if($this->model->lit_insert($_POST, $tags) == TRUE){
					echo "All done<br />";
				}
			}
	}
}