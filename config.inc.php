<?php

/*
	$_SESSION['cfg_param']		// Array des übergebenen Parameter param
	$_SESSION['cfg_lastRefresh']	// Zeit des letzten Seitenaufrufes
	$_SESSION['message'][]			// Array mit Nachrichten zum anzeigen ( nachricht::level )
	$_SESSION['cfg_userno']			// User Nr., wenn > 0 ist User angemeldet

*/
	$_SESSION['cfg_adminPass']	= "xxxx";	// Password to access before Multiuser config

	$_SESSION['cfg_base_url']		= "https://www.filestore.com/";
	$_SESSION['cfg_rootdir']		= '/filestore'; // Verzeichnis der index.php (Startdatei)

	$_SESSION['cfg_db_host'] = "localhost";
	$_SESSION['cfg_db_name'] = "filestore";
	$_SESSION['cfg_db_user'] = "filestore";
	$_SESSION['cfg_db_pass'] = "filestore";

	$_SESSION['cfg_autologout'] = 10;		// Anzahl der Minuten bis automatischem Logout

?>
