<?php
/*


*/
$file_op_ajax = new file_op_ajax();

class file_op_ajax {

	private $mysqli;
	private $folders;			// Array mit den Ordnern
	private $files;				// array mit allen Dateien

	function __construct() {	// wird vor jeglicher Ausgabe ausgeführt!
		@session_start(); // session aufrufen
		// load config file
		if ( file_exists( '../config.localhost.php' ) ) {
			include_once ('../config.localhost.php');
		} else {
			include_once ('../config.inc.php');
		}

		// Verbindung zur Datenbank herstellen
		$this->mysqli = new mysqli( $_SESSION['cfg_db_host'], $_SESSION['cfg_db_user'], $_SESSION['cfg_db_pass'], $_SESSION['cfg_db_name'] );
		mysqli_set_charset( $this->mysqli, 'utf8'); // lt. PHP Doku ist dies der bevorzugte Weg!
		if (mysqli_connect_errno()) {
    		printf("Connect failed: %s\n", mysqli_connect_error());
    		exit();
		}

		if ( $_POST['aktion'] == "double" ) {
			$this->checkDouble();
		} elseif ( !empty( $_POST['deleteFile'] ) ) {
			echo "Lösche " . $_POST['deleteFile'];
			unlink( $_SESSION['cfg_rootdir'] . "/" . $_POST['deleteFile'] );
		} elseif ( $_POST['aktion'] == "showList" ) {
			$this->folders2array();
			$this->files2array();
			$this->filearray2list();
//			$this->test();
		}

//		include_once ($_SESSION['cfg_rootdir'].'/inc/login.inc.php');
//		$login = new login( $this->mysqli );
	}
	private function test() {
		echo "Dies ist ein Test!
				Es ist " . date("G:i:s") . " Uhr.
				Umlaute: üöäÜÖÄß.
				Letzte Aktion vor " . ( time() - $_SESSION['config']['lastAccess'] ) . " Sekunden.
				Rechte: " . $_SESSION['config']['rights'][1] . '<br>
				Dateiname  ' . $_POST['datei'] . " wurde übergeben!";
	}
	function folders2array() {
		$folders = glob( $_SESSION['cfg_rootdir'] . '/files/*' );
		foreach( $folders as $folder ) {
			$this->folders[] = substr_replace( $folder, "", 0, ( strlen( $_SESSION['cfg_rootdir'] ) + 1 ) );
		}
	}
	function files2array() {
		$i = 0;
		foreach( $this->folders as $folder ) {
			$files = glob( $_SESSION['cfg_rootdir'] . '/' . $folder . '/*' );
			// Verzeichnis abziehen
			foreach( $files as $file ) {
				$this->files[$i][] = substr_replace( $file, "", 0, ( strlen( $_SESSION['cfg_rootdir'] . '/' . $folder ) + 1 ) );
				$this->files[$i][] = substr_replace( $file, "", 0, ( strlen( $_SESSION['cfg_rootdir'] ) + 1 ) );
				$i++;
			}
		}
	}
	function human_filesize($bytes, $decimals = 2) {
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}
	function filearray2list () {
		echo '<table class="table table-hover">';
		foreach( $this->files as $file ) {
//			echo filectime( $_SESSION['cfg_rootdir'] . "/" . $file[1] ) . "<br>";
			echo "<tr>";
			// Lösch- Button und Datum! nur wenn angemeldet!
			if ( $_SESSION['cfg_userno'] ) {
				echo '<td>
								<button type="button" class="btn btn-danger" onClick="deleteFile( this );" value="' . $file[1] . '" >
									<i class="far fa-trash-alt"></i>
								</button>
								<button type="button" class="btn btn-outline-primary btn-sm" onClick="changeFile( this );" value="' . $file[1] . '" >
									Aktualisieren
								</button>
							</td>
							<td>
								' . date( "d.m.Y", filectime( $_SESSION['cfg_rootdir'] . "/" . $file[1] ) ) . '
							</td>';
			}
			echo '<td>
							<a href="' . $file[1] . '" target="_blank" >
								' . $file[0] . '
							</a>
						</td>
						<td class="text-right"><span class="badge">' . $this->human_filesize( filesize( $_SESSION['cfg_rootdir'] . "/" . $file[1] ), 1 ) . '</span></td>
						';
			echo "</tr>";
		}
		echo '</table>
					<script type="text/javascript">
						function changeFile( a ) {
							$("#neueDatei").val( a.value );
							$("#uploadModal").modal("show");
						}
						function deleteFile( a ) {
							$.post(\'inc/file_op.ajax.php\', { deleteFile:a.value },
							function(data) {
//								alert( "Hallo Welt!" );
								$("#dateiTabelle").append( data );
								updateTabelle();
							})
						}
					</script>';
	}
	private function checkDouble() {
		include_once( $_SESSION['cfg_rootdir'].'/inc/file_op.inc.php');
		$file_op = new file_op( $this->mysqli );
		$file_op->folders2array();
		$file_op->files2array();
		$datei = $file_op->renameFile( $_POST['datei'] );
		$doppelt = 0;
		foreach( $file_op->files as $file ) {
			if ( $datei == $file[0] ) {
				$doppelt = 1;
			}
		}
		echo $doppelt;
	}
}
?>
