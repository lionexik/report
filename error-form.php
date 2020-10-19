<?php

	/**
    * Stranka s formularem pro nahlaseni chyby
    */

	session_start();

	require 'funkce/databaze.php';
	require 'funkce/cookie_function.php';
	require 'funkce/alerts_text.php';


	$conn = connect();

	$error_name = "";
	$error_text = "";
	

	$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if (!empty($cookie)) {
        check_signed_cookie($cookie, $conn);
    }

  	if(!isset($_SESSION['user']) || $_SESSION['user'] == 0) {
  		$_SESSION["error"] = 2;
    	header("Location: https://wa.toad.cz/~zelenj12/signup.php");
  	}

  	$name_ses = "";
  	$text_ses = "";
	if(isset($_SESSION["data"])) {
		$name_ses = (isset($_SESSION["data"]["error_name"])) ? $_SESSION["data"]["error_name"] : "";
		$text_ses = (isset($_SESSION["data"]["error_text"])) ? $_SESSION["data"]["error_text"] : "";
	}

	$code = 0;
	$alerttext = "";
	if (isset($_SESSION["error"])) {
		$code = $_SESSION["error"];
		$alerttext = alert($code);
	}
	

	disconnect($conn);

?>

<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="error-form-style.css">

    <?php
    	if(isset($_COOKIE["skin"])) {
    		switch ($_COOKIE["skin"]) {
    		 	case '-1':
    		 		echo '<link rel="stylesheet" type="text/css" href="style-dark.css">';
    		 		break;
    		 	
    		 	default:
    		 		echo '<link rel="stylesheet" type="text/css" href="style-light.css">';
    		 		break;
    		 } 
    	} else {
    		echo '<link rel="stylesheet" type="text/css" href="style-light.css">';
    	}
    ?>

	<title>Nahlášení chyby</title>
</head>
<body>

	<div class="nav-container">
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    		<span class="navbar-toggler-icon"></span>
  		</button>
    	<div class="collapse navbar-collapse" id="navbarText">
    		<ul class="navbar-nav mr-auto">
       			<li class="nav-item active">
          			<a class="navbar-brand" href="https://wa.toad.cz/~zelenj12/error-form.php">Nahlášení chyby</a>
    		    </li>
        		<li class="nav-item">
       				<a class="nav-link" href="https://wa.toad.cz/~zelenj12/table.php">Seznam chyb</a>        	
        		</li>
        		<li class="nav-item">
       				<a class="nav-link" href="https://wa.toad.cz/~zelenj12/error-form.php">Nahlášení chyby</a>        	
        		</li>
      		</ul>
    		<a class="navbar-text" href="https://wa.toad.cz/~zelenj12/logout.php">Odhlášení</a>
    	</div>
  	</nav>
  	</div>

  	<?php if ($code > 0) { ?>
	 	<div class='alert alert-danger' role='alert'>
			<?php echo $alerttext; 
			$_SESSION["error"] = 0;
			?> 
		</div>
	<?php } else if ($code < 0) { ?>
		<div class='alert alert-success' role='alert'>
			<?php echo $alerttext; 
			$_SESSION["error"] = 0;
			?>
		</div>
	<?php }
	?>

  	<div class="container card-body">
  		<header>Nahlášení chyby</header>
		<form action=save-error.php method="get">
			<div class="form-group">
				<label for="error_name"> Název chyby </label>
				<input type="text" required name="error_name" id="error_name" class="form-control" placeholder="Název chyby" required>
			</div>
			<div class="form-group">
				<label for="error_text"> Popis chyby </label>
				<textarea required name="error_text" id="error_text" class="form-control" placeholder="Popis chyby..." required></textarea>
			</div>
			<input type="submit" value="Odeslat" name="sub" class="btn btn-primary">
		</form>
	</div>

	<footer id="foot" class="foote-down">
    	<a href="chage-style.php?color=-1" class="badge badge-light">Šedá</a>
    	<a href="chage-style.php?color=1" class="badge badge-primary">Světle modrá</a>
	</footer> 

		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

	    <script src="script-error.js"></script>>


</body>
</html>

<?php unset($_SESSION["data"]); ?>