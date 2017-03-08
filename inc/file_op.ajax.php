<?php/**/$file_op_ajax = new file_op_ajax();class file_op_ajax {	private $mysqli;	function __construct() {	// wird vor jeglicher Ausgabe ausgeführt!
		// load config file
		if ( file_exists( '../config.localhost.php' ) ) {
			include_once ('../config.localhost.php');
		} else {
			include_once ('../config.inc.php');
		}

		// Verbindung zur Datenbank herstellen
		$this->mysqli = new mysqli( $_SESSION['cfg_db_host'], $_SESSION['cfg_db_user'], $_SESSION['cfg_db_pass'], $_SESSION['cfg_db_name'] );		mysqli_set_charset( $this->mysqli, 'utf8'); // lt. PHP Doku ist dies der bevorzugte Weg!		if (mysqli_connect_errno()) {
    		printf("Connect failed: %s\n", mysqli_connect_error());
    		exit();
		}

		if ( $_POST['aktion'] == "double" ) {
			$this->checkDouble();
//			$this->test();
		}

//		include_once ($_SESSION['cfg_rootdir'].'/inc/login.inc.php');//		$login = new login( $this->mysqli );	}	private function test() {		echo "Dies ist ein Test!				Es ist " . date("G:i:s") . " Uhr.				Umlaute: üöäÜÖÄß.				Letzte Aktion vor " . ( time() - $_SESSION['config']['lastAccess'] ) . " Sekunden.				Rechte: " . $_SESSION['config']['rights'][1] . '<br>				Dateiname  ' . $_POST['datei'] . " wurde übergeben!";	}	private function checkDouble() {		include_once( $_SESSION['cfg_rootdir'].'/inc/file_op.inc.php');		$file_op = new file_op( $this->mysqli );		$file_op->folders2array();		$file_op->files2array();		$datei = $file_op->renameFile( $_POST['datei'] );		$doppelt = 0;		foreach( $file_op->files as $file ) {			if ( $datei == $file[0] ) {				$doppelt = 1;			}		}		echo $doppelt;	}}?>