<?php
/*
	ToDo:
*/
class install {

	private $mysqli;
	
	function __construct( $mysqli ) {
		$this->uniSqli = $mysqli;
	}
	function body() {
		if ( $_SESSION['cfg_param'][1] == "create" ) {
			// Admin Passwort abfragen
			echo '<p>The Database will get setup. First login as User and Password "administrator". Please change it directly!</p>';
//			$this->create_tables();
		} else {
			// Button zum installieren
			echo '<p><a href="index.php?param=install;create" class="btn btn-primary btn-lg active" role="button">Erstelle Tabellen</a></p>';
			echo phpinfo();
		}
	}
	function create_tables() {
		// Tabelle für Dateien
		$query = 'CREATE TABLE IF NOT EXISTS files (
								`number` int(11) NOT NULL AUTO_INCREMENT,
								`filename` varchar(255) NOT NULL,
								`discription` varchar(255) NOT NULL,
								`user` int(11) NOT NULL DEFAULT "1",
								`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
								`delete_on` date NOT NULL,
								PRIMARY KEY (`number`)
							);';
		// Tabelle für User
		$query = 'CREATE TABLE IF NOT EXISTS user (
								`number` int(11) NOT NULL AUTO_INCREMENT,
								`username` varchar(255) NOT NULL,
								`email` varchar(255) NOT NULL,
								`password` varchar(255) NOT NULL,
								PRIMARY KEY (`number`)
							);';
		// Administrator erstellen
		$query = 'INSERT INTO `user` (`number`, `username`, `password`) VALUES
								(NULL, "administrator", "' . password_hash("administrator", PASSWORD_DEFAULT) . '");';
	}
}
?>