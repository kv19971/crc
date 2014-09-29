<?php

class model{
	public $con;
	private $host = '127.0.0.1';
	private $uname = 'root';
	private $pass = '';
	private $dbname = 'crc';
	private $mem_host = '127.0.0.1';
	private $mem_port = '11211';
	public $cache_store;
	public $memcache;
	public function __construct(){
		try{
			$this->con = new PDO("mysql:host={$this->host};dbname={$this->dbname}", $this->uname, $this->pass);
			//$this->con->setattribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		$this->memcache = new Memcache;
		$this->cache_store = $this->memcache->connect($this->mem_host, $this->mem_port);
		
	}
	public function mysqlp_query($query){
		// add motherfukin caches for select queries ONLY 
		
		if(strpos($query, "WHERE") && strpos($query, "SELECT")){
			$cache_key = md5(stristr($query, "WHERE"));
		}
		if($this->cache_store == true && isset($cache_key)){
			$sth = $this->memcache->get($cache_key);
		}
		if(!isset($sth) || @!$sth){
			try{
				$sth = $this->con->query($query);
				if(isset($cache_key)){
					$this->memcache->set($cache_key, $sth->fetch());
				}
			}catch(PDOException $e){
				print_r($e);
				exit();	
				//$this->mysql_error_handle();
			}
		}
		return $sth;
	}
	public function transaction($value){
		if($value == "start"){
			$this->con->beginTransaction();
		}elseif($value == "end"){
			$this->con->commit();
		}elseif($value == "revert"){
			$this->con->rollback();
		}
	}
	public function mysql_error_handle($str = "An error occured with the database"){
		echo $str;
		exit();
	}
	public function check_uid_db($uid){
		return $this->mysqlp_query("SELECT 1 FROM user_main WHERE uid='{$uid}' ")->rowcount();
		
	}
	public function rpt_check(){
		$sth = $this->mysqlp_query("SELECT ban FROM user_main WHERE uid='$_SESSION[uid]'")->fetch();
		return $sth['ban'];
	}
}