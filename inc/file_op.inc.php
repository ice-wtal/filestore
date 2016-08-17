<?php/*	File Operations	ToDo:	*/class file_op {	private $mysqli;	private $folders;		// unterverzeichnisse von /files		function __construct( $mysqli ) {		$this->mysqli = $mysqli;
	}		function body() { // wird im Hauptbereich angezeigt		//		print_r( $_POST );		echo '<div class="page-header">				<h1>Filelinks <small>to direct download, use in Mailings or Emails</small></h1>			</div>';		$this->folders2array();		$this->files2array();		$this->filearray2list();
		
	}	function folderCreate() {
		// alle Dateien werden im Folder "files" abgelegt - check if available
		is_dir("files") || mkdir("files");
		// aktueller Folder? jahr(2) monat
		$path = "files/" . substr( date("Y"), -2 ) . date("m");
		// wenn nicht vorhanden, erstellen
		is_dir($path) || mkdir($path);
		return $path;
	}
	function folders2array() {		$folders = glob( $_SESSION['cfg_rootdir'] . '/files/*' );		foreach( $folders as $folder ) {			$this->folders[] = substr_replace( $folder, "", 0, ( strlen( $_SESSION['cfg_rootdir'] ) + 1 ) );		}	}	function files2array() {		$i = 0;		foreach( $this->folders as $folder ) {			$files = glob( $_SESSION['cfg_rootdir'] . '/' . $folder . '/*' );			// Verzeichnis abziehen			foreach( $files as $file ) {				$this->files[$i][] = substr_replace( $file, "", 0, ( strlen( $_SESSION['cfg_rootdir'] . '/' . $folder ) + 1 ) );				$this->files[$i][] = substr_replace( $file, "", 0, ( strlen( $_SESSION['cfg_rootdir'] ) + 1 ) );				$i++;			}		}	}	function filearray2list () {		echo '<div class="list-group">';		foreach( $this->files as $file ) {			echo '<a href="' . $file[1] . '" target="_blank" class="list-group-item">' . $file[0] . '<span class="badge">' . $this->human_filesize( filesize( $file[1] ), 1 ) . '</span></a>';		}		echo '</div>';	}	function human_filesize($bytes, $decimals = 2) {		$sz = 'BKMGTP';		$factor = floor((strlen($bytes) - 1) / 3);		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];	}
	function uploadSimple() {
//		print_r( $_POST );
//		print_r( $_FILES );
/*
				<div class="form-group">
					<label class="control-label">Select File</label>
					<input type="file" class="btn btn-file" name="datei" role="file" >
				</div>
*/
/*
		echo '<form class="form-inline" action="' . $_SERVER['REQUEST_URI'] . '" method="post" name="files" enctype="multipart/form-data">
				<div style="position:relative;">
      			<a class="btn btn-primary" href=\'javascript:;\'>
					Choose File...
					<input type="file" style=\'position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;\' name="datei" size="40" onchange=\'$("#upload-file-info").html($(this).val());\'>
					</a>&nbsp;
					<span class="label label-info" id="upload-file-info"></span>
				</div>
				<p></p>
				<button type="submit" class="btn btn-default" name="uploadBtn" id="submit-all">Submit all files</button>
				</form>
				<p></p>
				<h3>Aktuell können nur relativ kleine Dateien hochgeladen werden!</h3>
				<p>Unser Hoster 1&1 beschränkt die Ausführzeit für Scripte auf 60 Sekunden. Da der Upload größerer Dateien länger dauert,
				bricht der Upload nach 60 Sekunden ab.</p>
				<p>Versuche haben ergeben, das zum Beispiel 3 MB große Dateien funktionieren (wohl auch einiges mehr), 20 MB aber nicht!</p>
				<p>Dies ist aber auch abhängig von der Internetverbindung. Mit einer vDSL Anbindung funktionieren unter Umständen auch 20 MB!</p>
				';
        <br/>
        <label>Select File:</label>
        <input type="file" name="file2" />
*/
		echo '<form onsubmit="getProgress()" target="_self" enctype="multipart/form-data" method="post">
					<input type="hidden" name="UPLOAD_IDENTIFIER" value="<?php echo $id;?>" />
					<label>Select File:</label>
					<input type="file" name="file" />
					<br/>
					<label>Upload File:</label>
					<input id="submitButton" type="submit" value="Upload File" />
				</form>				
				';
//		var_dump($_FILES);
//		print_r( $_FILES );

		if ( is_array( $_FILES ) ) {
			$this->fileUpload();
		}

	}
	function fileUpload() {
		$path = $this->folderCreate();

		if ( !empty( $_FILES['file']['name'] ) ) {
			if( $_FILES['file']['size'] < 55360000 AND $_FILES['file']['size'] > 0 ) {
				// Gibt es die Datei schon? Dann ein "(1)" anhängen
				
				// Datei kopieren
				echo $_FILES['file']['name'] . " wurde kopiert!\n";
				$_SESSION['message'][] = "Die Datei wurde kopiert.::0"; 
				move_uploaded_file($_FILES['file']['tmp_name'], $path . "/" . $_FILES['file']['name'] );
			}
		}

		if ( !empty( $_FILES['datei']['name'] ) ) {
			if( $_FILES['datei']['size'] < 15360000 AND $_FILES['datei']['size'] > 0 ) {
				// Datei kopieren
				move_uploaded_file($_FILES['datei']['tmp_name'], $path . "/" . urlencode( $_FILES['datei']['name'] ) );
			}
		}
	}
	function uploadDropzone() {
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
	}}?>