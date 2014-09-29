<?php
// paged_form_tokens
class paged_form_tokens{
	public $db;
	public $form_links_valid = array("/paged/settings.php", "/paged/register.php", "/paged/login.php", "/paged/index.php", '/paged/search.php', '/paged/profile.php', '/paged/pageview.php');
	public function __construct(){
		$this->db = new paged_database();
	}
	public function check_prev_link(){
		if(!isset($_SERVER['HTTP_REFERER'])){
			return FALSE;
		}
		$prevurl = parse_url($_SERVER['HTTP_REFERER']);
		if(in_array($prevurl['path'], $this->form_links_valid)){
			return TRUE;
		}else{
			return FALSE;
		}
	}
	public function generate_token($frmname){
		$token = uniqid("paged_{$frmname}_", TRUE);
		$this->db->run_mysql_query("INSERT INTO paged_form_token(tk_id) VALUES ('$token')");
		return $token;
	
	}
	public function get_token($tk_id){
		if(!isset($tk_id) || $tk_id == "" || $tk_id == FALSE || $tk_id == NULL || empty($tk_id)){
			return FALSE;
		}else{
			$tk_id = mysqli_real_escape_string($this->db->con, $tk_id);
			$qr = $this->db->run_mysql_query("SELECT tk_id FROM paged_form_token WHERE tk_id = '$tk_id' AND active=1");
			if($qr && mysqli_num_rows($qr) == 1){
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	public function invalidate_token($tk_id){
		$a = $this->db->run_mysql_query("UPDATE paged_form_token SET active='0' WHERE tk_id = '$tk_id'");
		if(!$a){
			return FALSE;
		}else{
			return TRUE;
		}
	}

}


?>