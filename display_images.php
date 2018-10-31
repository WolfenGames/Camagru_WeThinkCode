<?php
	require_once('config/config.php');
	require_once('profile/functions.php');
    session_start();

    $query = "SELECT * FROM `camagru`.`images` ORDER BY `ID` DESC;";

    try
    {
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $i = 0;

        $result = $stmt->SetFetchMode(PDO::FETCH_ASSOC);
        foreach ($stmt->fetchAll() as $k => $v)
        {
            if ($i == 0)
            {
                echo "<div class='row'>";
            }
            echo ($i == 1) ? "<div class='col-sm-4 img-block-1'>" : "<div class='col-sm-4 img-block-2'>";
			echo "
				<p style=" . ((isset($_SESSION['Username']) && $v['image_name'] == $_SESSION['Username']) ? "'color: #F5EAB7;" : "'color: #EF944C;") . "'>" . $v['image_name'] . "</p>
				<img class='display-img' src='data:image/png; base64, " . $v['image_data'] . "'> <br />";
			if (isset($_SESSION['Username']))
			{
				echo "
					<div class='img-options'>";
				if (hasliked($v['ID']))
					echo	"<input class='btn' type='button' id='".$v['ID']."' onclick='like(this.id)' value='Like'>";
				else
					echo	"<input class='btn' type='button' id='".$v['ID']."' onclick='dislike(this.id)' value='Dislike'>";
				echo "	<input class='btn' type='button' id='".$v['ID']."' onclick='comment(this.id)' value='Comment'>";
				if (isset($_SESSION['Username']) && ($_SESSION['Username'] == $v['image_name']))
				{
					echo "<input type='button' class='btn' onclick='delete_image(".$v['ID'].")' value='Delete'>";
				}
				echo    "</div>";
			}else
			{
				echo "
					<div class='img-options'>
						<input class='btn' type='button' style='width: 100%;' value='Please login' disabled>
					</div>
				";
			}
            echo	"</div>";
            if ($i == 2)
            {
                echo "</div>";
            }
            $i++;
            if ($i > 2)
                $i = 0;
        }
        if ($i < 3)
            echo "</div>";
    }
    catch (PDOException $e)
    {
        echo "Cannot fetch pictures -> " . $e->getMessage() . "<br />";
        die();
    }
