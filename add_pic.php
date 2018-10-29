<?php
    require_once("setup/config.php");
    $img_dat = $_POST['img'];
    $query = "INSERT INTO `camagru`.`images` (`image_data`, `image_name`, `user_id`) VALUES (
        '$img_dat',
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