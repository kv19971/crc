<?php
class view{
	protected static $btext = "";
	protected $title = "";
	protected $logged = TRUE;
	protected $printliterature = FALSE;
	public function page_nxt_generate($nst, $limit = 15){
		$p_nst = $nst-$limit;
		
		$_SERVER['REQUEST_URI'] = str_ireplace("page/{$p_nst}", "", $_SERVER['REQUEST_URI']);
		if(substr($_SERVER['REQUEST_URI'], -1) == "/"){
			self::$btext .= "<a href='http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}page/{$nst}'>See More</a>";
		}else{
			self::$btext .= "<a href='http://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}/page/{$nst}'>See More</a>";
		}
		
	}
	public function print_sc_msg($msg = "All Done!"){
		//self::$btext = "<div class='error'>{$msg}</div><br />".self::$btext;
		$this->printliterature = TRUE;
		echo "<span class='sc_msg'>".$msg."</span>";
		
	}
	public function print_err_msg($msg = "Sorry. Please try again"){
		$this->printliterature = TRUE;
		echo "<span class='err_msg'>".$msg."</span>";
		exit();
	}
	public function page_no_results(){
		self::$btext .= "No More results";
	}
	public function render_val_field($name, $tp = 'post'){
		if($tp == 'post'){
			if(isset($_POST[$name]) && !empty($_POST[$name])){
				return "value='".htmlspecialchars($_POST[$name])."'";
			}else{
				return "";
			}
		}else{
			if(isset($_GET[$name]) && !empty($_GET[$name])){
				return "value='".htmlspecialchars($_GET[$name])."'";
			}else{
				return "";
			}
		}
	}
	public function render_err($str = "Something went wrong"){
		self::$btext = "<div class='error'>{$str}</div><br />".self::$btext;
		
	}
	
