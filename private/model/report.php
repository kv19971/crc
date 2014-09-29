<?php
include('literature.php');
class report_model extends literature_model{
	public function chk_report($type, $id){
		if($this->mysqlp_query("SELECT 1 FROM report_main WHERE type='$type' AND id='$id' AND uid='$_SESSION[uid]'")->rowcount() != 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function retrieve_all_reports($fa, $pgs){
		if($fa == "by"){
			$a = $this->mysqlp_query("SELECT * FROM report_main WHERE uid='$_SESSION[uid]' LIMIT {$pgs['st']}, {$pgs['et']}");
		}else{
			$a = $this->mysqlp_query("SELECT * FROM report_main WHERE id LIKE '{$_SESSION[uid]}_%' LIMIT {$pgs['st']}, {$pgs['et']}");
		}
		if($a->rowcount() == 0){
			return FALSE;
		}else{
			return $a;
		}
	}
	public function report($param){
		if($param['do'] == "add"){
	
			if($param['bn'] == FALSE){
				$this->rpt_normal_query($param);
			}else{
				$this->rpt_transactional_query($param);
			}
			return TRUE;
		}elseif($param['do'] == "rm"){
			$this->mysqlp_query("DELETE FROM report_main WHERE id='$param[id]' AND type='$param[type]' AND uid='$_SESSION[uid]'");
			return TRUE;
		}
	}
	public function rpt_normal_query($param){
				$this->mysqlp_query("INSERT INTO report_main (content, type, `id`, uid) VALUES ('$param[content]', '$param[type]', '$param[id]', '$_SESSION[uid]')");
				
			}
	public function rpt_transaction_query($param){
				try{
					$this->transaction("begin");
					$this->normal_query($param);
					$this->mysqlp_query("UPDATE user_main SET ban='$param[bn]' WHERE uid='$param[uid]'");
					$this->transaction("end");
				
				}catch(PDOException $e){
					$this->transaction("revert");
					$this->mysql_error_handle();
				}
			}
	public function count_reports($type, $id){
		return $this->mysqlp_query("SELECT row FROM report_main WHERE id='$id' AND type='$type'")->rowcount();
	}
}