<?php
class user_model extends model{
	public function ustats($data){
		if($data == "nolits"){
			return $this->mysqlp_query("SELECT 1 FROM lit_main WHERE uid='$_SESSION[uid]'")->rowcount();
		}else
		if($data == "nocritiqued"){
			return $this->mysqlp_query("SELECT 1 FROM lit_main WHERE uid='$_SESSION[uid]' AND llock='1'")->rowcount();
		}else
		if($data == "noreportsagainst"){
			return $this->mysqlp_query("SELECT 1 FROM report_main WHERE id LIKE '{$_SESSION['uid']}_%' ")->rowcount();
		}else
		if($data == "noreportsby"){
			return $this->mysqlp_query("SELECT 1 FROM report_main WHERE uid='{$_SESSION['uid']}' ")->rowcount();
		}else
		if($data == "nofeedbacksrequested"){
			return $this->mysqlp_query("SELECT 1 FROM lit_feedback WHERE dialogue='1' AND uid='$_SESSION[uid]' ")->rowcount();
		}else
		if($data == "nofeedbacksrequestedby"){
			return $this->mysqlp_query("SELECT 1 FROM lit_feedback WHERE dialogue='1' AND lit_id LIKE '{$_SESSION['uid']}_' ")->rowcount();
		}
	}
	public function retrieve_user($uid){
		return $this->mysqlp_query("SELECT * FROM user_main WHERE uid='$uid' LIMIT 1")->fetch();
	}
	public function retrieve_lits($uid, $pgs){
		return $this->mysqlp_query("SELECT * FROM lit_main WHERE uid='$uid' LIMIT {$pgs['st']}, {$pgs['et']}");
	}
	public function retrieve_favourites($pgs, $ob = "user", $rtype = "default", $uid){
		if($rtype == "default"){
			$pgstr = "LIMIT {$pgs['st']}, {$pgs['et']}";
		}else{
			$pgstr = "";
		}
		if($ob == "user"){
			$st = $this->mysqlp_query("SELECT uid FROM user_fav WHERE fav_id='$uid' {$pgstr}");
		}elseif($ob == "byuser"){
			$st = $this->mysqlp_query("SELECT fav_id as uid FROM user_fav WHERE uid='$uid' {$pgstr}");
		}
		if($rtype == "default"){
			if($st->rowcount() == 0){
				return FALSE;
			}else{
				return $st;
			}
		}else{
			return $st->rowcount();
		}
		
	}
	public function update_user($data, $pass, $chk_mail){
		if($chk_mail == TRUE){
		try{
			$this->transaction("start");
			if($this->mysqlp_query("SELECt 1 FROM user_main WHERE uid='$_SESSION[uid]' AND mail = '$data[mail]'")->rowcount() != 0){
				throw new PDOException("E");
			}else{
				$this->mysqlp_query("UPDATE user_main SET shownsfw = '$data[shownsfw]', bio='$data[bio]', mail = '$data[mail]', pass='$pass', private='$data[private]' WHERE uid='$_SESSION[uid]'");
			}
			$this->transaction("end");
			return TRUE;
		}catch(PDOException $e){
			$this->transaction("revert");
			if($e->getmessage() == "E"){
				errorocc("Youll need to select another email!");
			}else{
				$this->mysql_error_handle();
			}
		}
		}elseif($chk_mail ==  FALSE){
			$this->mysqlp_query("UPDATE user_main SET shownsfw = '$data[shownsfw]', bio='$data[bio]', pass='$pass', private='$data[private]' WHERE uid='$_SESSION[uid]'");
			return TRUE;
		}
		
	}
	public function check_fav($uid){
			if($this->mysqlp_query("SELECT 1 FROM user_fav WHERE uid='$_SESSION[uid]' AND fav_id = '$uid' LIMIT 1")->rowcount() != 0){
				return TRUE;
			}else{
				return FALSE;
			}
		}
		public function fav($uid, $type){
			if($type == "do"){
				$this->mysqlp_query("INSERT INTO user_fav (uid, fav_id) VALUES ('$_SESSION[uid]', '$uid')");
				return TRUE;
			}elseif($type == "undo"){
				$this->mysqlp_query("DELETE FROM user_fav WHERE uid='$_SESSION[uid]' AND fav_id='$uid'");
				return TRUE;
			}
		}
}