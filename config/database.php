<?php
    require_once("config.php");
    //Create Database
    try {
        $db = $conn->prepare($data_query);
        $db->execute();
    }
    catch(PDOException $e)
    {
        echo "Create Database failed: " . $e->getMessage() . "<br />\n";
    }
    //Create User Table
    try {
        $db = $conn->prepare($table_user_query);
        $db->execute();
    }
    catch(PDOException $e)
    {
        echo "Create table failed: ::USERS:: " . $e->getMessage() . "<br />\n";
    }
    //Create Image Table
    try {
        $db = $conn->prepare($table_image_query);
        $db->execute();
    }
    catch(PDOException $e)
    {
        echo "Create table failed: ::IMAGE:: " . $e->getMessage() . "<br />\n";
    }
    //Create Likes Table
    try {
        $db = $conn->prepare($table_likes_query);
        $db->execute();
    }
    catch(PDOException $e)
    {
        echo "Create table failed: ::LIKES:: " . $e->getMessage() . "<br />\n";
    }
    //Create comment Table
    try {
        $db = $conn->prepare($table_comment_query);
        $db->execute();
    }
    catch(PDOException $e)
    {
        echo "Create table failed: ::COMMENT:: " . $e->getMessage() . "<br />\n";
    }
?>
