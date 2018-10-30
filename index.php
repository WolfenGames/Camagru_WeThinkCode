<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Camagru of all Camagru's</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="css/index.css" />
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="js/main.js"></script>
</head>
<body>
	<?php
		require_once("profile/functions.php");
		session_start();
		if (!isset($_SESSION['Username']))
			if (login("Julian.w16@gmail.com", "123"))
				header("Location: ./");
	?>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
			<a class="navbar-brand" href="#">Camagru of all Camagru's</a>
			</div>
			<button onclick="changeTab('Feed')" class="btn btn-link navbar-btn">Feed</button>
			<button onclick="changeTab('Camera')" class="btn btn-link navbar-btn">Camera</button>
			<button onclick="changeTab('Profile')" class="btn btn-link navbar-btn">Profile</button>
			<?php
				if (isset($_SESSION['Username']))
				{
					?>
					<form method="POST" action="logout.php">
						<button class="btn btn-link navbar-btn" type="submit">Logout</button>
					</form>
					<?php
				}
			?>
		</div>
	</nav>

	<div class="container">

		<div class="tab" id="Feed">
			<div id="gallery">

			</div>
		</div>

		<div class="tab" id="Camera">
				<div>
					<video id="video">Video is loading...</video>
				</div>
				<div class="bar">
					<div style="margin: auto;">
						<input type="button" value="Take the Shot!!" id="snap">
					</div>
				</div>
				<canvas name="image" id="canvas">Canvas Still Loading</canvas>
				<div id="options">
					<input type="button" class="button" name="Submit" onclick="sendData()" id="button" value="Submit Photo">
				</div>
				<input type="button" value="Cancel" id="delete_snap">
		</div>

		<div class="tab" id="Profile">

		</div>
	</div>
</body>
</html>