<?php
/*
	filestore-sa mit bootstrap!

	bei 1und1 ist die script execution time auf 60 sec. festgelegt und läßt sich auch nicht per php.ini ändern!

	!! Deshalb können keine großen Dateien hochgeladen werden !!

*/

	@session_start(); // session aufrufen
	header('Content-Type: text/html; charset=utf-8');

	// load config file
	if ( file_exists( 'config.localhost.php' ) ) {
		include_once ('config.localhost.php');
	} else {
		include_once ('config.inc.php');
	}

	// Übergebenen Parameter in Array
	$_SESSION['cfg_param'] = explode( ";", $_GET['param'] );
	// wurde der erste Aufruf mit Parametern getätigt? Speichern für Aufruf nach dem einloggen...
//	if ( !isset( $_SESSION["startParam"] ) ) {
//		$_SESSION["startParam"] = ( empty( $_GET['param'] ) ? "" : $_GET['param'] );
//	}

//	set_time_limit(0); // Scripte laufen unbegrenzt, Upload dauert schon mal länger
	error_reporting (0); // zeigt alle Fehler - mit "0" abschalten, -1 zeigt alle Fehler!
//	date_default_timezone_set("Europe/Berlin");

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
		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css">
<!--	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css"> -->
		<!-- Optionales Theme -->
<!--		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css"> -->
		<title><?php echo $index->title(); ?></title>
<!--		<script type="text/javascript" src="jQuery/jquery.min.js"></script> -->
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<!--		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script> -->
<!--		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script> -->
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
	</head>
	<body>
		<nav class="navbar navbar-expand-lg navbar-light bg-light" >
		 	<a class="navbar-brand" href="index.php">
				filestore-sa
		 	</a>
			<button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#myNavbar">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="myNavbar">
				<?php $index->navigation(); ?>
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
