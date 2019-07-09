<?php include('../functions.php') ?>
<!DOCTYPE html>
<html>

<head>
	<title>Add new match - Create notification for users</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="assets/vendor/css/mdb.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
	<link rel="stylesheet" type="text/css" href="../assets/css/style.css">
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
		<h2>Admin - create notification</h2>
	</div>

	<form method="post" action="create_notif.php">

		<?php echo display_error(); ?>

		<div class="input-group">
			<label>Date du match :</label>
			<input type="date" name="date_event">
		</div>
		<div class="input-group">
			<label>Lieu du match :</label>
			<input type="text" name="lieu_event">
		</div>
		<div class="input-group">
			<label>Nombre de places necessaire :</label>
			<input type="number" name="dispo_event">
		</div>


		<div class="input-group">
			<button type="submit" class="btn" name="notif_btn"> + Create season</button>
		</div>
	</form>

	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script type="text/javascript" src="assets/vendor/js/mdb.min.js"></script>
</body>

</html>