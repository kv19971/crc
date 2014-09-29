<?php

class controller{
	public $view;
	public $model;
	public $pgs;
	public function print_err_msg($msg){
		$this->view->print_err_msg($msg);
	}
	public function print_sc_msg($msg){
		$this->view->print_sc_msg($msg);
	}
	public function page_generate($args){
		$ar = array_keys($args, 'page');
		if($ar == FALSE){
			$pg = 0;
		}else{
			$lst = end($ar);
			$pg = $args[$lst+1];	
		}
		
		$st = $pg;
		$et = $pg+14;
		$nst = $et+1;
		$net = $nst+14;
		//echo "lol $st , $et , $nst , $net";
		return array('st'=>$st, 'et'=>$et, 'nxt_st'=>$nst, 'nxt_et'=>$net, 'limit'=>14);
	}
	
	public function check_errs($args){
		$this->view = new view;
		if(isset($args[0])){
			$this->view->render_err(trim(htmlspecialchars(urldecode($args[0]))));
		}else{
			$this->view->render_err();
			
			
				
		}
	}
	public function inst_sess(){
		ini_set('session.cookie_httponly', 1);
		ini_set('session.entropy_file', '/dev/urandom');
		ini_set('session.hash_function', 'whirlpool');
		if(!isset($_SESSION)){
			session_start();
		}
	}
	public function check_login(){
		if(!isset($_SESSION)){
			session_start();
		}
		if(!isset($_SESSION['uid']) || !$_SESSION['uid']){
			return FALSE;
		}else{
			$_SESSION['uid'] = validate_user_id($_SESSION['uid']);
			return $this->check_uid_exists($_SESSION['uid']);
		}
	}
	public function check_login_final(){
		if($this->check_login() == FALSE){
			errorocc("Please log in to continue");
		}
		
	}
	public function check_uid_exists($uid){
		$sth = $this->model->check_uid_db($uid);
		if($sth != 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function print_items($st, $limit, $nxt_st, $type, $lid = ""){
		// st = resultset
		// limit = number of rows to be printed 
		// nxt_st = next starting point s
		$inc = 0;		
		foreach($st as $row){
			$inc = $inc + 1;
			$this->view->append_result($type, $row, $lid);
			if($inc >= $this->pgs['limit']){
				break;
			}
		}
		if($st->rowcount()> $this->pgs['limit']){
			$this->view->page_nxt_generate($this->pgs['nxt_st']);
		}else{
			$this->view->page_no_results();
		}
	}
}