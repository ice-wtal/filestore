<?php/*	File Operations	ToDo:
	- "#" Zeichen im Dateinamen macht Probleme beim Download! Entfernen / Ersetzen!
	- Löschen: Ordner löschen wenn leer!*/class file_op {	private $mysqli;	private $folders;		// unterverzeichnisse von /files	function __construct( $mysqli ) {		$this->mysqli = $mysqli;
		if ( !empty( $_POST['delete'] ) ) {
			$this->del_file();
		}
	}	function body() { // wird im Hauptbereich angezeigt//		print_r( $_POST );		echo '<div class="page-header">				<h1>Filelinks <small>to direct download, use in Mailings or Emails</small></h1>			</div>';		$this->folders2array();		$this->files2array();		$this->filearray2list();

	}	function folderCreate() {
		// alle Dateien werden im Folder "files" abgelegt - check if available
		is_dir("files") || mkdir("files");
		// aktueller Folder? jahr(2) monat
		$path = "files/" . substr( date("Y"), -2 ) . date("m");
		// wenn nicht vorhanden, erstellen
		is_dir($path) || mkdir($path);
		return $path;
	}
	function folders2array() {		$folders = glob( $_SESSION['cfg_rootdir'] . '/files/*' );		foreach( $folders as $folder ) {			$this->folders[] = substr_replace( $folder, "", 0, ( strlen( $_SESSION['cfg_rootdir'] ) + 1 ) );		}	}	function files2array() {		$i = 0;		foreach( $this->folders as $folder ) {			$files = glob( $_SESSION['cfg_rootdir'] . '/' . $folder . '/*' );			// Verzeichnis abziehen			foreach( $files as $file ) {				$this->files[$i][] = substr_replace( $file, "", 0, ( strlen( $_SESSION['cfg_rootdir'] . '/' . $folder ) + 1 ) );				$this->files[$i][] = substr_replace( $file, "", 0, ( strlen( $_SESSION['cfg_rootdir'] ) + 1 ) );				$i++;			}		}	}	function filearray2list () {//		print_r( $_POST );
		echo '<form class="form-horizontal" name="eingang" action="' . $_SERVER['REQUEST_URI'] . '" method="POST" >';		echo '<div class="col-md-10 col-md-offset-1">
				<table class="table table-hover">';		foreach( $this->files as $file ) {			echo "<tr>";
			// Lösch- Button und Datum! nur wenn angemeldet!
			if ( $_SESSION['cfg_userno'] ) {
				echo '<td>
								<button class="btn btn-danger" name="delete" value="' . $file[1] . '" >
									<span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
								</button>
							</td>
							<td>
								' . date( "d.m.Y", filectime( $file[1] ) ) . '
							</td>';
			}
			echo '<td>
							<a href="' . $file[1] . '" target="_blank" >
								' . $file[0] . '
							</a>
						</td>
						<td class="text-right"><span class="badge">' . $this->human_filesize( filesize( $file[1] ), 1 ) . '</span></td>
						';			echo "</tr>";
		}		echo '</table>
				</div>';		echo '</form>';	}
	function del_file() {
		if ( file( $_POST['delete'] ) ) {
			unlink( $_POST['delete'] );
			$_SESSION['message'][] = 'Datei "' . $_POST['delete'] . '" wurde gelöscht.::0';
		}
	}	function human_filesize($bytes, $decimals = 2) {		$sz = 'BKMGTP';		$factor = floor((strlen($bytes) - 1) / 3);		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];	}
	function uploadSimple() {
//		print_r( $_POST );
//		print_r( $_FILES );
		echo '<form class="form form-horizontal" onsubmit="getProgress()" target="_self" enctype="multipart/form-data" method="post">
					<input type="hidden" name="UPLOAD_IDENTIFIER" value="<?php echo $id;?>" />
					<p>
						<input class="btn btn-default" type="file" name="file" />
					</p>
					<p>
						<input class="btn btn-primary" id="submitButton" type="submit" value="Upload File" />
					</p>
				</form>
				';

		if ( is_array( $_FILES ) ) {
			$this->fileUpload();
		}

	}
	function fileUpload() {
		$path = $this->folderCreate();

		if ( !empty( $_FILES['file']['name'] ) ) {
			if( $_FILES['file']['size'] < 55360000 AND $_FILES['file']['size'] > 0 ) {
				$target = preg_replace("/[^a-zA-Z0-9_-]/", "_", $_FILES['file']['name'] ); // Sonderzeichen werden ausgetauscht
				// Gibt es die Datei schon? Dann ein "(1)" anhängen
				if ( file_exists( $path . "/" . $target ) ) {
					echo "A File with this name does already exist.\n";
					echo "The Upload was canceled!\n";
				} else  {
					if ( $target != $_FILES['file']['tmp_name'] ) {
						echo "New Filename is " . $target . "...\n";
					}
					// Datei kopieren
	//				$_SESSION['message'][] = "Die Datei wurde kopiert.::0";
					move_uploaded_file( $_FILES['file']['tmp_name'], $path . "/" . $target );
					echo "The File has been uploaded!\n";
					echo "<h4>The Downloadlink is " . $_SESSION['cfg_base_url'] . $path . "/" . $target . "</h4>\n";
				}
			}
		}
/*
		if ( !empty( $_FILES['datei']['name'] ) ) {
			if( $_FILES['datei']['size'] < 15360000 AND $_FILES['datei']['size'] > 0 ) {
				// Datei kopieren
				move_uploaded_file($_FILES['datei']['tmp_name'], $path . "/" . urlencode( $_FILES['datei']['name'] ) );
			}
		}
*/
	}
/*	function uploadDropzone() {
		print_r( $_POST );
		print_r( $_FILES );
		echo '<div class="page-header">				<h1>Upload file...</h1>			</div>
			<div>
			<meta charset="utf-8">

<title>Dropzone simple example</title>


<!--
  DO NOT SIMPLY COPY THOSE LINES. Download the JS and CSS files from the
  latest release (https://github.com/enyo/dropzone/releases/latest), and
  host them yourself!
-->
<script src="dropzone/dist/dropzone.js"></script>
<link rel="stylesheet" href="dropzone/dist/dropzone.css">


<p>
  This is the most minimal example of Dropzone. The upload in this example
  doesn\'t work, because there is no actual server to handle the file upload.
</p>

<!-- Change /upload-target to your upload address -->
<form action="files/1512" class="dropzone">

</form>
<button type="submit" id="submit-all">Submit all files</button>

			</div>
<script>
Dropzone.options.myDropzone = {

  // Prevents Dropzone from uploading dropped files immediately
  autoProcessQueue: false,

  init: function() {
    var submitButton = document.querySelector("#submit-all")
        myDropzone = this; // closure

    submitButton.addEventListener("click", function() {
      myDropzone.processQueue(); // Tell Dropzone to process all queued files.
    });

    // You might want to show the submit button only when
    // files are dropped here:
    this.on("addedfile", function() {
      // Show submit button here and/or inform user to click it.
    });

  }
};
</script>
			';
	}
*/}?>