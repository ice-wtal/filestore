<?php
	}	
		
	}
		// aktueller Folder? jahr(2) monat
		$path = "files/" . substr( date("Y"), -2 ) . date("m");
		// wenn nicht vorhanden, erstellen
		is_dir($path) || mkdir($path);
		return $path;
	}
	function folders2array() {
	function uploadSimple() {

			$this->fileUpload();
		}
//		print_r( $_POST );
//		print_r( $_FILES );
/*
				<div class="form-group">
					<label class="control-label">Select File</label>
					<input type="file" class="btn btn-file" name="datei" role="file" >
				</div>
*/

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
				<p></p>';
	}
	function fileUpload() {
		$path = $this->folderCreate();
		if ( !empty( $_FILES['datei']['name'] ) ) {
//			$fileSub = substr (strrchr ($_FILES['datei']['name'], "."), 0);
			if( $_FILES['datei']['size'] < 15360000 AND $_FILES['datei']['size'] > 0 ) {
				// Datei kopieren
				move_uploaded_file($_FILES['datei']['tmp_name'], $path . "/" . urlencode( $_FILES['datei']['name'] ) );
			}
		}
	}
	function uploadDropzone() {
		print_r( $_POST );
		print_r( $_FILES );
		echo '<div class="page-header">
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
