<?php
	function connect() {
		session_start();

		$servername = "localhost";
    	$dbusername = "zelenj12";
    	$dbpassword = "webove aplikace";
   		$dbname = "zelenj12";

    	$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);


    	if ($conn->connect_error) {
      		die("Spojeni s databazi selhalo: " . $conn->connect_error);
    	}

    	return $conn;
	}

	function disconnect($conn) {
		$conn->close(); 
	}