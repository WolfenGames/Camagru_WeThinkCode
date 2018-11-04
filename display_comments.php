<?php
    require_once("config/config.php");
    if (isset($_POST['ID']))
    {
        global $conn;
        try
        {
            $query = "SELECT * FROM `camagru`.`comment` WHERE `img_id` = :id;";
            $stmt = $conn->prepare($query);
            $id = $_POST['ID'];
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $stmt->SetFetchMode(PDO::FETCH_ASSOC);
            foreach($res = $stmt->fetch() as $key=>$val)
            {
                echo "<xmp class='comment' id='".$val['ID'].">" . $val['comment'] . ".</xmp>";
            }
        }
        catch (PDOException $e)
        {
            echo "Cant fetch comments -> " . $e->getMessage();
        }
    }