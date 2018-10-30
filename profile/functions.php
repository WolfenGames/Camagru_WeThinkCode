<?php
    require_once("config/config.php");

    function login($email, $pass)
    {
        global $conn;
        $pass = hash("sha512", $pass);
        $query = "SELECT * FROM `camagru`.`users` WHERE `password` = :pass LIMIT 1;";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":pass", $pass);
        $stmt->execute();
        $stmt->SetFetchMode(PDO::FETCH_ASSOC);
        $user = $stmt->fetch();
        if ($user['isVerified'] == 0)
            return false;
        else
        {
            $_SESSION['Username'] = $user['username'];
            $_SESSION['Email'] = $user['email'];
            $_SESSION['UID'] = $user['ID'];
            return true;
        }
    }

    function logout()
    {
        session_destroy();
        header("Location: ./");
    }

    function resend_verify($email)
    {
        global $conn;
        try
        {
            $key = hash("sha512", $email . time());
            $query = "UPDATE `camagru`.`users` SET `verifyKey` = :vkey, `isVerified` = 0 WHERE `email` = :email;";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(":vkey", $key);
            $stmt->bindParam(":email", $email);
            $stmt->execute();
            send_verify($email, $key);
        }
        catch (PDOException $e)
        {
            echo "Can not resend verification email -> " . $e->getMessage();
        }
    }

    function send_verify($email, $key)
    {
        $from = "admin@camagru.com";
        $to = $email;
        $subject = "Verify Camagru Account";
        $message = "<html><body>";
        $message .= "Please click on the following link to allow us to activate you account\n";
        $message .= "<a href='http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']."/../verify.php?verify=".$key."'><p>Click me!!</p></a>";
        $message .= "</body></html>";
        $headers = "From:" . $from . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
        $headers .= "X-Mailer: PHP/" . PHP_VERSION;
        mail($to,$subject,$message, $headers);
    }

    function delete_account($email, $pass)
    {
        global $conn;
        $pass = hash("sha512", $pass);
        $query = "DELETE FROM `camagru`.`users` WHERE `password` = :pass LIMIT 1;";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":pass", $pass);
        $stmt->execute();
    }

    function register($login, $email, $pass, $veripass)
    {
        global $conn;
        if ($pass == $veripass)
        {
            try
            {
                $pass = hash("sha512", $pass);
                $query = "INSERT INTO `camagru`.`users` (`username`, `email`, `password`, `verifyKey`) VALUES (:uname, :email, :pass, :vkey);";
                $key = hash("sha512", $email . time());
                $stmt = $conn->prepare($query);
                $stmt->bindParam(":uname", $login);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":pass", $pass);
                $stmt->bindParam(":vkey", $key);
                $stmt->execute();
                send_verify($email, $key);
            }
            catch (PDOException $e)
            {
                echo "Failed to register -> " . $e->getMessage();
            }
        }
        else
            echo "Password is invalid!\n";
    }