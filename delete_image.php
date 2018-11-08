<?php
    require_once("config/database.php");
    if (isset($_POST['ID']))
    {
		$query = "DELETE FROM `camagru`.`images` WHERE `ID` = :id;";
		$query2 = "DELETE FROM `camagru`.`likes` WHERE `likes` = :id;";
		$query3 = "DELETE FROM `camagru`.`comments` WHERE `img_id` = :id;";
        try
        {
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $_POST['ID']);
			$stmt->execute();
			$stmt2 = $conn->prepare($query2);
			$stmt2->bindParam(":id", $_POST['ID']);
			$stmt2->execute();
			$stmt3 = $conn->prepare($query3);
			$stmt3->bindParam(":id", $_POST['ID']);
			$stmt3->execute();
        }
        catch (PDOException $e)
        {
            echo "Cannot delete image -> " . $e->getMessage();
        }
    }