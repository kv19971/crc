<?php

class home_view extends view{
	public function show_login(){
		$this->title = "Log In";
		$this->logged = FALSE;
		ob_start();
		?>
		<div class='row' id='wpsace'>

</div>
<div class='row'>
<div class='col-md-3'></div>
<div class='col-md-6'><div id='lp_title'><h1>Critiq.<span class='in'>in</span></h1></div></div>
<div class='col-md-3'></div>
</div>
<div class='row'>
<div class='col-md-2'></div>
<div class='col-md-8'><div id='lp_stitle'><h2>Critiq allows you to share creative writing compositions and their feedback.</h2></div></div>
<div class='col-md-2'></div>
</div>
<div class='row'>
<div class='col-md-2'></div>
<form action="<?php echo SERVER_ROOT_ONLY.'home'; ?>" method='POST' name='login'>
<div class='col-md-3'><input class='text' type='text' name='uid' Placeholder='User ID'/></div>
<div class='col-md-3'><input class='text' type='password' name='pwd' Placeholder='Password'/></div>
<div class='col-md-2'><input type='submit' class='button' name='login' value='Sign In'/></div>
<div class='col-md-1'></div>
</form>
</div>
<div class='row'>
<div class='col-md-12'><div id='nom'>Not a member? <a href="<?php echo SERVER_ROOT_ONLY.'register'; ?>">Sign Up!</a></div></div>
</div>
<div id="footer">
	<a href="<?php echo SERVER_ROOT_ONLY.'about'; ?>">About Critiq.in</a>
</div>
		<?php
		self::$btext .= ob_get_clean();
	}
	public function show_home(){
	$this->title = "Home";
	ob_start();
	?>
	<div id='tut_panel'>
		<div class='row'>
			<div class='col-md-11'></div><div class='col-md-1'><a class='closebtn' href="#" onclick='close_tut_panel()'>Close</a></div>
		</div>
		<div class='row'>
			<div class='col-md-12'><div id='panel_title'>Hey There!</div></div>
			<div class='col-md-12'>
				<p><b>Critiq</b> is here to allow you to share your literary works and get feedback on your compositions by other members of our community.</p>
				<p>After you post a lit, your composition, you'll need to review at least 3 other lits before you can post another lit. This is how we ensure that all lits from every user in the community gets quality feedback.</p>
				<p><b>REMEMBER! Please don't be intentionally rude to any of the users. Be sure to, if posting explicit content, check the NSFW (Not Safe For Work) box. Also, don't forget to upvote the things you like.</b></p>
				<p>Happy Writing :D</p>
			</div>
		</div>
	</div>
	<?php
	self::$btext .= ob_get_clean();
	}
	public function print_head(){
		self::$btext .="<div class='row'><div class='col-md-9'><div class='title'>Recommended lits for you</div></div><div class='col-md-3'><a href='".SERVER_ROOT_ONLY."/literature/add'><div class='button'>Post A Lit</div></a></div></div>";
	}
}