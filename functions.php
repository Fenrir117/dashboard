<?php
session_start();

// connect to database
$db = new PDO('mysql:host=localhost;dbname=multi_login', 'root', '');

// variable declaration
$username = "";
$email    = "";
$season    = "";
$errors   = array();

// appel des function avec leur bouton respectif

// Function d'inscription
if (isset($_POST['register_btn'])) {
	register();
}
// Function création saisons 
if (isset($_POST['season_btn'])) {
	createSeason();
}

// Function de création de notification
if (isset($_POST['notif_btn'])) {
	createNotif();
}

// Function liée au bouton d'accepter la disponibilitée
if (isset($_POST['notif_accept'])) {
	acceptNotif(); 
}

// Function liée au bouton de refus la disponibilitée
if (isset($_POST['notif_refused'])) {
	refusedNotif();
}



// Fonction d'enregistrement
function register()
{
	// call these variables with the global keyword to make them available in function
	global $db, $errors, $username, $email;

	// receive all input values from the form. Call the e() function
	// defined below to escape form values
	$username    =  $_POST['username'];
	$email       =  $_POST['email'];
	$password_1  =  $_POST['password_1'];
	$password_2  =  $_POST['password_2'];
	$season = $_POST['season_user'];
	

	$sql_u = "SELECT * FROM users WHERE username=:username";
	$sth = $db->prepare($sql_u);
	$sth->bindParam(':username', $username, PDO::PARAM_STR);
	$sth->execute();
	$result_u = $sth->fetchAll(PDO::FETCH_ASSOC);

	$sql_e = "SELECT * FROM users WHERE email=:email";
	$sth = $db->prepare($sql_e);
	$sth->bindParam(':email', $email, PDO::PARAM_STR);
	$sth->execute();
	$result_e = $sth->fetchAll(PDO::FETCH_ASSOC);


	// form validation: ensure that the form is correctly filled
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($email)) {
		array_push($errors, "Email is required");
	}
	if (empty($password_1)) {
		array_push($errors, "Password is required");
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	if (count($result_u) > 0) {
		array_push($errors, "Sorry... username already taken");
	} else if (count($result_e) > 0) {
		array_push($errors, "Sorry... email already taken");
	} else {
		// register user if there are no errors in the form
		if (count($errors) == 0) {
			$password = md5($password_1); //encrypt the password before saving in the database
			$user_type = $_POST['user_type'];
			$sql = "INSERT INTO users (username, email, user_type, password) 
					  VALUES(:username, :email, :user_type, :password)";
			$sth = $db->prepare($sql);
			$sth->bindParam(':username', $username, PDO::PARAM_STR);
			$sth->bindParam(':email', $email, PDO::PARAM_STR);
			$sth->bindParam(':user_type', $user_type, PDO::PARAM_STR);
			$sth->bindParam(':password', $password, PDO::PARAM_STR);
			$sth->execute();

			$last_id = $db->lastInsertId();

			$sql_s = "SELECT * FROM saison WHERE date_saison='$season'";
			$sth_s = $db->prepare($sql_s);
			$sth_s->bindParam(':date_saison', $season, PDO::PARAM_STR);
			$sth_s->execute();
			$result = $sth_s->fetchAll();
			$idSeason = $result[0]["id_saison"];
			echo $idSeason;
			echo $last_id;

			$sql = "INSERT INTO inscription (user_identifiant, saison_id) 
					  VALUES(:user_identifiant, :saison_id)";
			$sth = $db->prepare($sql);
			$sth->bindParam(':user_identifiant', $last_id, PDO::PARAM_INT);
			$sth->bindParam(':saison_id', $idSeason, PDO::PARAM_STR);
			$sth->execute();


			$_SESSION['success']  = "New user successfully created!!";
			header('Location: home.php');
		}
	}
}

//Function de création de saisons
function createSeason()
{

	global $db, $errors, $season;
	$season =  $_POST['season'];

	if (empty($season)) {
		array_push($errors, "Une saisie est requise");
	}

	if (count($errors) == 0) {

		$sql = "INSERT INTO saison (date_saison) 
		VALUES('$season')";
		$sth = $db->prepare($sql);
		$sth->execute();
		$_SESSION['success']  = "New user successfully created!!";
		header('location: home.php');
	}
}

