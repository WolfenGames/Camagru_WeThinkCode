<?php
    $data_query = "CREATE DATABASE IF NOT EXISTS `camagru`;";
    
    $table_image_query = "CREATE TABLE IF NOT EXISTS `camagru`.`images` (
        `ID` INT PRIMARY KEY AUTO_INCREMENT,
        `image_data` BLOB(4294967295) NOT NULL,
        `image_name` Varchar(100) DEFAULT 'NO NAME' NOT NULL,
        `user_id` INT
	);";
	
    $table_likes_query = "CREATE TABLE IF NOT EXISTS `camagru`.`likes` (
        `ID` INT PRIMARY KEY AUTO_INCREMENT,
        `ref_id` INT NOT NULL,
        `likes` INT NOT NULL
	);";
	
    $table_comment_query = "CREATE TABLE IF NOT EXISTS `camagru`.`comments` (
        `ID` INT PRIMARY KEY AUTO_INCREMENT,
        `img_id` INT NOT NULL,
        `commenter` Varchar(1024) NOT NULL DEFAULT 'anonymous',
        `comment` Varchar(1024) NOT NULL DEFAULT 'This is a comment',
        `Date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
	);";
	
    $table_user_query = "CREATE TABLE IF NOT EXISTS `camagru`.`users` (
        `ID` INT PRIMARY KEY AUTO_INCREMENT,
        `username` Varchar(1024) NOT NULL,
        `password` Varchar(1024) NOT NULL,
        `email` Varchar(1024) NOT NULL UNIQUE,
        `isVerified` INT(1) DEFAULT 0,
		`emailfpref` BOOLEAN NULL DEFAULT FALSE,
        `verifyKey` Varchar(1024) NOT NULL DEFAULT 'HelloWorld' UNIQUE
    );";
