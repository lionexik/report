<?php

  /**
  * Stranka s vypisem vsech chyb
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

  if(!isset($_SESSION['user']) || $_SESSION['user'] == 0) {
    $_SESSION["error"] = 2;
    header("Location: signup.php");
  }

  $code = 0;
  $alerttext = "";
  if (isset($_SESSION["error"])) {
    $code = $_SESSION["error"];
    $alerttext = alert($code);
  }
  


?>


<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="table-style.css">

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


	<title>Přehled nahlášených chyb</title>
</head>
<body>

  <?php
   
$sql_category = "";
$category = -1;

if(isset($_GET['category'])){
    if($_GET['category'] >= 0){
        $category = $_GET['category'];
        $sql_category = "WHERE process = $category";
    }

}


    $max_page = 10;

    if (isset($_GET['page'])) {
        $page = $_GET['page'];
    } else {
        $page = 1;
    }

    $off = ($page - 1) * $max_page;

    $tot_sql = $conn->query("SELECT COUNT(*) FROM Error $sql_category");
    $c_rows = $tot_sql->fetch_array()[0];
    $total_pages = ceil($c_rows / $max_page);

    $errors =  $conn->query("SELECT * FROM Error $sql_category LIMIT $off, $max_page");

  ?>


	<nav class="navbar navbar-expand-lg navbar-dark bg-primary" id="nav-id">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
    <div class="collapse navbar-collapse" id="navbarText">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item active" id="home">
          <a class="navbar-brand" href="table.php" data-target="#home">Seznam chyb</a>
        </li>
        <li class="nav-item" id="home-small">
          <a class="nav-link" href="table.php" data-target="#smallhome">Seznam chyb</a>
        </li>
        <li class="nav-item" id="next-small">
          <a class="nav-link" href="error-form.php" data-target="#next">Nahlášení chyby</a>
        </li>
      </ul>
    <a class="navbar-text" href="logout.php">Odhlášení</a>
    </div>
  </nav>

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
	
  <header>Přehled nahlášených chyb</header>
  <form method="get" class="">
    <label class="filtr-title" for="inputGroupSelectFiltr"> Filtrace </label>
    <div class="input-group">
    <select name="category" class="custom-select" id="inputGroupSelectFiltr" aria-label="Filtrace podle stavu">
      <option value="-1">Vše</option>
      <option value="1">Nyní řešíme</option>
      <option value="2">Vyřešeno</option>
      <option value="0">Čeká</option>
    </select>
    <div class="input-group-append">
    <button type="submit" class="btn btn-outline-secondary">Filtruj</button>
  </div>
  </div>
  </form>

	<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Název chyby</th>
      <th scope="col">Popis</th>
      <th scope="col">Autor</th>
      <th scope="col">Datum přidání</th>
      <th scope="col">Postup</th>
      <th scope="col">Komentář</th>
      <th scope="col">Detail</th>
      <?php if(isset($_SESSION['status']) && $_SESSION['status'] == 1) { echo '<th scope="col">Smazat</th>'; } ?>
    </tr>
  </thead>
  <tbody>

  	<?php 
      $max_page_cons = $max_page;
      if ($max_page > $c_rows) {$max_page = $c_rows;}
      if ($max_page > $errors->num_rows) {$max_page = $errors->num_rows;}
      
  		for ($i = 1; $i <= $max_page; $i++) {
        $row = $errors->fetch_assoc();
        $row_name = htmlspecialchars($row["name"], ENT_QUOTES);
        $row_text = htmlspecialchars($row["text"], ENT_QUOTES);
        $row_author = htmlspecialchars($row["author"], ENT_QUOTES);
        $row_date = htmlspecialchars($row["date"], ENT_QUOTES);
        $row_process = htmlspecialchars($row["process"], ENT_QUOTES);
        $row_id = htmlspecialchars($row["ID"], ENT_QUOTES);
 //       $row_comment = htmlspecialchars($row["comment"], ENT_QUOTES);
  	?>
    <tr>
      <th scope="row"><?php echo $i+($max_page_cons*($page-1)); ?></th>
      <td> <?php echo $row_name;?> </td>
      <td> <?php $part = (strlen($row_text) > 30) ? substr($row_text, 0, 30) . "...": $row_text;
                  echo $part;
                  ?> </td>
      <td> <?php $auth_id = $row_author;
              $author =  $conn->query("SELECT email FROM User WHERE ID = '$auth_id'"); 
              if($author->num_rows > 0) {
                echo htmlspecialchars($author->fetch_assoc()["email"], ENT_QUOTES);
              } else {
                echo "autor neurčen";
              }
      ?> </td>
      <td><?php echo $row_date; ?></td>
      <td><?php 
              if($_SESSION["status"] == 0) {
                switch ($row_process) {
                  case 0:
                    echo "Čeká na vyřízení";
                    break;
                  case 1:
                    echo "Nyní řešíme";
                    break;
                  default:
                    echo "Vyřešeno";
                    break;
                }
              } else { 
                $check0 = "";
                $check1 = "";
                $check2 = "";
                $checkl0 = "";
                $checkl1 = "";
                $checkl2 = "";
                switch ($row_process) {
                  case 0:
                    $check0 = "checked";
                    $checkl0 = "active";
                    break;
                  case 1:
                    $check1 = "checked";
                    $checkl1 = "active";
                    break;
                  default:
                    $check2 = "checked";
                    $checkl2 = "active";
                    break;
                }
                ?>
                <form action="save-status.php" method="GET"  id="radio-form-<?php echo $i?>">
                  <div class="btn-group btn-group-toggle" data-toggle="buttons" id="radio-div-<?php echo $i?>">
                    <label class="btn btn-secondary<?php echo " " . $checkl0?>">
                      <input type="radio" name="status" id="status0<?php echo $i?>" value="0" <?php echo $check0 ?> > Čeká
                    </label>
                    <label class="btn btn-secondary<?php echo " " . $checkl1?>">
                      <input type="radio" name="status" id="status1-<?php echo $i?>" value="1" <?php echo $check1 ?> > Řešíme
                    </label>
                    <label class="btn btn-secondary<?php echo " " . $checkl2?>">
                      <input type="radio" name="status" id="status2-<?php echo $i?>" value="2" <?php echo $check2 ?> > Vyřešeno
                    </label>
                    <input type="hidden" name="error" id="error-hid-<?php echo $i?>" value="<?php echo $row_id?>">
                  </div>
                  <button type="submit" class="btn btn-success">Uložit</button>
                </form>
                <?php
              }
       ?></td>
      <td>
         <?php 
            $sql_count_comm = $conn->query("SELECT COUNT(*) AS n FROM Comment WHERE error = '$row_id'");
          
            $comm_count = $sql_count_comm->fetch_assoc()['n'];
            if($comm_count > 0) {
              $comm =  $conn->query("SELECT * FROM Comment WHERE error = '$row_id'");
            //  $comm_author = htmlspecialchars($comm_text["author"], ENT_QUOTES);
               //mysqli_num_rows($comm);
             
            ?>
            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#comment-modal<?php  echo $i?>" data-whatever="@mdo">
              Komentář <span class="badge badge-light"><?php echo $comm_count; ?></span>
            </button>
            <?php if(isset($_SESSION['status']) && $_SESSION['status'] == 1) { ?>
            
              <div class="modal fade" id="comment-modal<?php  echo $i?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="title-modal-<?php echo $i?>"><?php echo $row_name; ?> </h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="save_comment.php" method="GET">
                      <div class="modal-body">
                        <div class="comments" data-spy="scroll" data-offset="0">
                        <?php for ($j = 0; $j < $comm_count; $j++) { 
                          $comm_text = $comm->fetch_assoc();
                          $comm_text_r = htmlspecialchars($comm_text["text"], ENT_QUOTES);
                          $comm_date = htmlspecialchars($comm_text["date"], ENT_QUOTES);
                        ?>
                        <div class=comment-body>
                  
                          <div class="text-comment">
                            <?php echo $comm_text_r; ?>
                          </div>
                          <div class="date-comment">
                            <?php echo $comm_date; ?>
                          </div>
                      
                        </div>
                        <?php } ?>
                        </div>
                        <div class="form-group">
                          <label for="comment-text-<?php echo $i?>" class="col-form-label">Komentář:</label>
                          <textarea class="form-control" id="comment-text-<?php echo $i?>" name="comment-text" required> <?php echo $comm_text_r; ?> </textarea>
                          <input type="hidden" name="error_id_com" id="error_id_com-<?php echo $i?>" value="<?php echo $row_id?>">
                          <input type="hidden" name="new-comment" id="new-comment-<?php echo $i?>" value="1">
                        </div>
                      
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
                        <button type="submit" class="btn btn-primary">Uložit</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>


         <?php } else { ?>
            <div class="modal" id="comment-modal<?php  echo $i?>" tabindex="-1" role="dialog">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title"> <?php echo $row_name; ?> </h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                    <div class="comments" data-spy="scroll" data-offset="0">
                    <?php for ($j = 0; $j < $comm_count; $j++) { 
                      $comm_text = $comm->fetch_assoc();
                      $comm_text_r = htmlspecialchars($comm_text["text"], ENT_QUOTES);
                      $comm_date = htmlspecialchars($comm_text["date"], ENT_QUOTES);
                      ?>
                      <div class=comment-body>
                      
                          <div class="text-comment">
                            <?php echo $comm_text_r; ?>
                          </div>
                          <div class="date-comment">
                            <?php echo $comm_date; ?>
                          </div>
                      
                        </div>
                  <?php } ?>
                </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
                  </div>
                </div>
              </div>
            </div>
       <?php  }
       } else {
         if(isset($_SESSION['status']) && $_SESSION['status'] == 1) { ?>
            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#comment-modal<?php  echo $i?>" data-whatever="@mdo" >
              Komentář <span class="badge badge-light">0</span>
            </button>
            <div class="modal fade<?php  echo $i?>" id="comment-modal<?php  echo $i?>" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title"> <?php echo $row_name; ?> </h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <form action="save_comment.php" method="GET">
                    <div class="modal-body">
                      
                        <div class="form-group">
                          <label for="comment-text-<?php echo $i?>" class="col-form-label">Komentář:</label>
                          <textarea class="form-control" id="comment-text-<?php echo $i?>" name="comment-text" required></textarea>
                          <input type="hidden" name="error_id_com" id="error_id_com-<?php echo $i?>" value="<?php echo $row_id?>">
                          <input type="hidden" name="new-comment" id="new-comment-<?php echo $i?>" value="1">
                        </div>
                      
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Zavřít</button>
                      <button type="submit" class="btn btn-primary">Uložit</button>
                    </div>
                    </form>
                  </div>
                </div>
              </div>
         <?php } 
       }
         ?>
       
      </td>
      <td> 
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".modal<?php echo $i?>">Detail</button>

        <div class="modal fade modal<?php echo $i?>" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog modal-lg">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title"> <?php echo $row_name; ?> </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <p> <?php echo $row_text; ?> </p>
              </div>
            </div>
          </div>
        </div>
      </td>
      <?php if(isset($_SESSION['status']) && $_SESSION['status'] == 1) { 
        echo '<td> 
                <form action="delete-error.php" method="GET">
                  <input type="hidden" name="id_error" id="id_error-' . $i . '" value="' . $row_id . '">
                  <button type="submit" class="btn btn-danger">Smazat</button> 
                </form>
              </td>'; 
      } ?>
    </tr>
    <?php } ?>

  </tbody>
</table>


<nav aria-label="..." class="nav-page">
  <ul class="pagination">
    <li class="page-item <?php if($page <= 1) {echo 'disabled';}?>">
      <a class="page-link" href="<?php if($page <= 1) {echo '#'; } else { echo "?page=".($page - 1)  . "&category=" . $category; } ?>" tabindex="-1" aria-disabled="true">Předchozí</a>
    </li>
    <?php
      if($page > 2) {
        echo '<li class="page-item"><a class="page-link" href="' . '?page=' . ($page - 2) .'&category=' . $category .'">' . ($page - 2) . '</a></li>';
      }
    ?>
    <?php
      if($page > 1) {
        echo '<li class="page-item"><a class="page-link" href="' . '?page=' . ($page - 1) .'&category=' . $category . '">' . ($page - 1) . '</a></li>';
      }
    ?>
    <li class="page-item active" aria-current="page">
      <a class="page-link" href="#"> <?php echo $page ?> <span class="sr-only">(current)</span></a>
    </li>
    <?php
      if($total_pages > $page) {
        echo '<li class="page-item"><a class="page-link" href="' . '?page=' . ($page + 1) .'&category=' . $category . '">' . ($page + 1) . '</a></li>';
      }
    ?>
    <?php
      if($total_pages > $page+1) {
        echo '<li class="page-item"><a class="page-link" href="' . '?page=' . ($page + 2) .'&category=' . $category . '">' . ($page + 2) . '</a></li>';
      }
    ?>
    <li class="page-item <?php if($page >= $total_pages) {echo 'disabled'; } ?>">
      <a class="page-link" href="<?php if($page >= $total_pages) {echo '#'; } else { echo "?page=".($page + 1) . "&category=" . $category; } ?>">Další</a>
    </li>
  </ul>
</nav>

  <footer id="foot" class="foote-down">
    <a href="chage-style.php?color=-1" class="badge badge-light">Šedá</a>
    <a href="chage-style.php?color=1" class="badge badge-primary">Světle modrá</a>
  </footer> 

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>



</body>
</html>

<?php 
	$conn->close(); 
?>
