<?php

  /**
  * Soubor s funkcemi pro databazi
  */

  /**
  * Funkce pro spojeni s databazi
  * @return spojeni s datbazi
  */
	function connect() {

		  $servername = "servername";
      $dbusername = "dbusername";
      $dbpassword = "dbpassword";
   		$dbname = "dbname";

    	$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);


    	if ($conn->connect_error) {
      		die("Spojeni s databazi selhalo: " . $conn->connect_error);
    	}

    	return $conn;
	}

  /**
  * Funkce pro odpojeni od databaze
  * @param $conn databaze
  */
	function disconnect($conn) {
		$conn->close(); 
	}

  