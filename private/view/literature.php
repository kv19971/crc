
<?php
class literature_view extends view{
	public function print_exfd_head($bf){
		$this->title = "Requests to extend feedbacks {$bf} you";
		self::$btext .= "<div class='row'><div class='col-md-12'><div class='title'>{$this->title}</div></div></div>";
		
	}
	public function print_feedback_head($tp, $til){
		if($tp == "s"){
			$this->title = "Feedback on {$til}";
		}else{
			$this->title = "Feedbacks on {$til}";
		}
		self::$btext .= "<div class='row'><div class='col-md-12'><div class='title'>{$this->title}</div></div></div>";
	}
	
	public function print_exfd_form($data, $ex = FALSE){
		$this->title = "Extend a feedback";
		self::$btext .= "<div class='row'><div class='col-md-12'><div class='title'>Score : {$data['rating']}/10</div></div></div>
		<div class='row'><div class='col-md-12'><div class='content_block'>{$data['content']}</div></div></div>";
		if($ex == TRUE){
			self::$btext .= "<div class='row'><div class='col-md-4'>The comment</div></div><div class='row'><div class='col-md-12'><div class='content_block'>{$data['comment']}</div></div></div>";
			$arg = "respond/";
		}
		self::$btext .= "<form id='extendfeedback'>
		<div class='row'><div class='col-md-4'>Your Response</div></div>
		<div class='row'><div class='col-md-6'><textarea class='text' name='comment'></textarea></div></div>
		<div class='row'><div class='col-md-1'></div><div class='col-md-4'>
		<input onclick='post_data(\"extendfeedback\", \"".SERVER_ROOT_ONLY."/literature/extendfeedback/{$arg}{$data['fdbk_id']}/2\")' class=\"button\" value=\"Send\">
		</div><div class='col-md-1'></div></div>";
	}
	public function print_s_feedback($data){
		self::$btext .= "<div class='row'><div class='col-md-12'><div class='title'>Score : {$data['rating']}/10</div></div></div>
		
		<div class='row'><div class='col-md-12'><div class='content_block'><pre>{$data['content']}</pre></div></div></div>";
		if($data['comment'] != ""){
		self::$btext .= "<div class='row'><div class='col-md-12'><div class='lt_user'>A comment on this feedback</div></div></div><div class='row'><div class='col-md-12'><div class='content_block'>{$data['comment']}</div></div></div>";
		}
		if($data['response'] != ""){
		self::$btext .= "<div class='row'><div class='col-md-12'><div class='lt_user'>A response to this comment</div></div></div><div class='row'><div class='col-md-12'><div class='content_block'>{$data['response']}</div></div></div>";
		}else{
			self::$btext .= "<div class='row'><div class='col-md-12'><div class='error'>This comment has yet to be given a response</div></div></div>";		
		}
		self::$btext .= "<div class='row' id='lit_btns'>
		<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."report/fdbk/".$data['fdbk_id']."'><div class='button'>Report</div></a></div>
		";
		self::$btext .= "<div class='col-md-4'><a href='".SERVER_ROOT_ONLY."literature/extendfeedback/".$data['fdbk_id']."'><div class='button'>Respond to this feedback</div></div><div class='col-md-1'></div></div>";
	}
	public function show_lit_form($type = "add", $data = array()){
		
		if(!isset($data['lit_id']) || empty($data['lit_id'])){
			$lid = "";
		}else{
			$lid = $data['lit_id'];
		}
		ob_start();
		?>
		<div class='row'><div class='col-md-12'><div class='title'><?php if($type == "edit"){$this->title = "Edit Your Lit"; }else{$this->title = "Add A Lit";} echo $this->title;?></div></div></div>
		<form id="<?php echo $type; ?>lit"><br />
		<?php 
		if($type == "add"){
		echo "<div class='row'><div class='col-md-2'></div><div class='col-md-2'>Title</div><div class='col-md-6'><input class='text' type='text' name='title' /></div></div>";
		}
		?>
		<div class='row'><div class='col-md-2'></div><div class='col-md-2'>NSFW</div><div class='col-md-6'><input type='checkbox' name='nsfw' value='1' <?php if($type == "edit" && $data['nsfw'] == '1'){echo "checked='checked'";} ?>/></div></div>
		<div class='row'><div class='col-md-2'></div><div class='col-md-2'>Keywords (Seprate them with a space)</div><div class='col-md-6'><input type='text' class='text' name='tags' value="<?php if($type == 'edit'){echo $data['tags'];} ?>"/></div></div>
		<div class='row'>
		<div class='col-md-2'></div><div class='col-md-3'>Content</div></div>
		<div class='row'>
		<div class='col-md-2'></div><div class='col-md-8'>
		<textarea class='text' name='content'><?php 
		if($type == 'edit')
		{
		echo $data['content'];
		} 
		?></textarea></div></div>
		<div class='row'>
			<div class='col-md-3'></div>
			<div class='col-md-6'>
			<input onclick='post_data("<?php echo $type; ?>lit", "<?php echo SERVER_ROOT_ONLY."/literature/{$type}/{$lid}/2"; ?>")' class='button' value="<?php echo $type; ?>">
			</div>
			</div>
		
<?php
	self::$btext .= ob_get_clean();
	}
	public function show_feedback_form($lit){
	$this->title = "Review A Lit";
		self::$btext = "
		<div class='row'><div class='col-md-12'><div class='title'>{$lit['title']}</div></div></div>
		<div class='row'><div class='col-md-12'><div class='content_block'>{$lit['content']}</div></div></div>
		<form id='feedback' >
		<div class='row'><div class='col-md-2'></div><div class='col-md-2'>Rate this lit</div><div class='col-md-6'><select name='rate' class='text'><option value=-1>Select</option><option value=1>1</option><option value=2>2</option><option value=3>3</option><option value=4>4</option><option value=5>5</option><option value=6>6</option><option value=7>7</option><option value=8>8</option><option value=9>9</option><option value=10>10</option></select></div></div>
		<div class='row'><div class='col-md-2'></div><div class='col-md-2'>Feedback</div></div>
		<div class='row'><div class='col-md-2'></div><div class='col-md-6'><textarea class='text' name='feedback'></textarea></div></div>
<div class='row'>
			<div class='col-md-3'></div>
			<div class='col-md-6'>
			<input onclick='post_data(\"feedback\", \"".SERVER_ROOT_ONLY."literature/feedback/{$lit['lit_id']}/2\")' class=\"button\" value=\"Post\">
			</div>
			</div>
		</form>";
	}
	public function show_ind_lit($data){
		$this->title = $data['title'];
		self::$btext .= "<div class='row'><div class='col-md-12'><div class='title' id='ltitle'>{$data['title']}</div></div></div><div class='row'><div class='col-md-12'><div class='lt_user'>By <a href='".SERVER_ROOT_ONLY."user/profile/{$data['uid']}'>{$data['uid']}</a></div></div></div>
		<div class='row'><div class='col-md-12'><div class='content_block'><pre>{$data['content']}</pre></div></div></div>
		<div class='row' id='lit_btns'>
			<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."comment/".$data['lit_id']."/add'><div class='button'>Comment</div></a></div>
			
			<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."literature/upvote/".$data['lit_id']."'><div class='button'>Upvote</div></a></div>
			<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."literature/downvote/".$data['lit_id']."'><div class='button'>Downvote</div></a></div>
			<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."report/lit/".$data['lit_id']."'><div class='button'>Report</div></a></div>
		";
		if($data['uid'] != $_SESSION['uid']){
			self::$btext .="<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."literature/feedback/".$data['lit_id']."'><div class='button'>Give Feedback</div></a></div><div class='col-md-1'></div>";
		}else{
			self::$btext .="<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."literature/edit/".$data['lit_id']."'><div class='button'>Edit</div></a></div><div class='col-md-2'><a href='".SERVER_ROOT_ONLY."literature/viewfeedbacks/".$data['lit_id']."'><div class='button'>View feedbacks</div></a></div>";

		}
		self::$btext .= "</div>
		<div id='wspace'></div><div class='row'><div class='col-md-12'><div class='lt_title'>More lits like this one</div></div></div>";
	
	}
	public function append_lit_further_nofound(){
		self::$btext .= "<div class='row'><div class='col-md-12'><div class='error'>Sorry! None found.</div></div></div>";
	}

}