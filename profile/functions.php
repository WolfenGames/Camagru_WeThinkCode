<?php
    require_once("../config/config.php");

    function login($email, $pass, $veripass)
    {
        $pass = hash("sha512", $pass);
        $veripass = hash("sha512", $veripass);
        if ($pass == $veripass)
        {
            $query = "SELECT * FROM `camagru`.`users` WHERE `password` = :pass;";
            $stmt = $conn->prepare($query);
            $stmt->bindParams(":pass", $pass);
            $stmt->execute();
            $stmt->SetFetchMode(PDO::FETCH_ASSOC);
            
        }
    }