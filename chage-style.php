<?php

	/**
    * Stranka pro zmenu skinu
    */
	
	if (isset($_GET["color"])) {
		if ($_GET["color"] < 0) {
			setcookie("skin", -1, time()+60*60*24*30);
		} else {
			setcookie("skin", 1, time()+60*60*24*30);
		}
	}

	if (isset($_SERVER['HTTP_REFERER'])) {
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit;	
	} else {
		header('Location: signup.php');
		exit;
	}
	