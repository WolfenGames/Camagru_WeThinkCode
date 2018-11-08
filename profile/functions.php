<?php
	set_include_path("../");
	require_once("config/database.php");

	function login($email, $pass)
	{
		global $conn;
		$pass = hash("sha512", $pass);
		$query = "SELECT * FROM `camagru`.`users` WHERE `email` = :email AND `password` = :pass LIMIT 1;";
		$stmt = $conn->prepare($query);
		$stmt->bindParam(":pass", $pass);
		$stmt->bindParam(":email", $email);
		$stmt->execute();
		$stmt->SetFetchMode(PDO::FETCH_ASSOC);
		$user = $stmt->fetch();
		if ($user)
		{
			if ($user['isVerified'] == 0)
				return "Need Verify";
			else
			{
				$_SESSION['Username'] = $user['username'];
				$_SESSION['Email'] = $user['email'];
				$_SESSION['UID'] = $user['ID'];
				$_SESSION['UserPref'] = $user['emailpref'];
				return "Logged in";
			}
		}
		return "No info";
	}

	function logout()
	{
		session_destroy();
		header("Location: ./");
	}

	function resend_verify($email)
	{
		global $conn;
		try
		{
			$key = hash("sha512", $email . time());
			$query = "UPDATE `camagru`.`users` SET `verifyKey` = :vkey, `isVerified` = 0 WHERE `email` = :email;";
			$stmt = $conn->prepare($query);
			$stmt->bindParam(":vkey", $key);
			$stmt->bindParam(":email", $email);
			$stmt->execute();
			send_verify($email, $key);
		}
		catch (PDOException $e)
		{
			echo "Can not resend verification email -> " . $e->getMessage();
		}
	}

	function send_verify($email, $key)
	{
		$from = "admin@camagru.com";
		$to = $email;
		$subject = "Verify Camagru Account";
		$message = "<html><body>";
		$message .= "Please click on the following link to allow us to activate you account\n";
		$loc = str_replace("?method=resend", "", $_SERVER['REQUEST_URI']);
		$loc = str_replace("?method=login", "", $loc);
		$loc = str_replace("?method=register", "", $loc);
		$message .= "<a href='http://".$_SERVER['HTTP_HOST'] . $loc ."/../verify.php?verify=".$key."'><p>Click me!!</p></a>";
		$message .= "</body></html>";
		$headers = "From:" . $from . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= "X-Mailer: PHP/" . PHP_VERSION;
		mail($to,$subject,$message, $headers);
	}

	function reset_pass($nPass, $cPass, $key)
	{
		if ($nPass == $cPass)
		{
			if (passValid($nPass))
			{
				global $conn;
				try
				{
					$query = "UPDATE `camagru`.`users` SET `password`=:pass WHERE `verifyKey`=:vkey;";
					$stmt = $conn->prepare($query);
					$pass = hash("sha512", $nPass);
					$stmt->bindParam(":pass", $pass);
					$stmt->bindParam(":vkey", $key);
					$stmt->execute();
					$res = $stmt->rowCount();
					if ($res == 1)
					{
						$query1 = "SELECT `email` FROM `camagru`.`users` WHERE `verifyKey`=:vkey;";
						$stmt2 = $conn->prepare($query1);
						$stmt2->bindParam(":vkey", $key);
						$stmt2->execute();
						$stmt2->SetFetchMode(PDO::FETCH_ASSOC);
						$res = $stmt2->fetch();
						notify_user($res['email'], 4);
						return "Password Reset";
					}
					else
						return "Undefined Error&verify=" . $key;
				}
				catch (PDOException $e)
				{
					return ($e->getMessage());	
				}
			}
			else
				return ("Password not valid&verify=" . $key);
		}else
			return ("Passwords dont match&verify=" . $key);
	}

	function passreset($email)
	{
		global $conn;
		try
		{
			$query = "SELECT `verifyKey` FROM `camagru`.`users` WHERE `email`=:email;";
			$stmt = $conn->prepare($query);
			$stmt->bindParam(":email", $email);
			$stmt->execute();
			$stmt->SetFetchMode(PDO::FETCH_ASSOC);
			$user = $stmt->fetch();
			if ($user)
				send_pass_reset_verify($email, $user['verifyKey']);
			return ("Request sent");
		}
		catch (PDOException $e)
		{
			return ($e->getMessage());
		}
	}

	function send_pass_reset_verify($email, $key)
	{
		$from = "admin@camagru.com";
		$to = $email;
		$subject = "Reset Camagru Account";
		$message = "<html><body>";
		$message .= "Please click on the following link to reset your password\n";
		$loc = str_replace("?method=resend", "", $_SERVER['REQUEST_URI']);
		$loc = str_replace("?method=login", "", $loc);
		$loc = str_replace("?method=register", "", $loc);
		$loc = str_replace("?method=forgotpass", "", $loc);
		$message .= "<a href='http://".$_SERVER['HTTP_HOST'] . $loc ."/../resetpw.php?verify=".$key."'><p>Click me!!</p></a>";
		$message .= "</body></html>";
		$headers = "From:" . $from . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= "X-Mailer: PHP/" . PHP_VERSION;
		mail($to,$subject,$message, $headers);
	}

	function notify_user($email, $option)
	{
		if ($option == 1)
			$message_add = "User has liked your photo";
		if ($option == 2)
			$message_add = "User has commented on your photo";
		if ($option == 3)
			$message_add = "You have updated your profile";
		if ($option == 4)
			$message_add = "You have changed your password";
		
		$from = "admin@camagru.com";
		$to = $email;
		$subject = "Camagru Account Notifications";
		$message = "<html><body>";
		$message .= "<h1>" . $message_add . "</h1>";
		$message .= "</body></html>";
		$headers = "From:" . $from . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= "X-Mailer: PHP/" . PHP_VERSION;
		mail($to,$subject,$message, $headers);
	}

	function delete_account($email, $pass)
	{
		global $conn;
		try
		{
			$pass = hash("sha512", $pass);
			$query = "DELETE FROM `camagru`.`users` WHERE `password` = :pass LIMIT 1;";
			$stmt = $conn->prepare($query);
			$stmt->bindParam(":pass", $pass);
			$stmt->execute();
		}
		catch(PDOException $e)
		{
			echo "Can`t delete -> " . $e->getMessage();
		}
	}

	function register($login, $email, $pass, $veripass)
	{
		global $conn;
		if (passValid($pass) && passValid($veripass))
		{
			if ($pass == $veripass)
			{
				try
				{
					$pass = hash("sha512", $pass);
					$query = "INSERT INTO `camagru`.`users` (`username`, `email`, `password`, `verifyKey`) VALUES (:uname, :email, :pass, :vkey);";
					$key = hash("sha512", $email . time());
					$stmt = $conn->prepare($query);
					$stmt->bindParam(":uname", $login);
					$stmt->bindParam(":email", $email);
					$stmt->bindParam(":pass", $pass);
					$stmt->bindParam(":vkey", $key);
					$stmt->execute();
					if ($stmt)
					{
						send_verify($email, $key);
						return "SuccessRegistration";
					}
					else
						return "InvalidRegistration";
					
				}
				catch (PDOException $e)
				{
					echo "Failed to register -> " . $e->getMessage();
				}
			}
			else
				return "Password is invalid!\n";
		}else
		{
			return "Password needs 8 minimum characters, 1 upper, 1 special and a number";
		}
	}

	function like($id)
	{
		global $conn;
		try
		{
			$query = "INSERT INTO `camagru`.`likes` (`ref_id`, `likes`) VALUES (:myid, :id);";
			$stmt = $conn->prepare($query);
			$myid = $_SESSION['UID'];
			$stmt->bindParam(":id", $id);
			$stmt->bindParam(":myid", $myid);
			$stmt->execute();
		}
		catch (PDOException $e)
		{
			echo "Failed to like -> " . $e;
		}
	}

	function dislike($id)
	{
		global $conn;
		try
		{
			$query = "DELETE FROM `camagru`.`likes` WHERE `ref_id` = :myid AND `likes` = :id;";
			$stmt = $conn->prepare($query);
			$myid = $_SESSION['UID'];
			$stmt->bindParam(":id", $id);
			$stmt->bindParam(":myid", $myid);
			$stmt->execute();
		}
		catch (PDOException $e)
		{
			echo "Failed to like -> " . $e;
		}
	}

	function get_likes($id)
	{
		global $conn;
		try
		{
			$query = "SELECT * FROM `camagru`.`likes` WHERE `likes` = :id;";
			$stmt = $conn->prepare($query);
			$stmt->bindParam(":id", $id);
			$stmt->execute();
			$stmt->SetFetchMode(PDO::FETCH_ASSOC);
			$like = $stmt->fetchAll();
			return (count($like));
		}
		catch (PDOException $e)
		{
			echo "Failed to recieve likes -> " . $e;
		}
	}

	function hasLiked($id)
	{
		global $conn;
		try
		{
			$query = "SELECT * FROM `camagru`.`likes` WHERE `ref_id` = :myid AND `likes` = :id LIMIT 1";
			$stmt = $conn->prepare($query);
			$myid = $_SESSION['UID'];
			$stmt->bindParam(":id", $id);
			$stmt->bindParam(":myid", $myid);
			$stmt->execute();
			$stmt->SetFetchMode(PDO::FETCH_ASSOC);
			$like = $stmt->fetch();
			if ($like)
				return false;
			else
				return true;

		}
		catch (PDOException $e)
		{
			echo "Failed to recieve likes -> " . $e;
		}
	}

	function passValid($pass)
	{
		return (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/", $pass));
	}

	function checkPass($pass)
	{
		global $conn;
		$query = "SELECT * FROM `camagru`.`users` WHERE `password` = :pass LIMIT 1;";
		try
		{
			$stmt = $conn->prepare($query);
			$hpass = hash("sha512", $pass);
			$stmt->bindParam(":pass", $hpass);
			$stmt->execute();
			$stmt->SetFetchMode(PDO::FETCH_ASSOC);
			$res = $stmt->fetch();
			return ($res);
		}
		catch (PDOException $e)
		{
			echo "Pass check failed -> " . $e->getMessage();
		}
	}

	function changePass($oldP, $newP, $newCP)
	{
		global $conn;
		if ($newP == $newCP && checkPass($oldP))
		{
			try
			{
				$query = "UPDATE `camagru`.`users` SET `password` = :pass WHERE `username` = :Uname;";
				$stmt = $conn->prepare($query);
				$nPass = hash("sha512", $newP);
				$stmt->bindParam(":pass", $nPass);
				$user = $_SESSION['Username'];
				$stmt->bindParam(":Uname", $user);
				$stmt->execute();
				$res = $stmt->rowCount();
				if ($res == 1)
				{
					return "Password Updated";
				}
				else
					return "Nothing Updated";
			}
			catch (PDOException $e)
			{
				return "Change pass failed -> " . $e->getMessage();
			}
		}else
		{
			return "Passwords don't match";
		}
	}

	function DoIexist()
	{
		return (isset($_SESSION['Username']));
	}

	function updateUser($uname, $email, $preff)
	{
		global $conn;
		try
		{
			$query = "UPDATE `camagru`.`users` SET `username` = :uname, `email` = :email, `emailpref` = :epref WHERE `username` = :cuname AND `email` = :cemail;";
			$stmt = $conn->prepare($query);
			$stmt->bindParam(":uname", $uname);
			$stmt->bindParam(":email", $email);
			$i = ($preff == true) ? 1 : 0;
			$stmt->bindParam(":epref", $i);
			$cuser = $_SESSION['Username'];
			$cemail = $_SESSION['Email'];
			$stmt->bindParam(":cuname", $cuser);
			$stmt->bindParam(":cemail", $cemail);
			$stmt->execute();
			$res = $stmt->rowCount();
			if ($res == 1)
			{
				$_SESSION['Username'] = $uname;
				$_SESSION['Email'] = $email;
				$_SESSION['UserPref'] = $i;
				return "User Updated";
			}
			else
				return "Nothing Updated";
		}
		catch (PDOException $e)
		{
			return "Can't Update users -> " . $e->getMessage();
		}
	}

	function makeComment($comment, $id)
	{
		global $conn;
		try
		{
			$query = "INSERT INTO `camagru`.`comments` (`img_id`, `commenter`, `comment`, `Date`) VALUES (:imgid, :user, :comment, NOW());";
			$imgid = $id;
			$user = $_SESSION['UID'];
			$icomment = $comment;
			$stmt = $conn->prepare($query);
			$stmt->bindParam(":imgid", $imgid);
			$stmt->bindParam(":user", $user);
			$stmt->bindParam(":comment", $icomment);
			$stmt->execute();
			$query2 = "SELECT * FROM `camagru`.`users` WHERE `ID`=:ui;";
			$stmt2 = $conn->prepare($query2);
			$stmt2->bindParam(":ui", $user);
			$stmt2->execute();
			$stmt2->SetFetchMode(PDO::FETCH_ASSOC);
			$user = $stmt2->fetch();
			if ($user && $user['emailpref'] == 1)
				notify_user($user['email'], 2);
		}
		catch(PDOException $e)
		{
			echo "Comment fail -> " . $e->getMessage();
		}
	}

	function deleteComment($id)
	{
		global $conn;
		try
		{
			$query = "DELETE FROM `camagru`.`comments` WHERE `ID` = :id;";
			$stmt = $conn->prepare($query);
			$stmt->bindParam(":id", $id);
			$stmt->execute();
		}
		catch (PDOException $e)
		{
			echo "Can`t delete comment -> " . $e->getMessage();
		}
	}

	function correctCurr($cpass)
	{
		global $conn;
		try
		{
			$query = "SELECT * FROM `camagru`.`users` WHERE `username` = :uname LIMIT 1;";
			$stmt = $conn->prepare($query);
			$uname = $_SESSION['Username'];
			$stmt->bindParam(":uname", $uname);
			$stmt->execute();
			$stmt->SetFetchMode(PDO::FETCH_ASSOC);
			$res = $stmt->fetch();
			$hasp = hash("sha512", $cpass);
			if ($res['password'] == $hasp)
				return 1;
			else
				return 0;
		}
		catch (PDOException $e)
		{
			return false;
		}
	}
