<?PHP
    include_once("config/setup.php");

    //include_once("login.php");
    
    
    //$passhash = ('sha256', $password);
    //$sql="select * where username='$username' AND password='$passhash'";
    //run your query using pdo ($conn->exec($sql);)
    //collect mysqli_query().sqlerror;
    /*body {             
        background-image: url("background.jpg");
    } */
?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="style.css">
        <style>
            body {             
        background-image: url("background.jpg");
            }
        </style>
        <meta charest="UTF-8">
        <title>Camagru - Index Page</title>
    </head>
    <body>
        <nav class="nav_bar">
            <div class="left">
            <h4>CAMAGRU</h4>
            </div>
            <div class="right">
            <ul>
                <li><a href="login.php">login</a></li>
                <li><a href="register.php">Register</a></li>
        		<li><a href="gallery.php">Gallery</a></li>
    		</ul>
            </div>
        </nav>

        <div class="container">
            
            <div class="main">
            </div>
            
        </div>

        <footer>
        <i>&copy; mmthombe</i>
            Camagru 
        </footer>
    </body>
</html>