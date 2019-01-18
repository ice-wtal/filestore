<?php
/*	File Operations
	ToDo:
	- "#" Zeichen im Dateinamen macht Probleme beim Download! Entfernen / Ersetzen!
	- Löschen: Ordner löschen wenn leer!
*/
class file_op {
	private $mysqli;
	private $folders;		// unterverzeichnisse von /files
	var $files;

	function __construct( $mysqli ) {
		$this->mysqli = $mysqli;
		if ( !empty( $_POST['delete'] ) ) {
			$this->del_file();
		}
	}
	function body() { // wird im Hauptbereich angezeigt

//		print_r( $_POST );
		echo '<div class="page-header">
				<h1>Filelinks <small>to direct download, use in Mailings or Emails</small></h1>
			</div>';
		$this->folders2array();
		$this->files2array();
		$this->filearray2list();

	}
	function folderCreate() {
		// alle Dateien werden im Folder "files" abgelegt - check if available
		is_dir("files") || mkdir("files");
		// aktueller Folder? jahr(2) monat
		$path = "files/" . substr( date("Y"), -2 ) . date("m");
		// wenn nicht vorhanden, erstellen
		is_dir($path) || mkdir($path);
		return $path;
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
	function filearray2list () {
//		print_r( $_POST );
		if ( $_SESSION['cfg_userno'] ) {
			echo '<p class="text-right">
							<button type="button" class="btn btn-primary" onClick="emptyNeueDatei();" data-toggle="modal" data-target="#uploadModal">
						  	Datei hochladen
							</button>
						</p>';
		}
		echo '<div class="row">
						<div class="col-md-12">
							<div id="dateiTabelle"></div>
						</div>
					</div>
					<script>
						function updateTabelle() {
							$.post(\'inc/file_op.ajax.php\', { aktion:\'showList\' },
							function(data) {
//								alert( "Hallo Welt!" );
								$("#dateiTabelle").html( data );
							})
						}
						function emptyNeueDatei() {
							$("#neueDatei").val( "" );
						}
						updateTabelle();
					</script>
					';
		$this->uploadModal();
	}
	function del_file() {
		if ( file( $_POST['delete'] ) ) {
			unlink( $_POST['delete'] );
			$_SESSION['message'][] = 'Datei "' . $_POST['delete'] . '" wurde gelöscht.::0';
		}
	}
	function human_filesize($bytes, $decimals = 2) {
		$sz = 'BKMGTP';
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
	}
	function uploadModal() {
		echo '<!-- Modal -->
					<form class="form form-horizontal" action="" enctype="multipart/form-data" method="post">
					<div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="meinModalLabel">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
									<h4 class="modal-title" id="meinModalLabel">Datei hochladen</h4>
					        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
										<span aria-hidden="true">&times;</span>
									</button>
					      </div>
					      <div class="modal-body">
									<p>
										<input type="hidden" name="overwriteFile" id="neueDatei" />
										<input class="btn btn-default" type="file" name="file" id="fileA" onchange="fileChange();" />
									</p>
									<div class="well">
										<div id="fileName"></div>
									  <div id="fileSize"></div>
									  <div id="fileType"></div>
									</div>
									<div class="progress">
										<div class="progress-bar" id="progressNew" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 2%;">
										0%
										</div>
									</div>
					      </div>
					      <div class="modal-footer">
<!--					        <button type="button" class="btn btn-default" data-dismiss="modal">Schließen</button> -->
<!--					        <button type="button" class="btn btn-primary">Änderungen speichern</button> -->

									<button class="btn btn-primary" type="submit" onclick="return checkDouble();" />Upload File</button>
									<input class="btn btn-outline-warning" name="abort" value="Upload abbrechen" type="button" onclick="uploadAbort();" />
									<button class="btn btn-secondary" class="close" data-dismiss="modal" />Schließen</button>
					      </div>
					    </div>
					  </div>
					</div>
					</form>
					<script>
						function fileChange() {
						    //FileList Objekt aus dem Input Element mit der ID "fileA"
						    var fileList = document.getElementById("fileA").files;

						    //File Objekt (erstes Element der FileList)
						    var file = fileList[0];

						    //File Objekt nicht vorhanden = keine Datei ausgewählt oder vom Browser nicht unterstützt
						    if(!file)
						        return;

						    document.getElementById("fileName").innerHTML = \'Dateiname: \' + file.name;
						    document.getElementById("fileSize").innerHTML = \'Dateigröße: \' + file.size + \' B\';
						    document.getElementById("fileType").innerHTML = \'Dateitype: \' + file.type;
						}
						var client = null;

						function uploadFile()	{

						    //Wieder unser File Objekt
						    var file = document.getElementById("fileA").files[0];
						    //FormData Objekt erzeugen
						    var formData = new FormData();
						    //XMLHttpRequest Objekt erzeugen
						    client = new XMLHttpRequest();

						    if(!file)
						        return;

						    //Fügt dem formData Objekt unser File Objekt hinzu
						    formData.append("datei", file);

						    client.onerror = function(e) {
						        alert("onError");
						    };

						    client.onload = function(e) {
						    };

						    client.upload.onprogress = function(e) {
						        var p = Math.round(100 / e.total * e.loaded);
										$(\'#progressNew\').css("width", p + "%"	);
										$(\'#progressNew\').html( p + "%" );
						    };

						    client.onabort = function(e) {
//						        alert("Upload abgebrochen");
						    };

						    client.open("POST", "_self");
						    client.send(formData);
						}
						function uploadAbort() {
					    if(client instanceof XMLHttpRequest)
					        //Bricht die aktuelle Übertragung ab
					        client.abort();
						}
						function checkDouble() {
							// Dateiendung der Dateien feststellen
  						var extension = $("#neueDatei").val().split(\'.\');
  						extNeu = extension[extension.length - 1].toUpperCase();

							var extension = document.getElementById("fileA").files[0].name.split(\'.\');
  						extAlt = extension[extension.length - 1].toUpperCase();

							if ( extNeu != "" && extNeu != extAlt ) {
								alert( "Die Dateiendung stimmt nicht überein. Es wird abgebrochen!");
								return false;
							}

							if ( $("#neueDatei").val() == "" ) {
								fileB = document.getElementById("fileA").files[0].name;
								$.post(\'inc/file_op.ajax.php\', { aktion:\'double\', datei:fileB },
								function(data) {
									if ( data == "0" ) {
										uploadFile();
									} else {
										alert( "A File with this name already exists!" );
									}
								})
								return data;
							}
						}
					</script>
					';
					if ( is_array( $_FILES ) ) {
						$this->fileUpload();
					}
	}
/*	function uploadSimple() {
//		print_r( $_POST );
//		print_r( $_FILES );
		echo '<form class="form form-horizontal" action="" enctype="multipart/form-data" method="post">
						<p>
							<input class="btn btn-default" type="file" name="file" id="fileA" onchange="fileChange();" />
						</p>
						<p>
<!--							<input class="btn btn-primary" onclick="uploadFile();" id="submitButton" type="submit" value="Upload File" /> -->
							<button class="btn btn-primary" type="submit" onclick="return checkDouble();" />Upload File</button>
							<input class="btn btn-alert" name="abort" value="Abbrechen" type="button" onclick="uploadAbort();" disabled />
						</p>
					</form>
					<div class="well">
						<div id="fileName"></div>
					  <div id="fileSize"></div>
					  <div id="fileType"></div>
					</div>
					<div class="progress">
						<div class="progress-bar" id="progressNew" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: 2%;">
						0%
						</div>
					</div>
					<script>
						function fileChange()
						{
						    //FileList Objekt aus dem Input Element mit der ID "fileA"
						    var fileList = document.getElementById("fileA").files;

						    //File Objekt (erstes Element der FileList)
						    var file = fileList[0];

						    //File Objekt nicht vorhanden = keine Datei ausgewählt oder vom Browser nicht unterstützt
						    if(!file)
						        return;

						    document.getElementById("fileName").innerHTML = \'Dateiname: \' + file.name;
						    document.getElementById("fileSize").innerHTML = \'Dateigröße: \' + file.size + \' B\';
						    document.getElementById("fileType").innerHTML = \'Dateitype: \' + file.type;
						}
						var client = null;

						function uploadFile()	{

						    //Wieder unser File Objekt
						    var file = document.getElementById("fileA").files[0];
						    //FormData Objekt erzeugen
						    var formData = new FormData();
						    //XMLHttpRequest Objekt erzeugen
						    client = new XMLHttpRequest();

						    if(!file)
						        return;

						    //Fügt dem formData Objekt unser File Objekt hinzu
						    formData.append("datei", file);

						    client.onerror = function(e) {
						        alert("onError");
						    };

						    client.onload = function(e) {
						    };

						    client.upload.onprogress = function(e) {
						        var p = Math.round(100 / e.total * e.loaded);
										$(\'#progressNew\').css("width", p + "%"	);
										$(\'#progressNew\').html( p + "%" );
						    };

						    client.onabort = function(e) {
//						        alert("Upload abgebrochen");
						    };

						    client.open("POST", "_self");
						    client.send(formData);
						}
						function uploadAbort() {
					    if(client instanceof XMLHttpRequest)
					        //Bricht die aktuelle Übertragung ab
					        client.abort();
						}
						function checkDouble() {
							fileB = document.getElementById("fileA").files[0].name;
							$.post(\'inc/file_op.ajax.php\', { aktion:\'double\', datei:fileB },
							function(data) {
								if ( data == "0" ) {
									uploadFile();
								} else {
									alert( "A File with this name already exists!" );
								}
							})
							return data;
						}
					</script>
					';
		if ( is_array( $_FILES ) ) {
			$this->fileUpload();
		}
	}
*/
	function renameFile( $file ) { // Sonderzeichen werden ausgetauscht
		return preg_replace("/[^a-zA-Z0-9_.-]/", "_", $file );
	}
	function fileUpload() {
		$path = $this->folderCreate();
//		echo $path;

		if ( !empty( $_FILES['file']['name'] ) ) {
			if( $_FILES['file']['size'] < 55360000 AND $_FILES['file']['size'] > 0 ) {
				if ( !empty( $_POST['overwriteFile'] ) ) { // wenn die Datei überschrieben werden soll...
					// Datei löschen
					unlink( $_POST['overwriteFile'] );
					$target = $_POST['overwriteFile'];
				} else {
					$target = $path . "/" . $this->renameFile( $_FILES['file']['name'] );
				}
				// Gibt es die Datei schon? Dann ein "(1)" anhängen
//				if ( file_exists( $path . "/" . $target ) ) {
//					echo "<p>A File with this name does already exist.</p>";
//					echo "<h4>The Upload was canceled!</h4>";
//				} else  {
					// Datei kopieren
	//				$_SESSION['message'][] = "Die Datei wurde kopiert.::0";
					move_uploaded_file( $_FILES['file']['tmp_name'], $target );
					echo '<div class="alert alert-success" role="alert">
									<h3>Your File has been uploaded!</h3>
									<p>';
					if ( $target != $_FILES['file']['tmp_name'] ) {
						echo 'The Filename has changed to "' . $target . '".<br><br>';
					}
					echo 'The Downloadlink is:</p>
					 				<input class="form-control" onfocus="this.select();" value="' . $_SESSION['cfg_base_url'] . $target . '">
								</div>';
//				}
			}
		}
	}
/*	function uploadDropzone() {
		print_r( $_POST );
		print_r( $_FILES );
		echo '<div class="page-header">
				<h1>Upload file...</h1>
			</div>
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
*/
}
?>
