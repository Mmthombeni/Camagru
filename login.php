<?php
    include_once('config/database.php');

    $errors = NULL;
    $handler = NULL;

    try
	{
		$handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
		$handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
	}
	catch(PDOException $e){
		echo "Connection Failed: " . $e->getMessage();
	}

    if (isset($_SESSION["logged-in"])){
		$select = $handler->prepare("SELECT * FROM `verify` WHERE id= :id");
		$select->bindParam(":id" , $_SESSION["logged-in"]);
		$select->execute();
		if($userdata=$select->fetchAll())
			header ("Location: home.php");
	}

    if(isset($_POST['submit']))
    {
        $username = ft_escape_str($_POST['username']);
        $pass = ft_escape_str($_POST['password']);
    
        if (!empty($username) && !empty($pass)){
            try
            {
                $select = $handler->prepare("SELECT * FROM `verify` WHERE username = :username AND verified = TRUE");
                $select->bindparam(':username', $username);
                $select->execute();
                $userRow = $select->fetch(PDO::FETCH_ASSOC);
                if($select->rowCount() > 0)
                {
                    if(password_verify($pass, $userRow['password']))
                    {
                        $_SESSION['logged-in'] = $userRow['id'];
                        $_SESSION['username'] = $userRow['username'];
                    }
                    else
                        $errors = "Incorrect username/password";
                }
                else
                    $errors = "Incorrect username/password";
            }
            catch(PDOException $e){
                echo "Connection Failed: " . $e->getMessage();
            }

            if(isset($_SESSION['logged-in']))
            {
                header("Location: home.php");
                exit();
            }
        }
    }
?>

<!DOCTYPE html><html><body>
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
    <nav class="nav_bar">
        <div class="left">
        <h4>CAMAGRU</h4>
        </div>
        <div class="right">
            <ul>
        		<li><a href="gallery.php">Gallery</a></li>
    		</ul>
        </div>
    </nav>
    <div class="container">
        <div class="main">
            <form action="login.php" method="post">
                <?php
                    if ($errors)
                        echo $errors."<br/>";
                ?>
                Username: <br />
                    <input name="username" value="<?php if(isset($_POST["username"])) echo $_POST["username"]; ?>" required/><br />
                Password: <br />
                    <input name="password" value="" type="password" required/><br />
                <input type="submit" name="submit" value="OK" id="login"/>
                <br /><br />
                <li><a href="forgot.php">forgot Password</a></li>
                <br/> 
                <br/>
                <br/> 
                <br/>
                Don't have an account?
                <br/>
                <li><a href="register.php">Register</a></li>
            </form>
        </div>
    </div>
    <footer>
    <i>&copy; mmthombe</i>
            Camagru 
    </footer>
</body>
</html>