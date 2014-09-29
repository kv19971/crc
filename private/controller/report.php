<?php
include('literature.php');
class report_controller extends literature_controller{
	public function __construct($args = array()){
		$this->model = new report_model;
		$this->view = new report_view;
		$this->pgs = $this->page_generate($args);
		$this->check_login_final();
		if(empty($args)){
			errorocc("404: Link not found");
		}
		if(!isset($args[0]) || empty($args[0])){
			errorocc("Invalid request");
		}
		if($args[0] != "user" && $args[0] != "lit" && $args[0] != "fdbk" && $args[0] != "view" ){
			errorocc("Invalid request");
		}
		if($args[0] == "view"){
			if(!isset($args[1]) || empty($args[1]) || $args[1] != "against"){
				$this->view_reports("by");
			}else{
				$this->view_reports("against");
			}
		}else{
		if(!isset($args[1]) || empty($args[1])){
			errorocc("Invalid request");
		}
		if($args[0] == "lit"){
			if($this->validate_lit_id($args[1]) == FALSE){
				errorocc("Invalid request");
			}
		}
		if($args[0] == "fdbk"){
			if($this->validate_fdbk_id($args[1]) == FALSE){
				errorocc("Invalid request");
			}
		}
		if($args[0] == "user"){
			$args[1] = validate_user_id($args[1]);
			if($this->check_uid_exists($args[1]) == FALSE){
				errorocc("Invalid request");
			}
		}
		if(in_array(2, $args)){	
			$args[3] = 2;
		}else{
			$args[3] = 1;
		}
		if(!isset($args[2]) || empty($args[2]) || $args[2] != "rm"){
			$this->report($args[0], $args[1], "add", $args[3]);
		}elseif($args[2] == "rm"){
			$this->report($args[0], $args[1], "rm", $args[3]);
		}
		}
	}
	private function view_reports($fa){
		$this->view->print_head($fa);
		$st = $this->model->retrieve_all_reports($fa, array('st'=>$this->pgs['st'], 'et'=>$this->pgs['et']));
		if($st == FALSE){
			$this->view->page_no_results();
		}else{
		$this->print_items($st, $this->pgs['limit'], $this->pgs['nxt_st'], "rp");
		}
	}
	private function report($type, $id, $do = "add", $sub){
	if($do == "add"){
		if($this->model->chk_report($type, $id) == TRUE){
			errorocc("You've already filed a report against this content/user!");
		}
		if(!isset($_POST) || empty($_POST) || $sub != 2){
			$this->view->show_report_form($type, $id);
		}else{
			if((!isset($_POST['content']) || empty($_POST['content'])) && (!isset($_POST['content_r']) || empty($_POST['content_r']))){
				$this->print_err_msg("Please give a reason!");
			}elseif(isset($_POST['content_r']) && !empty($_POST['content_r'])){
				$_POST['content'] = $_POST['content_r'];
			}
			if($type != "user"){
				if($type == "lit"){
					$st = $this->model->lit_retrieve($id, 'uid');
					$uid = $st['uid'];
				}elseif($type == "fdbk"){
					$st = $this->model->retrieve_feedback($id);
					$uid = $st['uid'];
				}
			}else{
				$uid = $id;
			}
			$s = $this->model->count_reports($type, $id);
			if($type == "user" && $s >= 14){
				$bn = 1;
			}elseif($type == "user" && $s >= 14){
				$bn = 2;
			}elseif($type == "user" && $s >= 19){
				$bn = 3;
			}else{
				$bn = FALSE;
			}
			if($this->model->report(array('bn'=>$bn, 'uid'=>$uid, 'type'=>$type, 'id'=>$id, 'do'=>$do, 'content'=>$_POST['content'])) == TRUE){
				$this->view->print_sc_msg("Report filed");
			}
		}
	}elseif($do == "rm"){
		if($this->model->chk_report($type, $id) == FALSE){
			$this->print_err_msg("You havent filed a report!");
		}
		if($this->model->report(array('type'=>$type, 'id'=>$id, 'do'=>$do)) == TRUE){
			$this->view->print_sc_msg("Report removed.");
		}
	}
	}
}