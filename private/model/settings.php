<?php

class settings_model extends model{
	
	public function final_apply($data){
		try{
			$this->con->beginTransaction();
			$sth = $this->con->query("SELECT 1 FROM user_main WHERE mail = '$data[mail]' AND uid != '$_SESSION[uid]'");
			
			
			
			if($sth->rowcount() != 0){
				throw new PDOException("Email already taken");
				
			}else{
				$sth = $this->con->exec("UPDATE user_main SET mail = '$data[mail]', name = '$data[name]', pass = '$data[pwd1]' WHERE uid = '$_SESSION[uid]' LIMIT 1");
			}
			$this->con->commit();
			
			header('Location: '.SERVER_ROOT_ONLY.'success');
			
		}catch(PDOException $e){
			$this->con->rollback();
			if($e->getmessage() != "Email already taken"){
				errorocc("Something went wrong with the database");
			}else{
				errorocc("Email already taken");
			}
		}
	}
	public function get_current_vals($uid){
		try{
			$sth = $this->con->query("SELECT name, mail, pass FROM user_main WHERE uid='{$uid}' LIMIT 1");
			
		}catch(PDOException $e){
			errorocc("Something went wrong with the database");
		}
		return $sth->fetch();
	}
}