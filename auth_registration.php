<?php

	/**
    * Stranka pro ulozeni registrovaneho uzivatele do datbaze
    */

	session_start();

	require 'funkce/databaze.php';

	$conn = connect();

	/**
	* Funkce ktera ulozi uzivatele do databaze
	* @param $email
	* @param $password
	* @param $conn pripojeni k databazi
	*/
	function insert_to_db($email, $password, $conn) {
		$enc_psw = password_hash($password, PASSWORD_DEFAULT);
		$sql = "INSERT INTO User (email, password) VALUES ('$email', '$enc_psw')";

		if ($conn->query($sql) === TRUE) {
    		$_SESSION["error"] = -1;
    		header("Location: signup.php");
		} else {
			$_SESSION["error"] = 3;
			header("Location: registration.php");

		}
	}

	$email = "";
	$password = "";
	$password_very = "";


	if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["password_again"])) {

		$_SESSION["data"] = $_POST;
		$email = $conn->real_escape_string($_POST["email"]);
		$password = $_POST["password"];
		$password_very = $_POST["password_again"];

		if (empty($email) || empty($password) || empty($password_very)) { 
			$_SESSION["error"] = 5;
			header("Location: registration.php");
			
		} else if ($password_very != $password) { 
			$_SESSION["error"] = 4;
			header("Location: registration.php");
			
		} else if (strlen($password) < 6 || strlen($password) > 60) {
			$_SESSION["error"] = 6;
			header("Location: registration.php");

		} else if (strlen($email) > 255) {
			$_SESSION["error"] = 7;
			header("Location: registration.php");
		}else {

			insert_to_db($email, $password, $conn);

		}

	} else {
		$_SESSION["error"] = 5;
		header("Location: registration.php");
	}


	disconnect($conn);
	exit;
?>