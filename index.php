<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Camagru of all Camagru's</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="css/index.css" /><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="js/main.js"></script>
</head>
<body>
	<?php
		require_once("profile/functions.php");
		session_start();
		?>
	<nav class="navbar navbar-expand-sm">
		<div class="container-fluid">
			<div class="navbar-header">
			<h3>Camagru of all Camagru's<h3>
			</div>
			<div class="navbar">
				<button onclick="changeTab('Feed')" class="btn btn-success navbar-btn">Feed</button>
				<div class="header-nav-div"></div>
				<button onclick="changeTab('Camera')" class="btn btn-success navbar-btn">Camera</button>
				<div class="header-nav-div"></div>
				<button onclick="changeTab('Profile')" class="btn btn-success navbar-btn">Profile</button>
				<div class="header-nav-div"></div>
			</div>
			<?php
				if (isset($_SESSION['Username']))
				{
					?>
					<form method="POST" action="logout.php">
						<button class="btn btn-success navbar-btn" type="submit">Logout</button>
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
				<canvas name="image" id="canvaspreview">Canvas Still Loading</canvas>
				<div id='sticker'>
				<form method="POST" action="add_pic.php" enctype="multipart/form-data">
					<fieldset class='user-login'>      
						<p>Which sticker would you like?</p>
						<div class="row">
						<input type="checkbox" id="corner" name="favorite_pet" value="corner.png">Corner thingy<br>
						<img src="stickers/corner.png" style="width: 50px; height: 50px">   
						</div>
						<div class="row">
						<input type="checkbox" id="flogo" name="favorite_pet" value="flogo.png">Blowy woly<br>     
						<img src="stickers/flogo.png" style="width: 50px; height: 50px">    
						</div>
						<div class="row">
						<input type="checkbox" id="grand" name="favorite_pet" value="grand.png">Grand Opening<br>  
						<img src="stickers/grand.png" style="width: 50px; height: 50px">       
						</div>
					<input class='btn' id="exampleView" type="button" value="show example" />      
					</fieldset>
				</div>
					<input style="width: 100%;" type="text" name='title' id='title' placeholder="Insert title here">
					<input style="background-color: #EF944C; width: 100%;" id="image_upload" accept="image/*" type='file' name='upload' id='upload' >
				</form>
				<div id="options">
					<?php 
						if (isset($_SESSION['Username']))
						{ ?>
							<input type="button" class="button" name="Submit" onclick="sendData()" id="button" value="Submit Photo">
						<?php }
						else
						{
						?>
							<input class='btn' type='button' style='width: 100%;' value='Please login' disabled>
						<?php
						} 
						?>
				</div>
				<input type="button" value="Cancel" id="delete_snap">
		</div>

		<div class="tab" id="Profile">
				<?php
					include("profile/profile.php");
				?>
		</div>
	</div>
</body>
</html>