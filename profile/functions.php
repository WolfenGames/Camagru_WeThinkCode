<?php
	set_include_path("../");
	require_once("config/config.php");

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
			if ($preff)
			{
				$preff = 1;
			}
			else
			{
				$preff = 0;
			}
			$query = "UPDATE `camagru`.`users` SET `username` = :uname, `email` = :email, `emailPref` = :epref WHERE `username` = :cuname AND `email` = :cemail;";
			$stmt = $conn->prepare($query);
			$stmt->bindParam(":uname", $uname);
			$stmt->bindParam(":email", $email);
			$stmt->bindParam(":epref", $preff);
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

	function uploadImage($file)
	{
		global $conn;
		try
		{
			$newf = base64_encode($file);
			$query = "INSERT INTO `camagru`.`images` (`image_data`, `image_name`. `user_id`) VALUES (:imgdat, :imgName, :id);";
			$imgname = $_SESSION['Username'];
			$imgid = $_SESSION['UID'];
			$stmt = $conn->prepare($query);
			$stmt->bindParam(":imgdat", $newf);
			$stmt->bindParam(":imgName", $imgname);
			$stmt->bindParam(":id", $imgid);
			$stmt->execute();
		}
		catch (PDOException $e)
		{
			echo "Upload image failed -> " . $e->getMessage();
		}
	}

	function makeComment($comment, $id)
	{
		global $conn;
		try
		{
			$query = "INSERT INTO `camagru`.`comments` (`img_id`, `commenter`, `comment`, `Date`) VALUES (:imgid, :user, :comment, NOW());";
			$imgid = $id;
			$user = $_SESSION['Username'];
			$icomment = $comment;
			$stmt = $conn->prepare($query);
			$stmt->bindParam(":imgid", $imgid);
			$stmt->bindParam(":user", $user);
			$stmt->bindParam(":comment", $icomment);
			$stmt->execute();
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
