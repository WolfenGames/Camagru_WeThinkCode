<?php
    echo ">>>?????>>>> \n";
    var_dump($_POST);
    echo ">>>?????>>>> \n";
    foreach($_POST as $key=>$value)
    {
        var_dump($key);
        echo "<br />";
        var_dump($value);
        echo "<br />";
    }
    $query = "INSERT INTO `image` VALUES (``";
    //header("Location: ./");