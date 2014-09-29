<?php
class literature_model extends model{
	public function retrieve_all_feedbacks($lid, $pgs){
		$st = $this->mysqlp_query("SELECT * FROM lit_feedback WHERE lit_id = '$lid' ORDER BY `time` DESC LIMIT {$pgs['st']}, {$pgs['et']}");
		return $st;
	}
	public function retrieve_feedback($id){
		return $this->mysqlp_query("SELECT * FROM lit_feedback WHERE fdbk_id = '$id' LIMIT 1")->fetch();
	}
	public function check_lit_id($id){
		try{
			$sth = $this->con->query("SELECT 1 FROM lit_main WHERE lit_id='$id'");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		return $sth->rowcount();
	}
		public function check_fdbk_id($id){
		try{
			$sth = $this->con->query("SELECT 1 FROM lit_feedback WHERE fdbk_id='$id'");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		return $sth->rowcount();
	}
	public function insert_fd_request($data, $fid, $rp){
		if($rp == "RP"){
		return $this->mysqlp_query("UPDATE lit_feedback SET response = '$data[comment]', dialogue='0' WHERE fdbk_id='$fid'");
		}else{
		return $this->mysqlp_query("UPDATE lit_feedback SET comment = '$data[comment]', dialogue='1' WHERE fdbk_id='$fid'");
		}
	}
	public function retrieve_all_exfd($uid, $pgs, $bf){
		if($bf == "for"){
			return $this->mysqlp_query("SELECT * FROM lit_feedback WHERE uid='$uid' AND dialogue='1' ORDER BY `time` LIMIT {$pgs['st']}, {$pgs['et']}");
		}else{
			return $this->mysqlp_query("SELECT * FROM lit_feedback WHERE lit_id  LIKE '{$uid}_%' ORDER BY `time` LIMIT {$pgs['st']}, {$pgs['et']}");

		}
	}
	public function lit_rm($id){
		try{
			$this->transaction("start");
			$this->mysqlp_query("DELETE FROM lit_main WHERE lit_id='$id' LIMIT 1");
			$this->mysqlp_query("DELETE FROM lit_tags WHERE lit_id = '$id'");
			$this->transaction("end");
			return TRUE;
		}catch(PDOException $e){
			$this->transaction("revert");
			echo $e->getmessage();
			return FALSE;
			
		}
	}
	public function lit_retrieve($id, $column = '*'){
		return $this->mysqlp_query("SELECT {$column} FROM lit_main WHERE lit_id='$id' LIMIT 1")->fetch();
	}
	public function retrieve_latest($uid){
			$st = $this->mysqlp_query("SELECT time FROM lit_main WHERE uid='$uid' ORDER BY `time` DESC LIMIT 1");
			if($st->rowcount() != 0){
				$st = $st->fetch();
				return $st['time'];
			}else{
				return FALSE;
			}
		
	}
	public function check_least_feedback($uid, $time){
		return $this->mysqlp_query("SELECT `row` FROM lit_feedback WHERE uid='$uid' AND `time` > '$time'")->rowcount();
	}
	public function lit_feedback($lit_id, $uid){
		if($this->mysqlp_query("SELECT 1 FROM lit_feedback WHERE lit_id='$lit_id' AND uid='$uid' LIMIT 1")->rowcount() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function retrieve_lit_tags($lid){
		return $this->mysqlp_query("SELECT tag FROM lit_tags WHERE lit_id='$lid'")->fetchall();
	}
	public function lit_feedback_no($id){
		return $this->mysqlp_query("SELECT row FROM lit_feedback WHERE lit_id = '$id' ")->rowcount();
	}
	public function update_lit($data){
		try{
			$this->transaction("start");
			$this->mysqlp_query("UPDATE lit_main SET title = '$data[title]', content='$data[content]', nsfw='$data[nsfw]' WHERE lit_id='$data[lit_id]' AND uid='$_SESSION[uid]'");
			$this->mysqlp_query("DELETE FROM lit_tags WHERE lit_id='$data[lit_id]'");
			$this->mysqlp_query("INSERT INTO lit_tags (lit_id, uid, tag) VALUES ".$_POST['tags']);
			$this->transaction("end");
			return TRUE;
		}catch(PDOException $e){
			$this->transaction("revert");
			echo $e->getmessage();
			return FALSE;
			
		}
	}
	public function lit_insert($data, $tags){
		try{
			$this->transaction("start");
			$this->mysqlp_query("INSERT INTO lit_main (lit_id, uid, content, title, nsfw) VALUES ('$_POST[lit_id]', '$_POST[uid]', '$_POST[content]', '$_POST[title]', '$_POST[nsfw]')");
			$this->mysqlp_query("INSERT INTO lit_tags (lit_id, uid, tag) VALUES ".$tags);
			$this->transaction("end");
			return TRUE;
		}catch(PDOException $e){
			$this->transaction("revert");
			echo $e->getmessage();
			return FALSE;
			
		}
	}
	public function check_title_exists($title){
		if($this->mysqlp_query("SELECT 1 FROM lit_main WHERE title = '$title'")->rowcount() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function lit_further_read($id){
		$sth = $this->mysqlp_query("SELECT * FROM lit_main WHERE lit_id IN (SELECT lit_id FROM lit_tags WHERE tag IN (SELECT tag FROM lit_tags WHERE lit_id = '$id')) ORDER BY rand() LIMIT 3");
		
		if($sth->rowcount() < 1){
			return FALSE;
		}else{
			return $sth;
		}
	
	}
	public function lit_feedback_insert($id, $uid, $data){
		try{
			$this->transaction("start");
			$frc = $this->mysqlp_query("SELECT rating FROM lit_feedback WHERE lit_id = '$id' ");
			$rate = 0;
			$i = 0;
			foreach($frc as $r){
				$i = $i + 1;
				$rate = $rate + $r['rating'];
			}
			if($i > 2){
				throw new PDOException("LL");
			}else{
				$this->mysqlp_query("INSERT INTO lit_feedback (fdbk_id, uid, lit_id, content, rating) VALUES ('$_POST[fdbk_id]', '$uid', '$id', '$data[feedback]', '$data[rate]')");
				$rate = ceil(($rate+$data['rate'])/3);
				if($frc == 2){
					$this->mysqlp_query("UPDATE 
					lit_main 
					SET 
					llock = '1', frate = '$rate' 
					WHERE 
					lit_id='$id'");
				}
			}
			$this->transaction("end");
			return TRUE;
		}catch(PDOException $e){
			$this->transaction("revert");
			if($e->getmessage() == "LL"){
				$this->mysqlp_query("UPDATE lit_main SET llock = 1 WHERE lit_id='$id' LIMIT 1");
				errorocc("Sorry! This lit is llocked");
			}else{
				$this->mysql_error_handle();
			}
		}
	}
	public function lit_select_rand(){
		$sth = $this->mysqlp_query("SELECT * FROM lit_main WHERE uid != '$_SESSION[uid]' AND lit_id NOT IN (SELECT lit_id FROM lit_feedback WHERE uid='$_SESSION[uid]') AND lit_id IN (SELECT lit_id FROM lit_tags WHERE tag IN (SELECT tag FROM lit_tags WHERE uid = '$_SESSION[uid]')) LIMIT 1");
		if($sth->rowcount() != 0){
			return $sth->fetch();
		}else{
			$sth = $this->mysqlp_query("SELECT * FROM lit_main WHERE uid != '$_SESSION[uid]' AND lit_id NOT IN (SELECT lit_id FROM lit_feedback WHERE uid='$_SESSION[uid]') LIMIT 1");
			if($sth->rowcount() != 0){
			return $sth->fetch();
			}else{
				return FALSE;
			}
		}
	}
		public function check_voted($id, $aos){
		try{
			$sth = $this->con->query("SELECT 1 FROM lit_vote WHERE lit_id='$id' AND uid='$_SESSION[uid]' AND aos='$aos' LIMIT 1");
		}
		catch(PDOException $e){
			$this->mysql_error_handle();
		}
		if($sth->rowcount() == 1){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function vote($id, $aos, $aor){
		
		try{
			$st = $this->con->query("DELETE FROM lit_vote WHERE lit_id='$id' AND uid='$_SESSION[uid]' LIMIT 1");
			
			if($aor == "ADD"){
				$st = $this->con->query("INSERT INTO lit_vote (uid, lit_id, aos) VALUES ('$_SESSION[uid]', '$id', '$aos')");
			}
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		
		if($st){
			return TRUE;
		}
	}
	
}