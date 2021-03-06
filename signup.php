<?php

	/**
    * Prihlasovaci stranka
    */

	session_start();
	require 'funkce/databaze.php';
	require 'funkce/cookie_function.php';
	require 'funkce/alerts_text.php';


	$conn = connect();

	$cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
    if (!empty($cookie)) {
        check_signed_cookie($cookie, $conn);
    }

    if(isset($_SESSION["user"])) {
 		header("Location: table.php");
	}

	$data = "";
	$checkbox = "";
	if(isset($_SESSION["data"])) {
		$data = (isset($_SESSION["data"]["email"])) ? $_SESSION["data"]["email"] : "";
		$checkbox = (isset($_SESSION["data"]["keep-signed"])) ? $_SESSION["data"]["keep-signed"] : "";
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
    <link rel="stylesheet" type="text/css" href="login-style.css">
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

	<title>Přihlášení</title>
</head>
<body>
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
	<div class="container">
	<header>Přihlášení uživatele</header>
	<form class="login" action="authorisation.php" method="post">
		<div class="form-group">
			<label for="email"> E-mailová adresa </label>
			<input type="email" required name="email" id="email" class="form-control" placeholder="Emailová adresa" value='<?php echo $data?>'>
		</div>
		<div class="form-group">
			<label for="password"> Heslo </label>
			<input type="password" required name="password" id="password" class="form-control" placeholder="Heslo">
		</div>
		<div class="form-group form-check">
    		<label class="form-check-label" for="keep-signed">
    			<input type="checkbox" class="form-check-input" name="keep-signed" id="keep-signed" value='<?php echo $checkbox?>'> Pamatuj si me
    		</label>
    		<a href='registration.php' class="badge badge-secondary">Registrace</a>
  		</div>
		<input type="submit" value="Přihlásit" name="sub" class="btn btn-primary">
	</form>

	</div>

	  <footer id="foot" class="foote-down">
    	<a href="chage-style.php?color=-1" class="badge badge-light">Šedá</a>
    	<a href="chage-style.php?color=1" class="badge badge-primary">Světle modrá</a>
	</footer> 

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>

	<script src="script-login.js"></script>

</body>

</html>

<?php unset($_SESSION["data"]); ?>
