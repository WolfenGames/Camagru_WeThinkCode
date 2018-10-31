<?php
	session_start();
	require_once("functions.php");
	if (isset($_GET['method']))
	{
		$method = $_GET['method'];
		if ($method == 'login')
		{
			try
			{
				header("Location: ../?" . login($_POST['email'], $_POST['password']));
			}
			catch(PDOException $e)
			{
				echo "Danger robinson " . $e->getMessage();
			}
		}
		if ($method == "resend")
		{
			$_POST['email'] = "Julian.w16@gmail.com";
			resend_verify($_POST['email']);
			header("Location: ../");
		}
	}

	if (isset($_SESSION['Username']))
	{
		?>
		<!-- LOGGED IN -->
			Logged in		
		<?php
	}else
	{
		?>
		<!-- LOGGED OUT -->

		<form action="profile/profile.php?method=login" method="POST">
			<p>Email:</p>
			<p><input type="text" name="email"></p>
			<p>Password: </p>
			<p><input type="password" name="password"></p>
			<p><input type="submit" name="submit" value="Login"><p>
		</form>
		<form action="profile/profile.php?method=resend" method="POST">
			<p>Email:</p>
			<p><input type="text" name="email"></p>
			<p><input type="submit" name="submit" value="Login"><p>
		</form>
		<?php
	}
?>
