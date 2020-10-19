<?php

  /**
  * Stranka pro smazani chyby
  */

	session_start();

	require 'funkce/databaze.php';
  require 'funkce/cookie_function.php';

  $conn = connect();

	$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if (!empty($cookie)) {
        check_signed_cookie($cookie, $conn);
    }

	if(!isset($_SESSION['user']) || $_SESSION['user'] == 0 || $_SESSION['status'] == 0) {
      $_SESSION["error"] = 2;
    	header("Location: signup.php");
  	}

  	if (isset($_GET['id_error'])) {
        $id = $_GET['id_error'];
    
  		$sql =  $conn->query("DELETE FROM Error WHERE ID='$id'");
  	}

  	header("Location: table.php");


	disconnect($conn);
  exit;
?>