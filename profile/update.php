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
						$message = "message=Incorrect Current Password&message-user=";
					}
				}
				else
				{
					$message = "message-pass=Passwords dont match&message-user=";
				}
			}else
			{
				$message = "message-pass=Failed to update password, incorrect length, Needs 1 Uppercase, 1 number, 1 special, mininum 8 characters&message-user=";
			}
		}
		if (isset($uname) && isset($email) && isset($preff))
		{
			if (!isset($message))
				$message = "message-user=";
			$message .= updateUser($uname, $email, $preff);
			if ($_SESSION['UserPref'] == 1)
				notify_user($email, 3);
			header("Location: ../?" . $message);
		}
		else
			header("Location: ../");
	}
	else
		header("Location: ../");