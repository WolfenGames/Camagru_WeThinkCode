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
				header("Location: ../?message=" . login($_POST['uname'], $_POST['password']));
			}
			catch(PDOException $e)
			{
				echo "Danger robinson Login " . $e->getMessage();
			}
		}
		if ($method == 'register')
		{
			try
			{
				header("Location: ../?message=" . register($_POST['uname'], $_POST['email'], $_POST['newPass'], $_POST['cNewPass']));
			}
			catch (PDOException $e)
			{
				echo "Danger robinson Register " . $e->getMessage();
			}
		}
		if ($method == "resend")
		{
			if ($_POST['email'])
			{
				resend_verify($_POST['email']);
				header("Location: ../?message=EmailSent");
			}
			else
				header("Location: ../?message=NoEmail");
		}
		if ($method == "forgotpass")
		{
			if (isset($_POST['email']))
			{
				try
				{
					header("Location: ../?message=" . passreset($_POST['email']));
				}
				catch (PDOException $e)
				{
					echo "Danger robinson Forgot Pass " . $e->getMessage();
				}
			}
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
					<input type="email" class="form-control" name="email" value="<?php echo $_SESSION['Email'];?>">
				</div>
				<div class="form-group">
					<label class="text-primary" for="text">Username</label>
					<input type="text" class="form-control" name="username" value="<?php echo $_SESSION['Username'];?>">
				</div>
				<div class="form-group">
					<label class="text-primary" for="pwd">Old Password</label> <br />
					<label class="text-warning"  for="pwd">Password must contain atleast 1 Uppercase, 1 lowercase, 1 special and minimum 8 characters </label>
					<input  class="form-control"type="password" name="oldPass" pattern='^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$'>
				</div>
				<div class="form-group">
					<label class="text-primary"  for="pwd">New Password</label> <br />
					<label class="text-warning"  for="pwd">Password must contain atleast 1 Uppercase, 1 lowercase, 1 special and minimum 8 characters </label>
					<input class="form-control" type="password" name="newPass" pattern='^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$'>
				</div>
				<div class="form-group">
					<label class="text-primary"  for="pwd">Confirm new Password</label> <br />
					<label class="text-warning"  for="pwd">Password must contain atleast 1 Uppercase, 1 lowercase, 1 special and minimum 8 characters </label>
					<input class="form-control" type="password" name="cNewPass" pattern='^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$'>
				</div>
				<div class="checkbox">
				<?php
					if ($_SESSION['UserPref'] == 1)
					{
						?>
						<label><input checked value="true" type="checkbox" name="emailPref"> Email Preference - Would you like to recieve emails from events</label>
						<?php
					}
					else
					{
						?>
						<label><input value="true" type="checkbox" name="emailPref"> Email Preference - Would you like to recieve emails from events</label>
						<?php
					}
					?>
				</div>
				<button type="submit" class="btn btn-primary">Update</button>
			</form>
		</div>	
		<?php
	}else
	{
		?>
		<div class="row prof-select">
			<button class="btn prof-butt" id="AlreadyMember" onclick="setState(1)">Old member</button>
			<button class="btn prof-butt" id="NewMember" onclick="setState(2)">New Member</button>
			<button class="btn prof-butt" id="ForgotPassword" onclick="setState(3)">Forgot Password</button>
			<button class="btn prof-butt" id="Other" onclick="setState(4)">Other</button>
		</div>
		<!-- LOGGED OUT -->
		<div id="user-login" class="user-login">
			<label class="log-title">Old User???</label>
			<form action="profile/profile.php?method=login" method="POST">
				<div class="form-group">
					<label class="text-primary" for="text">Username</label>
					<input class="form-control" type="text" placeholder="Username" name="uname">
				</div>
				<div class="form-group">
					<label class="text-primary" for="pwd">Password</label> <br />
					<label class="text-warning"  for="pwd">Password must contain atleast 1 Uppercase, 1 lowercase, 1 special and minimum 8 characters </label>
					<input class="form-control" type="password" placeholder="Password" name="password" pattern='^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$'>
				</div>
				<button type="submit" class="btn btn-primary">Login</button>
			</form>
		</div>
		
		<div id="user-control" class="user-control">
			<label class="log-title"> New User???</label>
			<form method="post" action="profile/profile.php?method=register">
				<div class="form-group">
					<label class="text-primary" for="email">Email</label>
					<input type="email" class="form-control" placeholder="example@host.com" name="email">
				</div>
				<div class="form-group">
					<label class="text-primary" for="text">Username</label>
					<input type="text" class="form-control" placeholder="Username" name="uname">
				</div>
				<div class="form-group">
					<label class="text-primary"  for="pwd">Password</label> <br />
					<label class="text-warning"  for="pwd">Password must contain atleast 1 Uppercase, 1 lowercase, 1 special and minimum 8 characters </label>
					<input class="form-control" type="password" placeholder="Password" name="newPass" pattern='^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$'>
				</div>
				<div class="form-group">
					<label class="text-primary"  for="pwd">Confirm Password</label> <br />
					<label class="text-warning"  for="pwd">Password must contain atleast 1 Uppercase, 1 lowercase, 1 special and minimum 8 characters </label>
					<input class="form-control" type="password" placeholder="Confirm Password" name="cNewPass" pattern='^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$'>
				</div>
				<button type="submit" class="btn btn-primary">Register</button>
			</form>
		</div>

		<div id="user-resubmit-email" class="user-resubmit-email">
			<label class="log-title">Didn`t recieve a verification email? Try again</label>
			<form action="profile/profile.php?method=resend" method="POST">
				<div class="form-group">
					<label class="text-primary" for="email">Email</label>
					<input name="email" type="email" class="form-control" placeholder="example@host.com">
				</div>
				<button type="submit" class="btn btn-primary">Resend Email</button>
			</form>
		</div>
		<div id="user-resubmit" class="user-resubmit">
			<label class="log-title">Forgot Password?</label>
			<form action="profile/profile.php?method=forgotpass" method="POST">
				<div class="form-group">
					<label class="text-primary" for="email">Email</label>
					<input name="email" type="email" class="form-control" placeholder="example@host.com">
				</div>
				<button type="submit" class="btn btn-primary">Send Email</button>
			</form>
		</div>

		<?php
	}
?>
