<?php
class follow_model extends model{
	public function check_follow($uid){
		try{
			$sth = $this->con->query("SELECT row FROM user_follow WHERE follower = '$_SESSION[uid]' AND following = '$uid' LIMIT 1");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		if($sth->rowcount() != 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function add_follow($uid, $undo){
		if($undo == FALSE){
			try{
				$sth = $this->con->query("INSERT INTO user_follow (follower, following) VALUES ('$_SESSION[uid]', '$uid')");
			}catch(PDOException $e){
				$this->mysql_error_handle();
			}
			
		}elseif($undo == TRUE){
			try{
				$sth = $this->con->query("DELETE FROM user_follow WHERE follower='$_SESSION[uid]' AND following = '$uid' LIMIT 1");
			}catch(PDOException $e){
				$this->mysql_error_handle();
			}
			
		}
		if($sth->rowcount() == 1){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function select_following($check){
		if($check == TRUE){
			try{
				$sth = $this->con->query("SELECT following as following FROM user_follow WHERE follower='$_SESSION[uid]'");
			}catch(PDOException $e){
				$this->mysql_error_handle();
			}
		}else{
			try{
				$sth = $this->con->query("SELECT follower as following FROM user_follow WHERE following='$_SESSION[uid]'");
			}catch(PDOException $e){
				$this->mysql_error_handle();
			}
		}
		if($sth->rowcount() < 1){
			return FALSE;
		}else{
			return $sth;
		}
		
	}
}