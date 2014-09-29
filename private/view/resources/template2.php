<!DOCTYPE HTML>
<html>
<head>
<link rel='stylesheet' href="<?php echo SERVER_ROOT_ONLY; ?>styles/bootstrap.min.css" />
<script type='text/javascript' src='http://code.jquery.com/jquery.min.js'></script>
<style>
body{
	background:#0B0F10;
	font-family: "Segoe UI","Segoe","Arial",sans-serif;
	color:#828483;
	margin:0;
	padding:0;
}
#login{
	width:auto;
	margin:30px auto;
	text-align:center;
}
.button{
	background:#FDB813;
	color:#222222;
	padding:6px;
	font-size:1.2em;
	border:2px solid #e7e7e7;
}
.button:hover{
	border:5px solid #e7e7e7;
}
#register{
	width:auto;
	font-size:1.8em;
}
form[name=login]{
	text-align:center;
}

form input, form textarea{
	margin:5px;
	font-family: "Segoe UI","Segoe","Arial",sans-serif;
	font-size:1.2em;
	padding:6px;
	border:2px solid #e7e7e7;
}
form[name=login] input{
	padding:6px 15px;
}
form input:focus, form textarea:focus, form input[type=submit]:hover{
	border:5px solid #e7e7e7;
}
form input[type=submit]{
	background:#7FBC41;
	color:#f8f8ff;
}
#header{
	height:65px;
	background:#fafafa;
	
}
#title{
	font-size:5em;
	color:#FFFFFF;
	text-align:center;
	font-family:"Segoe UI Light","Segoe","Segoe UI","Helvetica Neue",sans-serif;
	letter-spacing:0.82px;
}
#subtitle{
	margin:32px auto;
	font-size:3em;
	color:#444;
	text-align:center;
	font-family:"Segoe UI Light","Segoe","Segoe UI","Helvetica Neue",sans-serif;
	letter-spacing:0.82px;
}
#brdr{
	color:#ebebeb;
	font-size:2em;
	margin:24px auto;
	text-align:center;
	letter-spacing:7px;
	font-weight:bold;
}
#heading{
	position:relative;
	top:-0.4em;
	font-size:6em;
	color:#FFFFFF;
}
#subheading{}
#title_block{
	text-align:center;
	font-family: "Rockwell Extra Bold", "Rockwell Bold", monospace;
	font-size:82px;
	text-transform:uppercase;
	color:#04080B;
	background:#FEFEFE;
}
.content_block{
	width:auto;
	color:#04080B;

	padding:10px 20px;
	/*
	font-family: Impact, Haettenschweiler, "Franklin Gothic Bold", Charcoal, "Helvetica Inserat", "Bitstream Vera Sans Bold", "Arial Black", sans serif;
	*/
font-family:"OpenSansBold",Helvetica,Arial,sans-serif;
color: #f7f3ea;
text-align: center;

line-height: 1.4em;
}
.content_block a{
	text-decoration:none;
	color:#04080B;
}
.ppt{
	
	font-size: 70px;

	font-weight:bold;
	margin-top:130px;
	margin-bottom:10px;
	text-align:center;

	
}
.user{
	margin-top:15px;
	margin-bottom:170px;
	font-style:italic;
	font-size:22px;
}
.link_content{

	font-size:35px;
	margin-bottom:60px;
}
</style>
<script>
function send_data(link){
	$("#success").load(link, function(response){
		$(this).html(response);
		$("#success").slideDown();
	});
}
</script>
</head>
<body>
<div id='header'></div>
<?php echo $btext; ?>

</body>
</html>