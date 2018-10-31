<?php
	require_once("../profile/functions.php");
	session_start();
	if (isset($_POST))
	{
		if (isset($_SESSION['Username']))
		{
			like($_POST['ID']);
		}
	}