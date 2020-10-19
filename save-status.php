<?php

  /**
  * Stranka pro ulozeni statusu
  */

  session_start();

  require 'funkce/databaze.php';
  require 'funkce/cookie_function.php';

  $conn = connect();

  /**
    * Funkce pro zmenu stavu chyby
    * @param $status stav chyby
    * @param $id_error id chyby
    * @param $connection pripojeni k databazi
  */
	function save_status($connection, $status, $id_error) {
   		$sql = $connection->query("UPDATE Error SET process = '$status' WHERE ID = '$id_error'");
    	return $sql;
  	}

  

  $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if (!empty($cookie)) {
        check_signed_cookie($cookie, $conn);
    }

  if(!isset($_SESSION['user']) || $_SESSION['user'] == 0 || $_SESSION['status'] == 0) {
    $_SESSION["error"] = 2;
    header("Location: signup.php");
  } else {

	  if (isset($_GET["status"]) && isset($_GET["error"])) {
//    $_SESSION["status-data"] = $_GET;
		  save_status($conn, $_GET["status"], $_GET["error"]);
	  }
    $_SESSION["error"] = -31;
  	header("Location: table.php");
  }
  disconnect($conn);
?>