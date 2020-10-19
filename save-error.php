<?php

	/**
    * Stranka pro ulozeni chyby
    */
	session_start();

	require 'funkce/databaze.php';
	require 'funkce/cookie_function.php';

	$conn = connect();


	 /**
    * Funkce pro ulozeni chyby do databaze
    * @param $name nazev chyby
    * @param $text text chyby
	* @param $id id autora
    * @param $conn pripojeni k databazi
	*/
	function save_error($name, $text, $id, $conn) {
		if(empty($name) || empty($text)) {
			$_SESSION["error"] = 11; 
		}

		$sql = "INSERT INTO Error (name, text, author, date) VALUES ('$name', '$text', '$id', NOW())";

		if ($conn->query($sql) === TRUE) {
			$_SESSION["error"] = -11;
		} else {
			$_SESSION["error"] = 12;
		}
	}



	$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if (!empty($cookie)) {
        check_signed_cookie($cookie, $conn);
    }

  	if(!isset($_SESSION['user']) || $_SESSION['user'] == 0) {
  		$_SESSION["error"] = 2; 
    	header("Location: signup.php");
  	} else {

  		if (isset($_GET["error_name"]) && isset($_GET["error_text"])) {
  			if (strlen($_GET["error_name"]) < 1 || strlen($_GET["error_name"]) > 2000) {
  				$_SESSION["error"] = 13;
  				header("Location: https://wa.toad.cz/~zelenj12/table.php");
  				exit;
  			} else if (strlen($_GET["error_text"]) < 1 || strlen($_GET["error_text"]) > 2000) {
  				$_SESSION["error"] = 14; 
  				header("Location: https://wa.toad.cz/~zelenj12/table.php");
  				exit;
  			} else {
  				$_SESSION["data"] = $_GET;
				$name = $conn->real_escape_string($_GET["error_name"]);
				$text = $conn->real_escape_string($_GET["error_text"]);
				$id = $_SESSION["user"];

				save_error($name, $text, $id, $conn);
			}
		}

		header("Location: table.php");

	}
	disconnect($conn);

?>