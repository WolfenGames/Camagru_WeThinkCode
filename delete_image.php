<?php
    require_once("config/config.php");
    if (isset($_POST['ID']))
    {
        $query = "DELETE FROM `camagru`.`images` WHERE `ID` = :id;";
        try
        {
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":id", $_POST['ID']);
            $stmt->execute();
        }
        catch (PDOException $e)
        {
            echo "Cannot delete image -> " . $e->getMessage();
        }
    }