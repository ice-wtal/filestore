<?php
/*
	ToDo:

	!!! LOGIN !!!	Mit real_escape_string() absichern. Passwort mit passwort_hash() erstellen und in 255 Zeichen langes Feld speichern!*/
class login {

	private $mysqli;
	
	function __construct( $mysqli ) {
		$this->mysqli = $mysqli;
//		echo time() . "<br>" . ( $_SESSION['cfg_autologout'] * 60 ) . "<br>" . $_SESSION['cfg_lastRefresh'] . "<br>" . ( time() - $_SESSION['cfg_lastRefresh'] );
		if ( $_SESSION['cfg_userno'] ) { // wenn eingeloggt
			if ( $_POST["logout"] ) {
				$_SESSION['message'][] = "You are logged out.::0";
				unset ( $_SESSION['cfg_userno'] );
			} elseif ( time() - $_SESSION['cfg_lastRefresh'] > ( $_SESSION['cfg_autologout'] * 60 ) ) {
				$_SESSION['message'][] = "You haven't been active for some time (more than " . $_SESSION['cfg_autologout'] . " Minutes) and got logged out.::2";
				unset ( $_SESSION['cfg_userno'] );
			} else {
				$_SESSION['cfg_lastRefresh'] = time();
			}
		} elseif ( !empty( $_POST["username"] ) ) {
			$this->verifySimple();
		}
	}
	function verifySimple() {
		if ( $_POST["username"] == "administrator" AND $_POST["password"] == "schweigen" ) {
			$_SESSION['cfg_userno'] = 1;
			$_SESSION['message'][] = "You are logged in.::0";
			$_SESSION['cfg_lastRefresh'] = time();
		} else {
			$_SESSION['message'][] = "Wrong Username and / or Password.::3";
		}
	}
}
?>