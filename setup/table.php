<?php
    $data_query = "CREATE DATABASE `camagru`;";
    
    $table_image_query = "CREATE TABLE `camagru`.`images` (
        `ID` INT PRIMARY KEY AUTO_INCREMENT,
        `image_data` BLOB(2147483648) NOT NULL,
        `image_name` Varchar(100) DEFAULT 'NO NAME' NOT NULL,
        `user_id` INT
        );";