<?php
	require_once("config/database.php");
	require_once("profile/functions.php");
	session_start();
	
	if (isset($_POST['PostComment']))
	{
		makeComment(addslashes($_POST['comment']), $_POST['id']);
		$_GET['ID'] = $_POST['id'];
	}

   	if (isset($_GET['ID']) && isset($_SESSION['Username']))
	{
		global $conn;
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Camagru of all Camagru's</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="css/index.css" /><link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
</head>
<body>
	<?php
		require_once("profile/functions.php");
		session_start();
		?>
		<nav class="navbar navbar-expand-sm">
		<div class="container-fluid">
			<div class="navbar-header">
			<h3><a href="./">Camagru of all Camagru's<a><h3>
			<?php
				if (isset($_SESSION['Username']))
				{
					?>
					<form method="POST" action="logout.php">
						<button class="btn btn-success navbar-btn" type="submit">Logout</button>
					</form>
					<?php
				}
			?>
		</div>
	</nav>

	<div class="container">
		<div class="row">
			<div class="col-sm-7">
				<!--Display image-->
				<?php
				try
				{
					$query = "SELECT * FROM `camagru`.`images` WHERE `ID`=:id;";
					$stmt = $conn->prepare($query);
					$stmt->bindParam(":id", $_GET['ID']);
					$stmt->execute();
					$stmt->SetFetchMode(PDO::FETCH_ASSOC);
					$pic = $stmt->fetch();
					if (empty($pic['image_data']))
						header("Location: ./?message=NoImage");
					echo "<p style='width: 100%; background-color: #EF944C;'>";
					$query = "SELECT `username` FROM `camagru`.`users` WHERE `ID`=:id;";
					$stmt = $conn->prepare($query);
					$stmt->bindParam(":id", $pic['user_id']);
					$stmt->execute();
					$stmt->SetFetchMode(PDO::FETCH_ASSOC);
					$user = $stmt->fetch();
					if ($user)
						echo $user['username'] . ' - ' . $pic['image_name'];
					else
						echo "User Deleted" . ' - ' . $pic['image_name'];
					echo "</p>";
					echo "<img style='width: 100%; height: auto;' src='data:image/png; base64, " . $pic['image_data'] . "'>";
				}
				catch (PDOException $e)
				{
					echo $e->getMessage();
				}
				?>
				<form method="POST" action="display_comments.php" enctype="multipart/form-data">
						<input type="text" name='comment' id='comment' style='width: 100%;'>
						<input type="hidden" name='id' value="<?php echo $_GET['ID'] ?>">
						<input type='submit' name='PostComment' style='width: 100%;' id='uploadImage' value="Post Comment">
				</form>
			</div>
			<div class="col-sm-5 comment-column">
				<!--Display-comments-->
				<?php
					//comment section
					try
					{
						$query = "SELECT * FROM `camagru`.`comments` WHERE `img_id` = :id ORDER BY `date` DESC;";
						$stmt = $conn->prepare($query);
						$id = $_GET['ID'];
						$stmt->bindParam(":id", $id);
						$stmt->execute();
						$stmt->SetFetchMode(PDO::FETCH_ASSOC);
						foreach($stmt->fetchAll() as $key => $val)
						{
							echo "<div class='comment'>";
							echo "<p style='color: #232974;'>";
							$query = "SELECT `username` FROM `camagru`.`users` WHERE `ID`=:id;";
							$stmt = $conn->prepare($query);
							$stmt->bindParam(":id", $val['commenter']);
							$stmt->execute();
							$stmt->SetFetchMode(PDO::FETCH_ASSOC);
							$user = $stmt->fetch();
							if ($user)
								echo $user['username'];
							else
								echo "User Deleted";
							echo "</p>";
							echo "<pre class='comment' id='".$val['ID']."'>" . $val['comment'] . "</pre>";
							echo "</div>";
						}
					}
					catch (PDOException $e)
					{
						echo "Cant fetch comments -> " . $e->getMessage();
					}
				?>
			</div>
		</div>
	</div>
</body>
</html>
<?php
	}
	else
		header("Location: ./");
?>