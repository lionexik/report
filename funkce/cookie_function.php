<?php

	/**
    * Soubor s funkcemi pro cookie rememberme
    */

	/**
	* Zkontroluje zda je na pc cookie, ktera kontroluje zda uzivatel zustal prihlasen
	* @param $cookie cookie
	* @param $conn spojeni s databazi
	* @return boolean true pokud vse probehlo v poradku
	*/
	function check_signed_cookie($cookie, $conn) {
		$signed = false;
		$pieces = explode('-', $cookie);

		if(isset($pieces[0]) && isset($pieces[1]) && isset($pieces[2]) && !empty($pieces[0]) && !empty($pieces[1]) && !empty($pieces[2])) {
			$id = $pieces[0];
			$token = $pieces[1];
			$mac = $pieces[2]; 

        	if (!hash_equals(hash_hmac('sha256', $id . '-' . $token, 'SECRET_KEY'), $mac)) {
        		$signed = false;
        	} else {
        		$usertoken = mysqli_query($conn, "SELECT token FROM User WHERE ID = $id");
        		if (mysqli_num_rows($usertoken) > 0) {
	        		$sql_token = mysqli_fetch_assoc($usertoken)["token"];
		    	    if (hash_equals($sql_token, $token)) {
			            $_SESSION['user'] = $id;

			            $author = mysqli_query($conn, "SELECT status FROM User WHERE ID='$id'");
						$_SESSION['status'] = (mysqli_num_rows($author) > 0) ? mysqli_fetch_assoc($author)["status"] : "";

		            	$signed = true;
        			}
        		}
    		}
  		}
      return $signed;
	}