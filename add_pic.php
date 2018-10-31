<?php
    require_once("config/config.php");
    session_start();

	if (isset($_SESSION['Username']))
	{
		if (isset($_POST['img']))
		{
			$img = $_POST['img'];
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);

			$query = "INSERT INTO `camagru`.`images` (`image_data`, `image_name`, `user_id`) VALUES (
				'$img',
				:uname,
				:userid
			)";
			try {
				$db = $conn->prepare($query);
				$noname = $_SESSION['Username'];
				$db->bindParam(":uname", $noname);//$_SESSION['Username']);
				$uid = $_SESSION['ID'];
				$db->bindParam(":userid", $uid);// $_SESSION['UID']);
				$db->execute();
			}
			catch(PDOException $e)
			{
				echo "Cannot upload -> " . $e->getMessage() . "<br />\n";
			}
		}
		else
		{
			echo "No image sent";
		}
	}
	else
	{
		echo "Please Login";
	}