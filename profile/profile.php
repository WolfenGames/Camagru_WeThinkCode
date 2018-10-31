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
		<div class="user-control">
			<form method="post" action="profile/update.php">
				<div class="form-group">
					<label class="text-primary" for="email">Email</label>
					<input type="email" class="form-control" value="<?php echo $_SESSION['Email'];?>">
				</div>
				<div class="form-group">
					<label class="text-primary" for="text">Username</label>
					<input type="text" class="form-control" value="<?php echo $_SESSION['Username'];?>">
				</div>
				<div class="form-group">
					<label class="text-warning" for="pwd">Old Password</label>
					<input  class="form-control"type="password" name="oldPass">
				</div>
				<div class="form-group">
					<label class="text-warning"  for="pwd">New Password</label>
					<input class="form-control" type="password" name="newPass">
				</div>
				<div class="form-group">
					<label class="text-warning"  for="pwd">Confirm new Password</label>
					<input class="form-control" type="password" name="cNewPass">
				</div>
				<button type="submit" class="btn btn-primary">Update</button>
			</form>
		</div>	
		<?php
	}else
	{
		?>
		<div class="row prof-select">
			<button class="btn prof-butt" id="AlreadyMember" onclick="setState(1)">Already a member</button>
			<button class="btn prof-butt" id="NewMember" onclick="setState(2)">New Member</button>
			<button class="btn prof-butt" id="ForgotPassword" onclick="setState(3)">Forgot Password</button>
			<button class="btn prof-butt" id="Other" onclick="setState(4)">Other</button>
		</div>
		<!-- LOGGED OUT -->
		<div class="user-login">
			<label class="log-title">Old User???</label>
			<form action="profile/profile.php?method=login" method="POST">
				<div class="form-group">
					<label class="text-primary" for="email">Email</label>
					<input class="form-control" type="email" placeholder="example@host.com" name="email">
				</div>
				<div class="form-group">
					<label class="text-primary" for="pwd">Password</label>
					<input class="form-control" type="password" placeholder="Password" name="password">
				</div>
				<button type="submit" class="btn btn-primary">Login</button>
			</form>
		</div>
		
		<div class="user-control">
			<label class="log-title"> New User???</label>
			<form method="post" action="profile/create.php">
				<div class="form-group">
					<label class="text-primary" for="email">Email</label>
					<input type="email" class="form-control" placeholder="example@host.com">
				</div>
				<div class="form-group">
					<label class="text-primary" for="text">Username</label>
					<input type="text" class="form-control" placeholder="Username">
				</div>
				<div class="form-group">
					<label class="text-warning"  for="pwd">Password</label>
					<input class="form-control" type="password" placeholder="Password" name="newPass">
				</div>
				<div class="form-group">
					<label class="text-warning"  for="pwd">Confirm Password</label>
					<input class="form-control" type="password" placeholder="Confirm Password" name="cNewPass">
				</div>
				<button type="submit" class="btn btn-primary">Register</button>
			</form>
		</div>

		<div class="user-resubmit">
			<label class="log-title">Didn`t recieve a verification email? Try again</label>
			<form action="profile/profile.php?method=resend" method="POST">
				<div class="form-group">
					<label class="text-primary" for="email">Email</label>
					<input type="email" class="form-control" placeholder="example@host.com">
				</div>
				<button type="submit" class="btn btn-primary">Resend Email</button>
			</form>
		</div>
		<div class="user-resubmit">
			<label class="log-title">Forgot Password?</label>
			<form action="profile/profile.php?method=resend" method="POST">
				<div class="form-group">
					<label class="text-primary" for="email">Email</label>
					<input type="email" class="form-control" placeholder="example@host.com">
				</div>
				<button type="submit" class="btn btn-primary">Send Email</button>
			</form>
		</div>

		<?php
	}
?>
