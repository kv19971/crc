<?php
include(MODEL.'literature.php');
class comment_model extends literature_model{
		public function retrieve_single_comment($id){
		try{
			$sth = $this->con->query("SELECT * FROM comment_main WHERE comment_id = '$id' LIMIT 1");
		}catch(PDOException $e){
			$this->mysql_error_handle();
		}
		if($sth->rowcount() == 1){
			return $sth->fetch();
		}else{
			return FALSE;
		}
	}
	public function retrieve_comments($data, $pgs){
		$st = $pgs['st'];
		$et = $pgs['et'];
		//echo $st." ".$et;
		//print_r($pgs);
		if(isset($data['lit_id'])){
			try{
				$sth = $this->con->query("SELECT * FROM comment_main WHERE lit_id = '$data[lit_id]' AND reply_id='new' LIMIT $st, $et ");
			}catch(PDOException $e){
				$this->mysql_error_handle();
			}
		}elseif(isset($data['comment_id'])){
			try{
				$sth = $this->con->query("SELECT * FROM comment_main WHERE reply_id='$data[comment_id]' 
				LIMIT $st, $et ");
			}catch(PDOException $e){
				//$this->mysql_error_handle();
				echo $e->getmessage();
			exit();
			}
		}
		if($sth->rowcount() < 1){
			return FALSE;
		}else{
			return $sth;
		}
	}
	public function comment_op($type, $data){
		if($type == "insert"){
			try{
				$sth = $this->con->query("INSERT INTO comment_main (lit_id, uid, comment, reply_id, comment_id) VALUES ('$data[lit_id]', '$_SESSION[uid]', '$data[comment]', '$data[reply_id]', '$data[comment_id]')");
			}catch(PDOException $e){
				$this->mysql_error_handle();
			}
			
		}elseif($type == "edit"){
			try{
				$sth = $this->con->query("UPDATE comment_main SET comment='$data[comment]' WHERE comment_id = '$data[comment_id]' LIMIT 1");
			}catch(PDOException $e){
				$this->mysql_error_handle();
			}
			
		}elseif($type == "delete"){
			try{
				$sth = $this->con->query("UPDATE comment_main SET comment = '<delcom>Comment Deleted</delcom>' AND uid='[DELETED]' WHERE comment_id = '$data[comment_id]' AND uid='$_SESSION[uid]' LIMIT 1");
			}catch(PDOException $e){
				$this->mysql_error_handle();
			}
			
		}
		return $sth;
		
	}
	public function comment_vote($vote, $id){
		if($vote == 2 || $vote == 1){
			try{
				$sth = $this->con->query("INSERT INTO comment_vote(uid, comment_id, vote) VALUES ('$_SESSION[uid]', '$id', '$vote')");
			}catch(PDOException $e){
				$this->mysql_error_handle();
			}
			return $sth;
		}elseif($vote == 'chk'){
			try{
				$sth = $this->con->query("SELECT vote FROM comment_vote WHERE comment_id = '$id' AND uid='$_SESSION[uid]' LIMIT 1");
			}catch(PDOException $e){
				$this->mysql_error_handle();
			}
			if($sth->rowcount() == 1){
				$sth = $sth->fetch();
				$sth = $sth['vote'];
			}else{
				$sth = false;
			}
			return $sth;
		}elseif($vote == "rm"){
			try{
				$sth = $this->con->query("DELETE FROM comment_vote WHERE comment_id = '$id' AND uid='$_SESSION[uid]' LIMIT 1");
			}catch(PDOException $e){
				$this->mysql_error_handle();
			}
			return $sth->rowcount();
		}else{
			return FALSE;
		}
	}
}