//Function de création de notification
function createNotif()
{
	// ajout de la date de l'evenement
	global $db, $errors;
	$dateEvent = $_POST['date_event'];
	$lieuMatch = $_POST['lieu_event'];
	$dispoEvent = $_POST['dispo_event'];
	$noReponse = NULL;

	if (empty($dateEvent)) {
		array_push($errors, "Date is required");
	}
	if (empty($lieuMatch)) {
		array_push($errors, "Place is required");
	}
	if (empty($dispoEvent)) {
		array_push($errors, "Dispo is required");
	}

	// Requete insert mon formulaire dans la table planning
	$sql = "INSERT INTO planning (jour_event, lieu, places_necessaires, places_restantes) 
		VALUES(:jour_event, :lieu, :places_necessaires, :places_restantes)";
	$sth = $db->prepare($sql);
	$sth->bindParam(':jour_event', $dateEvent, PDO::PARAM_STR);
	$sth->bindParam(':lieu', $lieuMatch, PDO::PARAM_STR);
	$sth->bindParam(':places_necessaires', $dispoEvent, PDO::PARAM_STR);
	$sth->bindParam(':places_restantes', $dispoEvent, PDO::PARAM_STR);
	$sth->execute();

}


//Function d'acceptation de Notif
function acceptNotif()
{
	global $errors,$db, $idUser;

	$place_dispo    =  $_POST['place_dispo'];
	$date_accept = $_POST['date_accept'];
	echo ($date_accept);
	echo ($place_dispo);
	echo ($idUser);

	if (empty($place_dispo)) {
		array_push($errors, "Veuillez indiqué le nombres de places disponible");
	} else {

		$usernameLog = $_SESSION['user']['username'];
	
		$sql_u = "SELECT id FROM users WHERE username='$usernameLog'";
		$sth_u = $db->prepare($sql_u);
		$sth_u->execute();
		$result = $sth_u->fetch(PDO::FETCH_ASSOC);
		
		$idUser = $result['id'];
		$reponseAccept = 1;

		//requete selection d'une date det d'un user
		$sql_verif = "SELECT * FROM response_parent WHERE jour_event='$date_accept' AND id_user='$idUser'";
		$sth_verif = $db->prepare($sql_verif);
		$sth_verif->bindParam(':jour_event', $date_accept, PDO::PARAM_STR);
		$sth_verif->bindParam(':id_user', $idUser, PDO::PARAM_INT);
		$sth_verif->execute();
		$result_verif = $sth_verif->fetchAll(PDO::FETCH_ASSOC);

		if (count($result_verif) ==0) {
			
		//requete insertion des valeur dans les colonnes jour_event(date d'evenement), id_user(id de l'utilisateur), reponse(oui ou non repondu par le parent), places(places disponible par les parents)
		$sql_b = "INSERT INTO response_parent (jour_event, id_user, reponse, places) VALUES(:jour_event, :id_user, :reponse, :places)";
		$sth = $db->prepare($sql_b);
		$sth->bindParam(':jour_event', $date_accept, PDO::PARAM_STR);
		$sth->bindParam(':id_user', $idUser, PDO::PARAM_INT);
		$sth->bindParam(':reponse', $reponseAccept, PDO::PARAM_BOOL);
		$sth->bindParam(':places', $place_dispo, PDO::PARAM_STR);
		$sth->execute();
		}

		//Double requete necessaire?
		// $sql_b = "INSERT INTO response_parent (jour_event, id_user, reponse, places) VALUES(:jour_event, :id_user, :reponse, :places)";
		// $sth = $db->prepare($sql_b);
		// $sth->bindParam(':jour_event', $date_accept, PDO::PARAM_STR);
		// $sth->bindParam(':id_user', $idUser, PDO::PARAM_INT);
		// $sth->bindParam(':reponse', $reponseAccept, PDO::PARAM_BOOL);
		// $sth->bindParam(':places', $place_dispo, PDO::PARAM_STR);

		// $sth->execute();
	}
}


// Function affichage d'erreur
function display_error()
{
	global $errors;

	if (count($errors) > 0) {
		echo '<div class="error">';
		foreach ($errors as $error) {
			echo $error . '<br>';
		}
		echo '</div>';
	}
}

// Function d'appel de login
function isLoggedIn()
{
	if (isset($_SESSION['user'])) {
		return true;
	} else {
		return false;
	}
}

// log user out if logout button clicked
if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: login.php");
}

// call the login() function if register_btn is clicked
if (isset($_POST['login_btn'])) {
	login();
}

// LOGIN USER
function login()
{
	global $db, $username, $errors;

	// grap form values
	$username = $_POST['username'];
	$password = $_POST['password'];

	// make sure form is filled properly
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}

	// attempt login if no errors on form
	if (count($errors) == 0) {
		$password = md5($password);

		$sql = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
		$sth = $db->prepare($sql);
		$sth->execute();
		$results = $db->query($sql);

		// $query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
		// $result = $db->query($query);

		if ($results->rowCount() == 1) { // user found
			// check if user is admin or user
			$logged_in_user = $results->fetch(PDO::FETCH_ASSOC);

			if ($logged_in_user['user_type'] == 'admin') {

				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				header('location: admin/home.php');
			} else {
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";

				header('location: index.php');
			}
		} else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}

function isAdmin()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin') {
		return true;
	} else {
		return false;
	}
}
