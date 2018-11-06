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
		$preff = isset($_POST['emailPref']);
		if (isset($oPass) && isset($nPass) && isset($cPass))
		{
			if (checkPass($nPass) && checkPass($cPass))
			{
				if ($cPass == $nPass)
				{
					if (correctCurr($cPass))
					{
						$message = "message-pass=" . changePass($oPass, $nPass, $cPass) . "&message-user=";
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
		if (isset($uname) && isset($email) && isset($preff))
		{
			if (!isset($message))
				$message = "message-user=";
			$message .= updateUser($uname, $email, $preff);
			header("Location: ../?" . $message);
		}
		else
			header("Location: ../");
	}
	else
		header("Location: ../");