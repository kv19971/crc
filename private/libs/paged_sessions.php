<?php
class paged_sessions{
	public $uname;
	public $sql;
	public $pass;
	
	public $linktologin = "login.php";
	public $linktofailure = "login.php";
	public $linktosuccess = "index.php";
	public function __construct(){
		ini_set('session.cookie_httponly', 1);
		ini_set('session.entropy_file', '/dev/urandom');
		ini_set('session.hash_function', 'whirlpool');
		if(!isset($_SESSION)){
			session_start();
		}
		if(isset($_SESSION['paged_uname'])){
			$this->uname = $_SESSION['paged_uname'];
		}
		$this->sql = new paged_database;
	}
	public function log_in(){
		if(isset($_SESSION['paged_uname'])){
			if($this->check_log_in() == TRUE){
				header("Location: $this->linktosuccess");
				exit();
			}
		}
		// validate 
		$this->pass = SHA1($this->pass);
		// determine if username is email or user_id
		if(filter_var($this->uname, FILTER_VALIDATE_EMAIL) == false){
			$this->check_user_id();
			$column = 'user_id';
		}else{
			$this->uname = mysql_real_escape_string($this->uname);
			$this->uname = htmlspecialchars($this->uname);
			$this->uname = filter_var($this->uname, FILTER_VALIDATE_EMAIL);
			$column = 'email';
		}
		// execute query
		$this->sql = new paged_database("paged_public");
		$sqla = $this->sql->run_mysql_query("SELECT user_id FROM paged_user_main WHERE ".$column."='$this->uname' AND password = '$this->pass'");
		if(mysqli_num_rows($sqla) == 1){
			// set session variable to user_id 
			$sqla = $this->sql->get_row_data($sqla);
			session_regenerate_id(true);
			$_SESSION['paged_uname'] = $sqla['user_id'];
			$this->sql->run_mysql_query("UPDATE paged_user_main SET loggedin = '1' WHERE user_id='$_SESSION[paged_uname]' LIMIT 1");
			header("Location: $this->linktosuccess");
			exit();
		}else{
			$this->not_logged_in();
		}
		}
	public function check_log_in(){
		$login = "check";
		if(!isset($_SESSION['paged_uname'])){
			$login = FALSE;
			
			
		}else{
			$this->check_user_id();
			$r = $this->sql->run_mysql_query("SELECT loggedin FROM paged_user_main WHERE user_id='$_SESSION[paged_uname]' AND loggedin = '1'");
			if(mysqli_num_rows($r) != 1){
				$login = FALSE;
				
			}
			}
		if($login == "check"){
			$this->uname = $_SESSION['paged_uname'];
			$login = true;
			return $login;
		}else{
			return $login;
		}
		
	}
	public function log_out(){
		$uname = $_SESSION['paged_uname'];
		unset($_SESSION['paged_uname']);
		session_destroy();
		$this->sql->run_mysql_query("UPDATE paged_user_main SET loggedin='0' WHERE user_id='$uname' LIMIT 1");
		header("Location: $this->linktologin");
		exit();
	}
	private function not_logged_in(){
		header('Location: '.$this->linktofailure);
		exit();
	}
	public function check_user_id(){
		$a = new paged_validate;
		$b = $a->validate_user_id($this->uname);
		if($b == FALSE){
			$this->not_logged_in();
		}else{
			$c = $a->check_user_id_exists($this->uname);
			if($a->istrue = FALSE){
				$this->not_logged_in();
			}
		}
	}
	}

		



?>