<?php
		- Dateien in Datenbank
		- Dateinamen / Downloadnamen anpassen
		- Suche
		- Dateien löschen können
		// Verbindung zur Datenbank herstellen
		$this->mysqli = new mysqli( $_SESSION['cfg_db_host'], $_SESSION['cfg_db_user'], $_SESSION['cfg_db_pass'], $_SESSION['cfg_db_name'] );
    		printf("Connect failed: %s\n", mysqli_connect_error());
    		exit();
		}

			$this->logging = $log;
	}	
		if ( $_SESSION['cfg_userno'] ) { // extra Punkte wenn eingeloggt
			echo '<li' . ( $_SESSION['cfg_param'][0] == "upload" ? ' class="active"' : "" ) . '>
		}
			echo '<button type="submit" class="btn btn-default" name="logout" value="1"><span class="glyphicon glyphicon-log-out"></span> Logout</button>';
		} else {
			echo '<div class="form-group">
		}
		
			echo '<p>Searchkey: "' . $_GET['search'] . '"</p>';
		} else {
			echo '<div class="col-md-6 col-md-offset-3">';
	function message() {
		foreach ( (array) $_SESSION['message'] as $message ) {
			$arr = explode("::", $message );
			$level = array( "success", "info", "warning", "danger" );
			echo '<div class="alert alert-' . $level[ $arr[1] ] . '" role="alert">' . $arr[0] . '</div>';
		}
		unset( $_SESSION['message'] );
	}