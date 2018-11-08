<?php

    $conn = NULL;
    $username = "root";
    $password = "123asd";
    $database = "camagru";
    $host = "127.0.0.1";
    $port = "";

    require_once("table.php");
    try {
        $conn = new PDO("mysql:host=$host;", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch(PDOException $e)
    {
        echo "Connection failed: " . $e->getMessage() . "<br />\n";
        //die();
    }
?>