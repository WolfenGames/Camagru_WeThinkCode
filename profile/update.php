<?php
	require_once("functions.php");
	session_start();

	if ($_POST)
	{
		$oPass = $_POST['oldPass'];
		$nPass = $_POST['newPass'];
		$cPass = $_POST['cNewPass'];
		$uname = $_POST['username'];
		$email = $_POST['email'];
		$preff = $_POST['emailPref'];
		if (isset($oPass) && isset($nPass) && isset($cPass))
		{
			if (checkPass($nPass) && checkPass($cPass))
			{
				if ($cPass == $nPass)
				{
					if (correctCurr($cPass))
					{
						$message = changePass($oPass, $nPass, $cPass) . "+";
						$message .= updateUser($uname, $email, $preff);
						header("Location: ../?message=" . $message);
					}
					else
					{
						header("Location: ../?message=Incorrect Current Password");
					}
				}
				else
				{
					header("Location: ../?message=Passwords dont match");
				}
			}else
			{
				header("Location: ../?message=Failed to update password, incorrect length, Needs 1 Uppercase, 1 number, 1 special, mininum 8 characters");
			}
		}
		else
			header("Location: ../");
	}
	else
		header("Location: ../");