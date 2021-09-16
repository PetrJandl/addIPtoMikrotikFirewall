<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="cs">
<head>
	<meta charset="utf-8">
	<title>IP</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<link rel="apple-touch-icon" sizes="57x57" href="/fav/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/fav/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/fav/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/fav/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/fav/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/fav/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/fav/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/fav/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/fav/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/fav/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/fav/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/fav/favicon-16x16.png">
	<link rel="manifest" href="/fav/manifest.json">
	<meta name="msapplication-TileColor" content="#000000">
	<meta name="msapplication-TileImage" content="/fav/ms-icon-144x144.png">
	<meta name="theme-color" content="#000000">


	<style type="text/css">

	::selection { background-color: #E13300; color: white; }
	::-moz-selection { background-color: #E13300; color: white; }

	@media screen and (max-height: 575px){
	    #rc-imageselect, .g-recaptcha {transform:scale(0.95);-webkit-transform:scale(0.95);transform-origin:0 0;-webkit-transform-origin:0 0;}
	}
	@media (max-width: 992px) {
	    body {
		margin: 0px;
	    }
	}
	@media (min-width: 992px) {
	    body {
		margin: 0 auto;
		width: 600px;
		
	    }
	}

	body {
		background-color: #000;
		font: 13px/20px normal Helvetica, Arial, sans-serif;
		color: #ccc;
	}

	a {
		color: #eef;
		background-color: transparent;
		font-weight: normal;
	}

	h1 {
		color: #eee;
		background-color: transparent;
		border-bottom: 1px solid #D0D0D0;
		font-size: 19px;
		font-weight: normal;
		margin: 0 0 14px 0;
		padding: 14px 15px 10px 15px;
	}

	code {
		font-family: Consolas, Monaco, Courier New, Courier, monospace;
		font-size: 12px;
		background-color: #f9f9f9;
		border: 1px solid #D0D0D0;
		color: #002166;
		display: block;
		margin: 14px 0 14px 0;
		padding: 12px 10px 12px 10px;
	}

	#body {
		margin: 0 5px 0 5px;
	}

	p.footer {
		text-align: right;
		font-size: 11px;
		border-top: 1px solid #D0D0D0;
		line-height: 32px;
		padding: 0 10px 0 10px;
		margin: 20px 0 0 0;
	}

	#container {
		margin: 10px;
		border: 1px solid #D0D0D0;
		box-shadow: 0 0 8px #D0D0D0;
	}
	.red{
	    color: #f66;
	}
	.green{
	    color: #6f6;
	}


.btn-primary {
    color: #fff;
    background-color: #007bff;
    border-color: #007bff;
}

.btn {
    display: inline-block;
    font-weight: 400;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border: 1px solid transparent;
    padding: .375rem .75rem;
    font-size: 1rem;
    line-height: 1.5;
    border-radius: .25rem;
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}

	</style>

<script type="text/javascript">
var timer=0;
function startTimer()
{
    setInterval("timerUp()",1000);
}

function timerUp()
{
    timer++;
        var resetat=10; //change this number to adjust the length of time in seconds
    if(timer==resetat)
    {
	window.location = "/";
    }
    var tleft=resetat-timer;
    document.getElementById('timer').innerHTML=tleft;
}
    
</script>

</head>
<body <?php if(isset($reload)){ ?>onload="startTimer()"<?php } ?>>

<div id="container">
	<h1>Vítej! <?php echo $ip; ?></h1>

	<div id="body">
		<?php
		    echo $message;
		?>
<div class="text-right">V tuto chvíli je povoleno : <?php echo number_format($allAllowIP,0,",","."); ?> adres.</div>
	</div>

	<p class="footer">Knihovna města Hradce Králové &copy; 2018</p>
</div>
<?php if($add){ ?>
<script src='https://www.google.com/recaptcha/api.js?hl=cs'></script>
<?php } ?>
</body>
</html>
