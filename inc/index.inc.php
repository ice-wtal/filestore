<?php
/*
	ToDo:
	- Version 0.1: nur Dateien per FTP hochladen und anzeigen > erl.
	- Version 0.2: Login für Adminuser > erl.
		- Dateien löschen können, erl.
		- bei doppelter Datei den Upload abbrechen
			- anbieten den Namen zu ändern, z.B. (1) anhängen
		- Dateien in Datenbank (Tabellen erstellen, ändern per install script)
		- Dateinamen / Downloadnamen anpassen
		- Suche
	- Version 0.3: Upload von Dateien für Adminuser

	Datei ersetzen können, also nach vorhandener Datei suchen und Link von dort auf die neue Datei erstellen?


*/
class index {
	private $mysqli;

	function __construct() {	// wird vor jeglicher Ausgabe ausgeführt!
		// Verbindung zur Datenbank herstellen
		$this->mysqli = new mysqli( $_SESSION['cfg_db_host'], $_SESSION['cfg_db_user'], $_SESSION['cfg_db_pass'], $_SESSION['cfg_db_name'] );
		mysqli_set_charset( $this->mysqli, 'utf8'); // lt. PHP Doku ist dies der bevorzugte Weg!
		if (mysqli_connect_errno()) {
    		printf("Connect failed: %s\n", mysqli_connect_error());
    		exit();
		}

		include_once ($_SESSION['cfg_rootdir'].'/inc/login.inc.php');
		$login = new login( $this->mysqli );
//		include_once ($_SESSION['config']['rootdir'].'/inc/functions.inc.php');
//		$this->functions = new functions();
/*
		// Logout gewünscht oder länger als x Minuten keine Eingabe?
		if ( $this->param[0] == "logout" OR !empty( $_POST["logout"] ) OR $_SESSION['config']['lastAccess'] < time() - $_SESSION['config']['autoLogout'] * 60 AND !empty( $_SESSION["email"] ) ) {
			$this->login->logout();
		} else {
			include_once ($_SESSION['config']['rootdir'].'/inc/log.inc.php');
			$log = new log();
			$this->logging = $log;
			$log->logText( implode( ";", $this->param ), 1 );
		}
		// Zeit des letzten Zugriffs im Cookie setzen
		$_SESSION['config']['lastAccess'] = time();
		// Wurde Bestätigungsmail / -link geklickt
		$this->confirm->linkCheck();
		// Eingeloggt?
		if ( empty( $_SESSION["email"] ) AND !empty( $_POST["login"] ) ) {
			$this->login->verifyUser();
		}

*/
	}
	function title() {
		return "filestore-sa";
	}
	function navigation() {
		// Menüpunkt für Startseite
		echo '<ul class="navbar-nav mr-auto">
					<li class="nav-item' . ( empty( $_SESSION['cfg_param'][0] ) ? ' active' : "" ) . '">
							<a class="nav-link" href="index.php">Startseite</a></li>
					<div class="dropdown-divider"></div>';
/*
		if ( $_SESSION['cfg_userno'] ) { // extra Punkte wenn eingeloggt
			echo '<li' . ( $_SESSION['cfg_param'][0] == "upload" ? ' class="active"' : "" ) . '>
						<a href="index.php?param=upload">Fileupload</a></li>
					<li role="separator" class="divider"></li>';
		}
*/
			echo '<li class="nav-item' . ( $_SESSION['cfg_param'][0] == "imprint" ? ' active' : "" ) . '">
						<a class="nav-link" href="index.php?param=imprint">Imprint</a></li>
			</ul>';
		// Loginform - ausblenden wenn eingeloggt
		echo '<form class="form-inline" role="login" action="' . $_SERVER['REQUEST_URI'] . '" name="loginform" method="post">';
		if ( $_SESSION['cfg_userno'] ) {
			echo '<button type="submit" class="btn btn-outline-success" name="logout" value="1"><span class="glyphicon glyphicon-log-out"></span> Logout</button>';
		} else {
			echo '<input type="text" class="form-control mr-sm-2" name="username" placeholder="Username">
						<input type="password" class="form-control mr-sm-2" name="password" placeholder="Password">
						<button type="submit" class="btn btn-outline-success my-2 my-sm-0" name="login" value="1"><span class="glyphicon glyphicon-log-in"></span> Login</button>';
		}
		echo '</form>
					<p>&nbsp;&nbsp;&nbsp;</p>';
		// Suchfunktion rechts, wird immer angezeigt
		echo '<form class="form-inline" role="search" action="' . $_SERVER['REQUEST_URI'] . '" name="listing" method="get">
						<input type="text" class="form-control mr-sm-2" name="search" placeholder="Search">
						<button type="submit" class="btn btn-outline-success" onClick="return checkSearch()" value="1">Search</button>
					</form>';
	}
	function body() { // wird im Hauptbereich angezeigt

//		print_r( $_POST );
		$this->message(); // Infos darstellen

		if ( !empty( $_GET['search'] ) ) { // starte Suche
			echo '<div class="col-md-10 col-md-offset-1">';
			echo '<h2>Search not implemented yet!</h2>';
			echo '<p>Searchkey: "' . $_GET['search'] . '"</p>';
			echo '</div>';
		} elseif ( $_SESSION['cfg_param'][0] == "imprint" ) { // Imprint
			echo '<div class="col-md-8 col-md-offset-2">';
			$this->imprint();
			echo '</div>';
		} elseif ( $_SESSION['cfg_param'][0] == "install" ) { // Install
			echo '<div class="col-md-8 col-md-offset-2">';
			$this->install();
			echo '</div>';
		} elseif ( $_SESSION['cfg_param'][0] == "upload" AND $_SESSION['cfg_userno'] ) {
			echo '<div class="col-md-8 col-md-offset-2">';
			include_once ($_SESSION['cfg_rootdir'].'/inc/file_op.inc.php');
			$file_op = new file_op( $this->mysqli );
			$file_op->uploadSimple();
//			phpinfo();
//			$file_op->uploadDropzone();
			echo '</div>';
		} else {
			echo '<div class="col-md-10 offset-md-1">';
			include_once ($_SESSION['cfg_rootdir'].'/inc/file_op.inc.php');
			$file_op = new file_op( $this->mysqli );
			$file_op->body();
			echo '</div>';
		}
		// cron > automatisches ausloggen
		echo '<div id="scriptFenster"></div>
					<script type="text/javascript">
						function cronjobs() {
							$.post(\'inc/index.inc.php\', { autologout:1 },
								function(data) {
									$( \'#scriptFenster\').html( data );
	//								alert( data );
								}
							);
						}
						var aktiv = window.setInterval("cronjobs()", 60*1000);
					</script>';

	}
	function install() {
		echo '<div class="page-header">
				<h1>Installation</h1>
			</div>';
		include_once ($_SESSION['cfg_rootdir'].'/inc/install.inc.php');
		$install = new install( $this->mysqli );
		$install->body();
		if ( !empty( $_POST['db_setup'] ) ) {
			echo '<div class="progress">
					  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
						<span class="sr-only">60% abgeschlossen</span>
					  </div>
					</div>';
			$install->body();
		echo '<script type="text/javascript">
					 var valeur=100;
					 $(\'.progress-bar\').css(\'width\', valeur+\'%\').attr(\'aria-valuenow\', valeur);
				</script>';
		}
		echo '<form class="form-horizontal" action="' . $_SERVER['REQUEST_URI'] . '" name="geraet" method="post">
				<button class="btn btn-default" type="submit" name="db_setup" value="1" >Setup Database</button>
			</form>
			';
	}
	function imprint() {
		echo '<div class="page-header">
				<h1>Impressum <small>Betreiber der Webseite gemäß §5 TMG:</small></h1>
			</div>
			<address>
			<strong>IMP InterMediaPartners GmbH</strong><br>
			Beyeröhde 14<br>
			D-42389 Wuppertal<br>
			<abbr title="Telefonnummer">Tel.:</abbr> +49 202 27169 0
			</address>
			Internet: <a href="http://intermediapartners.de" target="_blank">www.intermediapartners.de</a><br>
			Vertretungsberechtigte Geschäftsführer: Uwe Riemeyer, Sven Anacker<br>
			Registernummer: HRB 5775, AG Wuppertal<br>
			Umsatzsteuer ID: DE121096580
			<h3>Verantwortlich für den Inhalt:</h3>
			Sven Anacker<br>
			Telefon: +49 202 27169 11<br>
			Email: <a href="mailto:webmaster@intermedia.partners">webmaster@intermedia.partners</a>';
	}
	function footer() {
		echo '<div class="col-xs-12 col-md-8 offset-md-2">
						<div class="card">
							<div class="card-body">
								<p class="text-center text-muted">
									<a href="index.php">Homepage</a> | <a href="index.php?param=imprint">Imprint</a><br>
									&copy; 2015 - ' . date("Y") . ' Sven Anacker, IMP InterMediaPartners GmbH, Beyer&ouml;hde 14, 42389 Wuppertal
								</p>
							</div>
						</div>
					</div>';
	}
	function message() {
		foreach ( (array) $_SESSION['message'] as $message ) {
			$arr = explode("::", $message );
			$level = array( "success", "info", "warning", "danger" );
			echo '<div class="alert alert-' . $level[ $arr[1] ] . '" role="alert">' . $arr[0] . '</div>';
		}
		unset( $_SESSION['message'] );
	}
}

$ajax_index = new ajax_index();

class ajax_index {

	private $mysqli;
	private $db_func;
	private $functions;

	function __construct() {
		@session_start(); // session aufrufen, für sepWin 1
		// Sprachsatz auf Deutsch umfriemeln, damit die Umlaute richtig geschrieben werden
		// nur nötig wenn diese Datei nicht "UTF-8 ohne Bom" kodiert ist
//		header('Content-Type: text/html; charset=iso-8859-1');
//		setlocale (LC_ALL, 'de_DE@euro', 'de_DE', 'deu_deu', 'ge');
		include_once( '../config.inc.php');

		// mysqli öffnen
		$this->mysqli = new mysqli( $_SESSION['cfg_db_host'], $_SESSION['cfg_db_user'], $_SESSION['cfg_db_pass'], $_SESSION['cfg_db_name'] );
		mysqli_set_charset( $this->mysqli, 'utf8');
//		mysqli_set_charset( $this->mysqli, $_SESSION['config']['db_charset'] );
		if (mysqli_connect_errno()) {
    		printf("Connect failed: %s\n", mysqli_connect_error());
    		exit();
		}


		if ( $_POST['autologout'] ) {
			if ( $_SESSION['cfg_userno'] ) {
				$logout = $_SESSION['cfg_autologout'] * 60;
				if ( ( $_SESSION['cfg_lastRefresh'] + $logout ) < time() ) {
					unset ( $_SESSION['cfg_userno'] );
					echo '<script type="text/javascript">
									window.location.href = "index.php";
								</script>';
				}
			}
		}
//			echo $this->test();
	}
	private function test() {
		echo "Dies ist ein Test!<br>
				Es ist " . date("G:i:s") . ' Uhr.<br>
				Umlaute: üöäÜÖÄß.<br>
				User Nr: ' . $_SESSION['cfg_userno'] . '<br>
				Last Refresh:' . $_SESSION['cfg_lastRefresh'] . '<br>
				Jetzt: ' . time();
	}
}
?>
