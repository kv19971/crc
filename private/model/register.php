<?php

class register_model extends model{
	
	public function final_register(){
		try{
			$this->con->beginTransaction();
			$sth = $this->con->query("SELECT 1 FROM user_main WHERE uid='$_POST[uid]' OR mail = '$_POST[mail]'");
			
			
			
			if($sth->rowcount() != 0){
				throw new PDOException("Email or UID already taken");
				
			}else{
				$sth = $this->con->query("INSERT INTO user_main (uid, mail, pass) VALUES ('$_POST[uid]', '$_POST[mail]', '$_POST[pwd1]')");
			}
			$this->con->commit();
			return TRUE;
			
		}catch(PDOException $e){
			$this->con->rollback();
			if($e->getMessage != "Email or UID already taken"){
				$this->mysql_error_handle();
			}else{
				errorocc("Email or UID already taken");
			}
		}
	}
}