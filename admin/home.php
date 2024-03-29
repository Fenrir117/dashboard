<?php
include('../functions.php');

if (!isAdmin()) {
	$_SESSION['msg'] = "You must log in first";
	header('location: ../login.php');
}

if (isset($_GET['logout'])) {
	session_destroy();
	unset($_SESSION['user']);
	header("location: ../login.php");
}
?>
<!DOCTYPE html>
<html>

<head>
	<title>Home</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="assets/vendor/css/mdb.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
	<link rel="stylesheet" type="text/css" href="/../assets/css/style.css">
	<style>
		.header {
			background: #003366;
		}

		button[name=register_btn] {
			background: #003366;
		}
	</style>
</head>

<body>
	<?php
	include('../header.php');
	?>
	<div class="header">
		<h2>Admin - Home Page</h2>
		<p>
			<?php


			// $seasonUser  =  $_POST['season_user'];

			?>
		</p>

	</div>
	<div class="content">
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			<div class="error success">
				<h3>
					<?php
					echo $_SESSION['success'];
					unset($_SESSION['success']);
					?>
				</h3>
			</div>
		<?php endif ?>

		<!-- logged in user information -->
		<div class="profile_info">
			<img src="../images/admin_profile.png">

			<div>
				<?php if (isset($_SESSION['user'])) : ?>
					<strong><?php echo $_SESSION['user']['username']; ?></strong>

					<small>
						<i style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i>
						<br>
						<a href="home.php?logout='1'" style="color: red;">logout</a>
						&nbsp; <a href="create_user.php"> + add user</a> <a href="create_season.php"> + add season</a><a href="create_notif.php"> + add notif</a>
					</small>

				<?php endif ?>
			</div>
		</div>
	</div>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script type="text/javascript" src="assets/vendor/js/mdb.min.js"></script>
</body>

</html>