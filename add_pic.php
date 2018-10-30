<?php
    require_once("config/config.php");
    if (isset($_POST['img']))
    {
        $img = $_POST['img'];
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);

        $query = "INSERT INTO `camagru`.`images` (`image_data`, `image_name`, `user_id`) VALUES (
            '$img',
            'testName',
            1
        )";
        try {
            $db = $conn->prepare($query);
            $db->execute();
            echo "I have done the thing";
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