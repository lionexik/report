<?php

	/**
    * Stranka pro odhlaseni uzivatele
    */

	session_start();

	if (isset($_SESSION['user'])) {
		unset($_SESSION['user']);
		unset($_SESSION['status']);
 	}

 	if (isset($_COOKIE['rememberme'])) {
 		setcookie("rememberme", "", 100);
 	}

 	$_SESSION["error"] = -2;

 	header("Location: signup.php");