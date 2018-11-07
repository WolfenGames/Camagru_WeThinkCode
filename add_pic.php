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
				$noname = !empty($_POST['title']) ? $_POST['title'] : time();
				$db->bindParam(":uname", $noname);
				$uid = $_SESSION['UID'];
				$db->bindParam(":userid", $uid);
				$db->execute();
				//header("Location: ./?message=Snap Uploaded");
			}
			catch(PDOException $e)
			{
				echo "Cannot upload -> " . $e->getMessage() . "<br />\n";
			}
		}
		else if (isset($_POST["uploadImage"]))
		{
			$file_name = $_FILES['upload']['name'];
            $file_tmp = $_FILES['upload']['tmp_name'];
            $file_type = $_FILES['upload']['type'];       
			
			move_uploaded_file($file_tmp,$file_name);
			$file = file_get_contents($file_name);
			
			$img = base64_encode($file);
			$img = str_replace('data:image/png;base64,', '', $img);
			$img = str_replace(' ', '+', $img);
			unlink($file_name);
			
			$query = "INSERT INTO `camagru`.`images` (`image_data`, `image_name`, `user_id`) VALUES (
				'$img',
				:uname,
				:userid
			)";
			try {
				$db = $conn->prepare($query);
				$noname = !empty($_POST['title']) ? $_POST['title'] : time();
				$db->bindParam(":uname", $noname);
				$uid = $_SESSION['UID'];
				$db->bindParam(":userid", $uid);
				$db->execute();
				header("Location: ./?message=Image uploaded");
			}
			catch(PDOException $e)
			{
				echo "Cannot upload -> " . $e->getMessage() . "<br />\n";
			}

		}
		else
		{
			header("Location: ./?message=No Image Sent");
		}
	}
	else
	{
		header("Location: ./?message=Please Login");
	}