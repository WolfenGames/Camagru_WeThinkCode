<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Camagru of all Camagru's</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="../css/index.css" />
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<?php

require_once("functions.php");

if (isset($_POST['submit']) && isset($_POST['verify']))
{
	header("Location: ./resetpw.php?message=" . reset_pass($_POST['newPass'], $_POST['cNewPass'], $_POST['verify']));
}

?>
<body>
	<nav class="navbar navbar-expand-sm">
		<div class="container-fluid">
			<div class="navbar-header">
			<h3><a href="../">Camagru of all Camagru's<a><h3>
			<h2>Reset Password</h2>
			</div>
		</div>
	</nav>

	<div class="container">
		<form action="resetpw.php" method="POST">
			<div class="form-group">
				<label class="text-warning"  for="pwd">New Password</label>
				<input class="form-control" type="password" name="newPass">
			</div>
			<div class="form-group">
				<label class="text-warning"  for="pwd">Confirm new Password</label>
				<input class="form-control" type="password" name="cNewPass">
			</div>
			<input type='hidden' name='verify' value="<?php echo $_GET['verify'];?>">
			<button name="submit" type="submit" class="btn btn-primary">Reset</button>
		</form>
	</div>
	<div class="footer">
	<p>© jwolf 2018</p>
	</div>
</body>
</html>