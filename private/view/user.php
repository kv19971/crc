<?php
class user_view extends view{
	public function stats_nav_generate($data){
		$this->printliterature = TRUE;
		echo "<div class='row' id='ustats'>
		<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."user'>{$data['nolits']} Lit(s) Posted</a></div>
		<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."user'>{$data['nocritiqued']} Lit(s) Critiqued</a></div>
		<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."report/view/against'>{$data['noreportsagainst']} Report(s) filed Against You</a></div>
		<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."report/view/by'>{$data['noreportsby']} Report(s) filed by You</a></div>
		<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."literature/extendfeedback/viewrequests/for'>{$data['nofeedbacksrequested']} Requests to extend feedback for You</a></div>
		<div class='col-md-2'><a href='".SERVER_ROOT_ONLY."literature/extendfeedback/viewrequests/by'>{$data['nofeedbacksrequestedby']} Requests to extend feedback by You</a></div>
		</div>";
	}
	public function show_profile($uid, $bio, $fav_count, $byfav_count){
		$this->title = $uid;
		self::$btext .= "<div class='row'><div class='col-md-5'><div class='title'>".$uid."</div></div><div class='col-md-2'><a href='".SERVER_ROOT_ONLY."user/showfavourites/user/{$uid}'>".$fav_count."</a> users have favourited {$uid}</div><div class='col-md-2'><a href='".SERVER_ROOT_ONLY."user/showfavourites/byuser/{$uid}'>".$byfav_count."</a> users have been favourited by {$uid}</div><div class='col-md-3'><a onclick=\"send_data('".SERVER_ROOT_ONLY."user/favourite/{$uid}')\"><div class='button'>Favourite This User</div></a></div></div>
		<div class='row'><div class='col-md-12'><div id='u_bio' class='content_block'>".$bio."</div></div></div>";
	}
	public function print_lits_head(){
		self::$btext .= "";
	}
	public function print_fav_head($type, $uid){
		if($type == "user"){
			$this->title = "People who favourited ".$uid;
		}else{
			$this->title = "People favourited by ".$uid;
		}
		self::$btext .= "<div class='row'><div class='col-md-12'><div class='title'>".$this->title."</div></div></div>";
	}
	public function print_end($uid){
		self::$btext .= "<div class='row'><div class='col-md-3'><a href='".SERVER_ROOT_ONLY."report/user/{$uid}'><div class='button'>Report This User</div></a></div></div>";

	}
	public function no_results(){
		self::$btext .= "This user hasnt posted any lits";
	}
	public function show_settings_form($data){
		$this->title = "Settings";
		if($data['shownsfw'] == 1){
			$chkdd = "checked = 'checked'";
		}else{
			$chkdd = "";
		}
		self::$btext .= "<div class='row'><div class='col-md-12'><div class='title'>Settings</div></div></div>
		<form id='usersettings'>
		<div class='row'>	
		<div class='col-md-2'></div><div class='col-md-2'>
		Email</div><div class='col-md-6'><input class='text' type='text' name='mail' value='{$data['mail']}'/></div></div>


		<div class='row'>	
		<div class='col-md-2'></div><div class='col-md-3'>
		Show NSFW Content</div><div class='col-md-5'>
		<input type='checkbox' name='shownsfw' value='1' class='text' {$chkdd}/></div></div> 
		<div class='row'>	
		<div class='col-md-2'></div><div class='col-md-2'>Bio</div></div>
		<div class='row'>	
		<div class='col-md-2'></div><div class='col-md-8'><textarea class='text' name='bio'>{$data['bio']}</textarea></div></div>
		<div class='row'>	
		<div class='col-md-2'></div><div class='col-md-2'>Password (Leave Blank if not changing)</div><div class='col-md-6'>
		<input class='text' type='password' name='pass1' /></div></div>
		<div class='row'>	
		<div class='col-md-2'></div><div class='col-md-2'>Confirm Password (Leave Blank if not changing)</div><div class='col-md-6'>
		<input class='text' type='password' name='pass2' /></div></div>
		<div class='row'>
			<div class='col-md-3'></div>
			<div class='col-md-6'>
			<input class='button' onclick='post_data(\"usersettings\", \"".SERVER_ROOT_ONLY."user/settings\")' value='Save changes' />
			</div>
			</div>";
	}
}