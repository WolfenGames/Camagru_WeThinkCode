<?php
    require_once("config/database.php");
    session_start();

	if (isset($_SESSION['Username']))
	{
		if (isset($_POST['img']))
		{
			$img = $_POST['img'];
			$img = str_replace(" ", "+", $img);
			$img = str_replace("data:image/png;base64,", "", $img);
			$img = base64_decode($img);
			$img = imagecreatefromstring($img);
			imagepng($img, 'save.png');
			if ($_POST['flowy'] == 'true')
			{
				$stick = imagecreatefrompng("stickers/flogo.png");
				$img = imagecreatefrompng('save.png');
				imagealphablending($img, true);
				imagesavealpha($img, true);
				$stick = imagescale($stick, 630, 100);
				imagesavealpha($stick, true);
				imagecopy($img, $stick, 100, 480, 0, 0, 630, 100);
				imagepng($img, 'save.png');
			}if ($_POST['corner'] == 'true')
			{
				$stick = imagecreatefrompng("stickers/corner.png");
				$img = imagecreatefrompng('save.png');
				imagealphablending($img, true);
				imagesavealpha($img, true);
				$stick = imagescale($stick, 640, 640);
				imagesavealpha($stick, true);
				imagecopy($img, $stick, 0, 0, 0, 0, 640, 640);
				imagepng($img, 'save.png');
			}if ($_POST['grand'] == 'true')
			{
				$stick = imagecreatefrompng("stickers/grand.png");
				$img = imagecreatefrompng('save.png');
				imagealphablending($img, true);
				imagesavealpha($img, true);
				$stick = imagescale($stick, 400, 100);
				imagesavealpha($stick, true);
				imagecopy($img, $stick, 320, 0, 0, 0, 630, 100);
				imagepng($img, 'save.png');
			}
			$img = base64_encode(file_get_contents('save.png'));
			if (empty($img))
				header("Location: ./?message=No Image Sent");
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
			}
			catch(PDOException $e)
			{
				echo "Cannot upload -> " . $e->getMessage() . "<br />\n";
			}
			unlink('save.png');
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