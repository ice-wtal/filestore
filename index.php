<?php
/*
	filestore-sa mit bootstrap!
	
	<!-- For bootstrap fixed Nav, body padding is required -->
		<style type="text/css">
		  body { padding-top: 70px; }
		</style>
*/
	@session_start(); // session aufrufen
	header('Content-Type: text/html; charset=utf-8');
	include_once ('config.inc.php');

	// Übergebenen Parameter in Array
	$_SESSION['cfg_param'] = explode( ";", $_GET['param'] );
	// wurde der erste Aufruf mit Parametern getätigt? Speichern für Aufruf nach dem einloggen...
//	if ( !isset( $_SESSION["startParam"] ) ) {
//		$_SESSION["startParam"] = ( empty( $_GET['param'] ) ? "" : $_GET['param'] );
//	}


	# Hauptseite laden
	include_once ($_SESSION['cfg_rootdir'].'/inc/index.inc.php');
	$index = new index();

// charset unter WinXP: ISO-8859-1, Win7: utf-8
?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
	<head>
		<meta name="robots" content="index, nofollow">
		<meta http-equiv="expires" content="0">
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<!-- Compatibility Mode for IE -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
		<!-- Optionales Theme -->
<!--		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"> -->
		<title><?php echo $index->title(); ?></title>
		<script type="text/javascript" src="jQuery/jquery.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-default" >
		  <div class="container-fluid">
			<div class="navbar-header">
			 <a class="navbar-brand" href="index.php">
				filestore-sa
			 </a>
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
			</div>
			<div class="collapse navbar-collapse" id="myNavbar">
				<?php $index->navigation(); ?>
			</div>
		  </div>
		</nav>

		<div class="container-fluid">
			<?php $index->body(); ?>
		</div>
		<div class="container-fluid">
			<?php $index->footer(); ?>
		</div>
	</body>
</html>
