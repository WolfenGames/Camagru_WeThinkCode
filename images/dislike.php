<?php
	require_once("../profile/functions.php");
	session_start();
	if (isset($_POST))
	{
		if (isset($_SESSION['Username']))
		{
			dislike($_POST['ID']);
		}
	}