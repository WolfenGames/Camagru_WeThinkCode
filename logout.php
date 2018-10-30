<?php
	session_start();
	require_once("profile/functions.php");
	logout();
	header("Location: ./");