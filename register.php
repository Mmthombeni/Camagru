<?php

include_once("config/database.php");

$handler = NULL;
$status = NULL;

try{
    $handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
    $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo "Connection Failed: " . $e->getMessage();
}

if(isset($_POST['submit']))
{
    $name = ft_escape_str($_POST['name']);
    $username = ft_escape_str($_POST['username']);
    $email = ft_escape_str($_POST['email']);
    $pass = ft_escape_str($_POST['password']);
    $verified = FALSE;
    $code = substr(md5(mt_rand()),0,15);
    
    if (!empty($name) && !empty($username) && !empty($email) && !empty($pass)){

        $encryppass = password_hash($pass, PASSWORD_BCRYPT);
        try{
            $select = $handler->prepare("SELECT email FROM `verify` WHERE email = :email");
            $select->bindparam(':email', $email);
            $select->execute();
            $userRow = $select->fetch(PDO::FETCH_ASSOC);
            if (!$userRow){
                $select = $handler->prepare("SELECT username FROM `verify` WHERE username = :username");
                $select->bindparam(':username', $username);
                $select->execute();
                $userRow = $select->fetch(PDO::FETCH_ASSOC);
                if (!$userRow){
                    $insert = $handler->prepare("INSERT INTO `verify` (name, username, email, password, code, verified)
                    VALUES (:name, :username, :email, :password, :code, :verified)");
                    $insert->bindParam(':name',$name);
                    $insert->bindParam(':username',$username);
                    $insert->bindParam(':email',$email);
                    $insert->bindParam(':password',$encryppass);
                    $insert->bindParam(':code',$code);
                    $insert->bindParam(':verified', $verified);
                    $insert->execute();
                
                    $to=$email;
                    $subject="Activation Code For Camagru";
                    $headers = "From: Camagru <admin@camagru.com>\r\n". 
                    "MIME-Version: 1.0" . "\r\n" . 
                    "Content-type: text/html; charset=UTF-8" . "\r\n";
                    $body='Your Activation Code is '.$code.' Please Click On This Link
                        <a href="http://'. $_SERVER['HTTP_HOST'] .'/camagru/verify.php?id='.$code.'">Verify.php?id='.$code.'</a>to activate your account.';
                    if (mail($to,$subject,$body,$headers)){
                        $status = "Activation Code Sent, Please Check Your Emails To Verify Your Account. If you don't receive this message please check your junk folder.";
                        $_POST["name"] = "";
                        $_POST["username"] = "";
                        $_POST["email"] = "";
                    }else
                        $status = "Could not send email.";
                }
                else
                    $status = "username already exist!";
            }
            else
                $status = "email already exist!";
        }catch(PDOException $e){
            echo "Connection Failed: " . $e->getMessage();
        }
    }
    else
        $status = "Fields incomplete";
}

?>

<!DOCTYPE html><html>
     <head>
        <link rel="stylesheet" href="./style.css">
        <meta charest="UTF-8">
        <title>Camagru - Register Page</title>
    </head>
    <body>
    <nav class="nav_bar">
            <div class="left">
            <h4>CAMAGRU</h4>
            </div>
            <div class="right">
            <ul>
                <li><a href="login.php">login</a></li>
        		<li><a href="gallery.php">Gallery</a></li>
    		</ul>
            </div>
        </nav>
        <div class="container">
            <div class="main">
                <form action="register.php" method="post">
                    <?php
                    if ($status)
                        echo $status."<br/>";
                    ?>
                    Full Name:<br />
                        <input name="name" value="<?php if(isset($_POST["name"])) echo $_POST["name"]; ?>" required/><br />
	                Username:<br />
                        <input name="username" value="<?php if(isset($_POST["username"])) echo $_POST["username"]; ?>" required/><br />
	                Password:<br />
                        <input name="password" value="" type="password" id="pass" required/><br />
                    Retype Password:<br />
                        <input name="repasswd" value="" type="password" id="pass2" required onfocusout="varpass()"/><br />
                    Email:<br />
                        <input name="email" value="<?php if(isset($_POST["email"])) echo $_POST["email"]; ?>" required/><br />
                    <input type="submit" name="submit" value="OK" id="register"/>
                        <br />
                        <br />
                    Already have an account?
                        <br/>
        		    <li><a href="login.php">Login</a></li>
        		    <li><a href="forgot.php">forgot Password</a></li>
                </form>
        <script>
            function varpass(){
            var pass = document.getElementById("pass");
            var pass2 = document.getElementById("pass2");
            if ((pass.value != pass2.value))
            {
                pass2.style.borderColor = "red";
                pass2.value = "";
            }
            else if (pass2.value == "" || pass.value == "")
                pass2.style.borderColor = "red";
            else
            {
                pass2.style.borderColor = "green";
                pass.style.borderColor = "green";
            }
            };
        </script>
        </div>
</div>
</body>
<footer>
<i>&copy; mmthombe</i>
     Camagru 
</footer>
</html>

