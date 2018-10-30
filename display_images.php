<?php

    require_once('config/config.php');

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
            echo ($i == 1) ? "<div class='col-md-4 img-block-1'>" : "<div class='col-md-4 img-block-2'>";
            echo "<img onclick='delete_image(".$v['ID'].")' style='width: 100%; height: auto;' src='data:image/png; base64, " . $v['image_data'] . "'> <br />
                <div class='img-options'>
                    <input class='btn' type='button' id='".$v['ID']."' onclick='like(this)' value='Like'>
                    <input class='btn' type='button' id='".$v['ID']."' onclick='comment(this)' value='Comment'> 
                </div>
                </div>";
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