<?php
include_once('config/database.php');

if(isset($_POST['submit']))
{
    $pass = ft_escape_str($_POST['password']);
    
    if(!empty($pass)){

        $encryppass = password_hash($pass, PASSWORD_BCRYPT);
        try
        {
            $handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
            $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $insert = $handler->prepare("UPDATE `verify` SET verify.password = :password WHERE verify.code LIKE :code");
            $insert->bindParam(':password',$encryppass);
            $insert->bindParam(':code', $_POST['code']);
            if ($insert->execute()){
                $code = $_GET['id'];
                echo "execute done";
                echo $_GET['id'];
                
                header("Location: login.php");
                exit();
            }
            else
                echo 'query error';
        }
        catch(PDOException $e){
            echo "Connection Failed: " . $e->message();
        }
    }else
        echo "feild is empty";
}
?>

<!DOCTYPE html><html>
<head>
        <link rel="stylesheet" href="./style.css">
        <style>
            body {
                color: white;
                background-image: url("background.jpg");
            }
        </style>
        <meta charest="UTF-8">
        <title>Camagru - Register Page</title>
    </head>
    <body>
    <div class="container">
        <div class="main">
<form action="reset.php" method="post">
	Password: <input name="password" value="" type="password" id="pass" required/><br />
    Retype Password: <input name="repasswd" value="" type="password" id="pass2" required onfocusout="varpass()"/><br />
    <input type="hidden" name="code" value="<?php echo $_GET['id']?>">
	<input type="submit" name="submit" value="OK" id="register"/>
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
</html>
