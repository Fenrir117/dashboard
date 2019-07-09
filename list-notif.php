<?php
include('functions.php');

if (!isLoggedIn()) {
  $_SESSION['msg'] = "You must log in first";
  header('location: login.php');
}
?>
<!DOCTYPE html>
<html>

<head>
  <title>Home</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link href="assets/vendor/css/mdb.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

  <link rel="stylesheet" type="text/css" href="/assets/css/style.css">

</head>

<body>


<?php
include('header.php');
?>

  <section id="container-messages">
    <h2 class="text-center">Mes messages</h2>
    <ul>
      <?php
      $usernameLog = $_SESSION['user']['username'];
      // echo $usernameLog;

      $sql_u = "SELECT id FROM users WHERE username='$usernameLog'";
      $sth_u = $db->prepare($sql_u);
      // $sth->bindParam(':username', $usernameLog, PDO::PARAM_INT);
      $sth_u->execute();
      $result = $sth_u->fetch(PDO::FETCH_ASSOC);

      $idUser = $result['id'];

      $sql = "SELECT * FROM planning";
      $sth = $db->prepare($sql);
      $sth->execute();
      $result = $sth->fetchAll(PDO::FETCH_ASSOC);

      echo display_error();


      foreach ($result as $sql) {

        $date_event = $sql["jour_event"];

        $sql_rep = "SELECT * FROM response_parent WHERE id_user='$idUser' AND jour_event = '$date_event'";
        $sth_rep = $db->prepare($sql_rep);
        $sth_rep->bindParam(':username', $usernameLog, PDO::PARAM_STR);
        $sth_rep->execute();
        $result_rep = $sth_rep->fetchAll(PDO::FETCH_ASSOC);

        $sql_obj = "SELECT * FROM planning WHERE jour_event = '$date_event'";
        $sth_obj = $db->prepare($sql_obj);
        $sth_obj->bindParam(':jour_event', $date_event, PDO::PARAM_STR);
        $sth_obj->execute();
        $result_obj = $sth_obj->fetchAll(PDO::FETCH_ASSOC);

        $sql_sco = "SELECT places FROM response_parent WHERE jour_event = '$date_event'";
        $sth_sco = $db->prepare($sql_sco);
        $sth_sco->bindParam(':jour_event', $date_event, PDO::PARAM_STR);
        $sth_sco->execute();
        $result_sco = $sth_sco->fetchAll(PDO::FETCH_ASSOC);

       
        $test = 0;
        $score = 0;

        for ($i = 0; $i < sizeof($result_sco); $i++) {
          $score += $result_sco[$i]["places"];

        
        }
        echo $score.'<br>';
        echo($result_obj[0]["places_necessaires"]);
        echo '<br>';
        echo ($sql["jour_event"].'<br>');

        
        if ($score >= $result_obj[0]["places_necessaires"]) {
          echo 'objectif atteint';
        }
        else if (count($result_rep) == 0) {

          echo '<form method="post" action="list-notif.php">
            <input type="hidden" name="date_accept" value="' . $sql["jour_event"] . '" >
            <div class="input-group">
			<label>Nombre de place dispo :</label>
			<input type="number" name="place_dispo" >
		    </div>
            <div class="input-group">
                <button type="submit" class="btn" name="notif_accept">Accepter</button>
            </div>
        </form>
        <form method="post" action="list-notif.php">
            <div class="input-group">
                <button type="submit" class="btn" name="notif_refused">Refuser</button>
            </div>
        </form>';
        } 
        
        else {
          echo 'repondu<br><br>';
        }
      }

      ?>
    </ul>
  </section>

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script type="text/javascript" src="assets/vendor/js/mdb.min.js"></script>

</body>

</html>