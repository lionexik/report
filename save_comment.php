<?php

  /**
  * Stranka pro ulozeni komentare
  */

  session_start();

  require 'funkce/databaze.php';
  require 'funkce/cookie_function.php';

  $conn = connect();

  /**
  * Funkce pro ulozeni komentare do databaze
  * @param $new true pokud se chce vytvorit novy zaznam v databazi
  * @param $error_id id chyby
  * @param $author_id id autora
  * @param $text text komentare
  * @param $connection pripojeni k databazi
  * @return boolean true pokud se ulozilo spravne
  */
	function save_comment($connection, $text, $author_id, $error_id, $new) {
    	if($new) {
      		$sql = $connection->query("INSERT INTO Comment (text, author, error) VALUES ('$text', '$author_id', '$error_id')");
          //  ready for changing comment
      /*		$sql_id = mysqli_query($connection, "SELECT ID FROM Comment WHERE error = '$error_id'");
      		$id_com = mysqli_fetch_assoc($sql_id)["ID"];
      		$sql_er = mysqli_query($connection, "UPDATE Error SET comment = '$id_com' WHERE ID = '$error_id'");*/
   		} else {
      		$sql = $connection->query("UPDATE Comment SET text = '$text', author = '$author_id' WHERE error = '$error_id'");
    	}
    	return $sql;
  	}

  $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if (!empty($cookie)) {
        check_signed_cookie($cookie, $conn);
    }

  if(!isset($_SESSION['user']) || $_SESSION['user'] == 0) {
    $_SESSION["error"] = 2;
    header("Location: signup.php");
  } else {

  	if (isset($_GET["comment-text"]) && isset($_GET["error_id_com"]) && isset($_GET["new-comment"])) {
//    $_SESSION["data_comment"] = $_GET;
      if (strlen($_GET["comment-text"]) < 0 || strlen($_GET["comment-text"]) > 255) {
        $_SESSION["error"] = 21;
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
      } else {
	 	    save_comment($conn, $conn->real_escape_string($_GET["comment-text"]), $_SESSION["user"], $_GET["error_id_com"], $_GET["new-comment"]);
	    }
    }
    $_SESSION["error"] = -21;
	  header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
  }
  disconnect();
?>