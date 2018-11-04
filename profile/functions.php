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
					echo "SuccessRegistration";
				}
				else
					echo "InvalidRegistration";
				
            }
            catch (PDOException $e)
            {
                echo "Failed to register -> " . $e->getMessage();
            }
        }
        else
            echo "Password is invalid!\n";
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
		return (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}/g", $pass));
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
			$query = "UPDATE `camagru`.`users` SET `password` = :pass WHERE `username` = :Uname;";
			try
			{
				$stmt = $conn->prepare($query);
				$nPass = hash("sha512", $newP);
				$stmt->bindParam(":pass", $nPass);
				$user = $_SESSION['Username'];
				$stmt->bindParam(":Uname", $user);
				$stmt->execute();
				$stmt->SetFetchMode(PDO::FETCH_ASSOC);
				$res = $stmt->fetch();
				if ($res)
					echo "Success";
				else
					echo "Fail";
			}
			catch (PDOException $e)
			{
				echo "Change pass failed -> " . $e->getMessage();
			}
		}else
		{
			echo "Passwords don't match";
		}
	}
