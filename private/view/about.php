<?php
class about_view extends view{
	public function __construct(){
		ob_start();
		$this->title = "About";
		$this->logged = FALSE;
		?>
		<style>
		#abt1{
	background:url("<?php echo SERVER_ROOT_ONLY.'images/abt1.jpg'; ?>");
	height:188px;
	}
	#abt2{
	background:url("<?php echo SERVER_ROOT_ONLY.'images/abt2.jpg'; ?>");
	height:427px;
	}
	#abt3{
	background:url("<?php echo SERVER_ROOT_ONLY.'images/abt3.jpg'; ?>");
	height:168px;
	}
		</style>
		<div class='row' id='wpsace'>

</div>
<div class='row'>
<div class='col-md-3'></div>
<div class='col-md-6'><div id='lp_title'><h1>Critiq.<span class='in'>in</span></h1></div></div>
<div class='col-md-3'></div>
</div>
<div class='row'>
<div class='col-md-2'></div>
<div class='col-md-8'><div id='lp_stitle'><h2>Here's How It Works</h2></div></div>
<div class='col-md-2'></div>
</div>
<div class='row'><div class='col-md-12'><h3 class='cntr'>You Post A Lit</h3></div></div>
<div class='row'>
<div class='col-md-7'><p>A "Lit" is your creative composition. It can be anything from a haiku to a long story. After you're done writing your composition in the textbox, you'll have to give your lit some tags so that other users can find it and give it feedback. Just click "Submit" once you're done.</p></div>
<div class='col-md-5'><div class='img' id='abt1'></div></div>
</div>
<div class='row'><div class='col-md-12'><h3 class='cntr'>You critique 3 other lits</h3></div></div>
<div class='row'>
<div class='col-md-7'><p>Once you're done writing your own lit, you'll have to give feedback to three other lits before you can post your own. These lits have to be posted by users other than yourself. You also cannot give more than one feedback to a lit. This is how Critiq ensures that each lit gets quality feedback. </p></div>
<div class='col-md-5'><div class='img' id='abt2'></div></div>
</div>
<div class='row'><div class='col-md-12'><h3 class='cntr'>You Continue</h3></div></div>
<div class='row'>
<div class='col-md-7'><p>Now you can write your next lit or you can browse through your recommendations to see lits posted by other users!</p></div>
<div class='col-md-5'><div class='img' id='abt3'></div></div>
</div>
<div class='row'>
<div class='col-md-3'></div>
<div class='col-md-6'>
<a href="<?php echo SERVER_ROOT_ONLY.'home'; ?>"><button id='ogi' class='button'>Okay, Got It!</button></a>
</div>
<div class='col-md-3'></div>
</div>
<div id="footer">
	Don't mean to brag, but <a href="http://twitter.com/kverma1997">I</a> made this. 
</div>
		<?php
		self::$btext .= ob_get_clean();
	}
}