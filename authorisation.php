<?php

	/**
    * Stranka s prihlasenim uzivatele
    */

	session_start();

	$email = "";
	$password = "";
	
	require 'funkce/databaze.php';

	$conn = connect();


	/**
	* Funkce pro skontrolu prihlaseni uzivatele
	* @param $email
	* @param $password
	* @param $connection pripojeni k databazi
	* @return integer pokud uzivatel byl nalezen vraci jeho id, jinak 0
	*/
	function check_user($email, $password, $connection) {
		$users =  $connection->query("SELECT * FROM User WHERE email='$email'");
		if($users->num_rows > 0) {
			$row = $users->fetch_assoc();
			$db_hash = $row["password"];
			$id_us = $row["ID"];

			if (password_verify($password, $db_hash)) { 
				$res = $id_us;
			} else {
				$res = 0;
			}
		} else {
			$res = 0;	
		}

		return $res;
	}

	/**
	* Funkce ktera nastvai cookie, a ulozi token do databaze aby uzivatel zustal prihlasen i po vypnuti prohlizece az po dobu 1 dne
	* @param $id id uzivatele
	* @param $conn pripojeni k databazi
	*/
	function keep_signed_cookie($conn, $id) {
		$token = md5(random_bytes(32)); 
		$conn->query("UPDATE User SET token = '$token' WHERE ID='$id'");

    	$cookie = $id . '-' . $token;
    	$mac = hash_hmac('sha256', $cookie, 'SECRET_KEY');
    	$cookie .= '-' . $mac;
    	setcookie('rememberme', $cookie, time()+86400);
	}



	if (isset($_POST["email"]) && isset($_POST["password"])) {

		$_SESSION["data"] = $_POST;
		$email = $conn->real_escape_string($_POST["email"]);
		$password = $_POST["password"];

		if(empty($email) || empty($password)) {

			$_SESSION["error"] = 5;
			header("Location: https://wa.toad.cz/~zelenj12/signup.php");
		} else {
			
			$id = check_user($email, $password, $conn);
			if($id) {

				if (isset($_POST["keep-signed"])) {
					keep_signed_cookie($conn, $id);
				}

				$_SESSION['user'] = $id;
				$author = $conn->query("SELECT status FROM User WHERE ID='$id'");
				
				$_SESSION['status'] = $author->fetch_assoc()["status"];
				header("Location: table.php");
			} else {

				$_SESSION["error"] = 1;
				header("Location: signup.php");
			}
		}
	} else {
		$_SESSION["error"] = 1;
		header("Location: signup.php");
	}

	
	disconnect($conn);
	exit;

?>