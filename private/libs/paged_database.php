<?php
//class used to connect to the database 
class paged_database{
	private $server = "127.0.0.1";
	private $db_name = "paged";
	private $pass;
	public $con;
	public $user = "root";
	// this function needs to be run first before anything else. 
	public function __construct($user = "root"){
		$this->pass = "";
		$user = $this->user;
		$this->con = mysqli_connect($this->server, $user, $this->pass) or $this->error_mysql();//add error handlers 
		mysqli_select_db($this->con, $this->db_name) or $this->error_mysql();;
		$this->user = $user;
	}
	public function run_mysql_query($query){
		if($db_qry = mysqli_query($this->con, $query)){
			return $db_qry;
		}else{
			$this->error_mysql();
		}
	}
	public function get_row_data($query){
		return mysqli_fetch_array($query);
	}
	public function move_pointer($query, $row_num){
		return mysqli_data_seek($query, $row_num) or $this->error_mysql();
	}
	public function change_user($changeto){
		if($changeto == "paged_public"){
			$pass = "somepass"; 
		}elseif($changeto == "paged_private"){
			$pass = "someotherpass"; 
		}
		mysqli_change_user($changeto, $pass, db_name, $this->con) or $this->error_mysql();// add error handlers 
		$this->user = $changeto;
	}
	
	public function close_con(){
		mysqli_close($this->con);
	}
	private function error_mysql(){
		error_log("<br /><b>".date('d M Y H:i:s')."</b><br/><p>".mysqli_error($this->con)."</p><br />", 3, 'error.log.html');
		die(header("Location: somethingwentwrong.php"));
		
	}
	public function check_rows($qry){
		if($qry){
			return mysqli_num_rows($qry);
		}else{
			return 0;
		}
	}
	public function query_limit($st = 0){
		if(filter_var($st, FILTER_VALIDATE_INT) == FALSE){
			$st = 0;
		}
		$et = $st + 19;
		return $st.", ".$et;
	}
	public function limit_next_st($prev_et){
	return $prev_et+1;
	}
	
	
}
?>