	public function __destruct(){
		if($this->printliterature == FALSE){
			$btext = self::$btext;
			
			$title = $this->title;
			
			$lgn = $this->logged;
			include('resources/template.php');
		}
	}
	public function append_result($type, $data, $lid = ""){
		if($type == "lt"){
			// required - lit_id, uid 
			self::$btext .= "<div class='content_block'>
			<div class='container'>
			<div class='row'>
			<div class='col-md-9'><div class='lt_user'><a href='".SERVER_ROOT_ONLY."user/profile/{$data['uid']}'>{$data['uid']}</a></div></div>
			<div class='col-md-3'><div class='lt_user'>";
			if($data['llock'] != 1){
				self::$btext .= "Not critiqued yet";
			}else{
				self::$btext .= "Score is ".$data['frate'];
			}
			self::$btext .= "</div></div>
			</div>
			<div class='row'><div class='col-md-12'><div class='lt_title'>";
			if($data['nsfw'] == 1){
				self::$btext .= "{NSFW}";
			}
			self::$btext .= "<a href='".SERVER_ROOT_ONLY."literature/view/{$data['lit_id']}'> {$data['title']} </a></div></div></div>

			<div class='row'>
			<div class='col-md-3'><a href='".SERVER_ROOT_ONLY."comment/".$data['lit_id']."/add'><div class='button'>Comment</div></a></div>
			<div class='col-md-3'><a onclick=\"send_data('".SERVER_ROOT_ONLY."literature/upvote/".$data['lit_id']."') \" ><div class='button'>Upvote</div></a></div>
			<div class='col-md-3'><a onclick=\"send_data('".SERVER_ROOT_ONLY."literature/downvote/".$data['lit_id']."') \" ><div class='button'>Downvote</div></a></div>
			";
			if($data['uid'] == $_SESSION['uid']){
				self::$btext .="<div class='col-md-3'><a href='".SERVER_ROOT_ONLY."literature/edit/".$data['lit_id']."'><div class='button'>Edit</div></a></div>";
			}else{
				if($data['llock'] != 1){
					self::$btext .="<div class='col-md-3'><a href='".SERVER_ROOT_ONLY."literature/feedback/".$data['lit_id']."'><div class='button'>Give Feedback</div></a></div>";
				}else{
					self::$btext .="<div class='col-md-3'><div class='button' id='disabled'>This Lit has been critiqued!</div></div>";					
				}
			}
			self::$btext .="</div></div></div>";
		}elseif($type == "cm"){
			// required - comment, uid, comment_id
		
			self::$btext .= "<li><div class='content_block'>
			<div class='container'>
			<div class='row'><div class='col-md-12'><div class='lt_user'><a href='".SERVER_ROOT_ONLY."user/profile/{$data['uid']}'>{$data['uid']}</a></div></div></div>
			<div class='row'><div class='col-md-12'><div class='lt_comment'>{$data['comment']}</div></div></div>

			<div class='row'>
			<div class='col-md-1'></div>
			<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."comment/".$data['lit_id']."/add/".$data['comment_id']."'><div class='button'>Reply</div></a></div>
			<div class='col-md-2'><a onclick=\"send_data('".SERVER_ROOT_ONLY."comment/".$data['lit_id']."/vote/2/".$data['comment_id']."')\"><div class='button'>Upvote</div></a></div>
			<div class='col-md-2'><a onclick=\"send_data('".SERVER_ROOT_ONLY."comment/".$data['lit_id']."/vote/1/".$data['comment_id']."')\"><div class='button'>Downvote</div></a></div>";
			if($data['uid'] == $_SESSION['uid']){
				self::$btext .= "
				<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."comment/".$data['lit_id']."/edit/".$data['comment_id']."'><div class='button'>Edit</div></a></div>
				<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."comment/".$data['lit_id']."/delete/".$data['comment_id']."'><div class='button'>Delete</div></a></div>";
			}
			self::$btext .= "<div class='col-md-1'></div>
			</div></div></li>";
		}elseif($type == "fd"){
			self::$btext .= "<div class='content_block'>
			<div class='container'>";
			if($data['dialogue'] == 0 && $data['comment'] != ""){
				self::$btext .= "<div class='row'><div class='col-md-12'><div class='error'>Pending response</div></div></div>";
			}
			self::$btext .= "<div class='row'><div class='col-md-12'><div class='lt_title'><a href='".SERVER_ROOT_ONLY."literature/viewfeedbacks/{$lid}/{$data['fdbk_id']}'> Score : {$data['rating']}/10 </a></div></div></div>

		
			</div></div>";
		}elseif($type == "fdrq"){
			self::$btext .= "<div class='content_block'>
			<div class='container'>";
			if($data['dialogue'] == 0){
				self::$btext .= "<div class='row'><div class='col-md-12'><div class='error'>Pending response</div></div></div>";
			}
			self::$btext .= "<div class='row'><div class='col-md-12'><div class='lt_title'>Score : {$data['rating']}/10</div></div></div>
			<div class='row'><div class='col-md-6'><div class='lt_user'>Request By ".stristr($data['lit_id'], "_", TRUE)."</div></div><div class='col-md-4'><a href='".SERVER_ROOT_ONLY."literature/extendfeedback/respond/{$data['fdbk_id']}'><div class='button'>Reply</div></a></div></div>
		
			</div></div>";
		}elseif($type == "rp"){
			self::$btext .= "<div class='content_block'>
			<div class='container'>
			<div class='row'><div class='col-md-12'><div class='lt_user'>On {$data['time']}</div></div></div>
			<div class='row'><div class='col-md-12'><div class='lt_title'>{$data['content']}</div></div></div>
			<div class='row'><div class='col-md-3'><a href='".SERVER_ROOT_ONLY."report/{$data['type']}/{$data['id']}/rm'><div class='button'>Delete Report</div></a></div></div>

		
			</div></div>";
		}elseif($type == "us"){
			self::$btext .= "<div class='content_block'>
			<div class='container'>
			<div class='row'><div class='col-md-12'><div class='lt_title'><a onclick=\"send_data('".SERVER_ROOT_ONLY."user/profile/{$data['uid']}')\"> {$data['uid']} </a></div></div></div></div></div>";
		}
	}
}