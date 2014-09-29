<?php

class home_model extends model{
	public function login_check($uid, $passwd){
		try{
		
			$lgn = $this->mysqlp_query("SELECT 1 FROM user_main WHERE uid='$uid' AND pass = '$passwd'");
			
			if($lgn->rowCount() != 1){
				return FALSE;
			}else{
				return TRUE;
			}
		}catch(PDOException $e){
			errorocc();
		}
	}
	public function recommended_lits($pgs){
		// needs optimization 
		// abstract to lit_model class 
		$sth = $this->mysqlp_query("SELECT * FROM lit_main WHERE uid != '$_SESSION[uid]' AND lit_id NOT IN (SELECT lit_id FROM lit_feedback WHERE uid='$_SESSION[uid]') AND lit_id IN (SELECT lit_id FROM lit_tags WHERE tag IN (SELECT tag FROM lit_tags WHERE uid = '$_SESSION[uid]')) ORDER BY `time` DESC LIMIT {$pgs['st']}, {$pgs['et']}");
		if($sth->rowcount() != 0){
			return $sth;
		}else{
			$sth = $this->mysqlp_query("SELECT * FROM lit_main WHERE uid != '$_SESSION[uid]' AND lit_id NOT IN (SELECT lit_id FROM lit_feedback WHERE uid='$_SESSION[uid]') ORDER BY `time` DESC LIMIT {$pgs['st']}, {$pgs['et']}");
			return $sth;
		}
	}
	public function check_feedbacks($uid){
		$st = $this->mysqlp_query("SELECT 1 FROM lit_main WHERE uid='$uid' AND llock='1'");
		return $st->rowcount();
	}
}