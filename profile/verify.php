<?php
    require_once("../config/database.php");

    if (isset($_GET['verify']))
    {
        try
        {
            $key = $_GET['verify'];
            $query = "UPDATE `camagru`.`users` SET `isVerified` = 1 WHERE `verifyKey` = :verkey;";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":verkey", $key);
			$stmt->execute();
			header("Location: ../?message=Whoohoo");
        }
        catch (PDOException $e)
        {
            echo "Cant Verify -> " . $e->getMessage();
        }
    }