<!DOCTYPE HTML>
<html>

<head>

<link href='http://fonts.googleapis.com/css?family=Roboto+Slab:100,400' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>
<title>
	<?php echo $title; ?> | Critiq.in
</title>
<meta name=”description” content=”Critiq allows you to share creative writing compositions and their feedback”>
<meta http-equiv="content-type" content="text/html;charset=UTF-8">
<meta name="revisit-after" content="30 days">
<meta name="distribution" content="web">
<META NAME="ROBOTS" CONTENT="INDEX, NOFOLLOW">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" 
    content="width=device-width, minimum-scale=1.0, initial-scale=1.0, user-scalable=yes">
  <script src="<?php echo SERVER_ROOT_ONLY; ?>components/platform/platform.js">
  </script>

<link rel="import"
  href="<?php echo SERVER_ROOT_ONLY; ?>components/core-header-panel/core-header-panel.html">


<link rel='stylesheet' href="<?php echo SERVER_ROOT_ONLY; ?>styles/bootstrap.min.css" />
<link rel='stylesheet' href="<?php echo SERVER_ROOT_ONLY; ?>styles/main.css" />
<script type='text/javascript' src="<?php echo SERVER_ROOT_ONLY; ?>scripts/jquery.min.js"></script>
</head>
<body unresolved touch-action="auto">

<div id='navbar'>
	<div class='container'>
		<div id='head_logo'><a href="<?php echo SERVER_ROOT_ONLY.'home'; ?>">Critiq.<span class='in'>in</span></a></div>
		<a id='nav_toggle' onclick='nav_toggle()'>Toggle</a>
		
		</div>
		<div id='navbar_hidden_1'>
		<div class='container'><div class='row'><div class='col-md-9'><div id='success'>Loading</div></div><div class='col-md-3'><a onclick='close_data()'><div class='button'>Close</div></a></div></div></div>
		</div>
			  	<div id='navbar_hidden_2'>
				<div class='container'>
		
		<?php if($lgn == TRUE){
		require_once(SERVER_ROOT.'private/controller/user.php');
		require_once(SERVER_ROOT.'private/model/user.php');
		require_once(SERVER_ROOT.'private/view/user.php');
		ob_start();
		$usr = new user_controller(array("user_stats"));
		unset($usr);
		?>
	
            <div class='row'><div class='col-md-12'><a href="<?php echo SERVER_ROOT_ONLY; ?>home"><div class='button'>Home</div></a></div></div>
            
		   <div class='row'><div class='col-md-3'><a href="<?php echo SERVER_ROOT_ONLY; ?>literature/add"><div class='button'>Post A Lit</div></a></div>
            <div class='col-md-3'><a href="<?php echo SERVER_ROOT_ONLY; ?>literature/feedback/rand"><div class='button'>Review A Lit</div></a></div>
            <div class='col-md-3'><a href="<?php echo SERVER_ROOT_ONLY; ?>report/view"><div class='button'>My Reports</div></a></div>
            <div class='col-md-3'><a href="<?php echo SERVER_ROOT_ONLY; ?>search"><div class='button'>Search</div></a></div>
			</div>
			<div class='row'>
			<div class='col-md-4'><a href="<?php echo SERVER_ROOT_ONLY; ?>user/profile"><div class='button'>Profile</div></a></div>
		   <div class='col-md-4'><a href="<?php echo SERVER_ROOT_ONLY; ?>user/settings"><div class='button'>Settings</div></a></div>
            <div class='col-md-4'><a href="<?php echo SERVER_ROOT_ONLY; ?>home/logout"><div class='button'>Log Out</div></a></div></div>
           
			

	
	<?php echo ob_get_clean(); }else{
	ob_start(); 
	?>
	
	  <div class='row'><div class='col-md-4'><a href="<?php echo SERVER_ROOT_ONLY; ?>home"><div class='button'>Log In</div></a></div>
            <div class='col-md-4'><a href="<?php echo SERVER_ROOT_ONLY; ?>about"><div class='button'>About Critiq.in</div></a></div>
            <div class='col-md-4'><a href="<?php echo SERVER_ROOT_ONLY; ?>register"><div class='button'>Sign Up</div></a></div>

			</div>
			
	
	<?php
	echo ob_get_clean();
	} ?>
	</div></div>
	
</div>
<div id='content'>
<div class='container'>

	<div class='col-md-12'>
		<?php echo $btext; ?>
	</div>
</div>
</div>
</body>
<script>
function nav_toggle(){
	$("#navbar_hidden_2").slideToggle();
	
}
function close_tut_panel(){
	$("#tut_panel").slideUp();
}

function send_data(link){

	
	$("#success").load(link, function(response){
		$("#navbar_hidden_1").slideDown();
	});
}
function post_data(form_id, link){
	form_id = "#" + form_id;
	var xhr = $.post(link, $(form_id).serialize(), function(response){
		$("#success").html(response);
		$("#navbar_hidden_1").slideDown();
		$(form_id + " *").attr('disabled', 'true');
	});
}
function close_data(){
	$("#navbar_hidden_1").slideUp();
	if($("form").length >0){
		$("form *").removeAttr('disabled');
	}
	if($("form").attr('id') != "usersettings" && $("form").attr('id') != "editlit" && $("form").attr('id') != "editcomment"){
		// clear the form 
		$("input[type=text], textarea").val("");
	}
}

</script>
</html>
