<?php
    include_once('config/database.php');

    $user = NULL;
    if(isset($_SESSION['logged-in'])){
        $user =  $_SESSION['logged-in'];
    }
     try
        {
            $handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
            $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $select = $handler->prepare("SELECT * FROM `images` ORDER BY creation_date DESC");
            $select->execute();
            $userRow = $select->fetchAll();
            
            $getLikes = $handler->prepare("SELECT * FROM `likes`");
            $getLikes->execute();
            $likeRow = $getLikes->fetchAll();
        }
        catch(PDOException $e){
            echo "Connection Failed: " . $e->getMessage();
        }
?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="style.css">
        <style>
            body {
                color: black;
            }
        </style>
        <meta charest="UTF-8">
        <title>Camagru - Gallery Page</title>
    </head>
    <body>
        <nav class="nav_bar">
            <div class="left">
            <h4>CAMAGRU</h4>
            </div>
            <div class="right">
                <?php
                    if (isset($_SESSION['logged-in'])){
                        echo '<ul>
                            <li><a class="active" href="#home">loggin: '. $_SESSION['username'] .'</a></li>
                            <li><a href="home.php">Home</a></li>
                            <li><a href="signout.php">Logout</a></li>
                            </ul>';
                    }else
                        echo '<ul>
                            <li><a class="active" href="#home"">Gallery</a></li>
                            <li><a href="login.php">Login</a></li>
                            <li><a href="register.php">Register</a></li>
                            </ul>';
                ?>
            </div>
        </nav>
        <div class="container">
            <div class="main">
                <?php
                    $count = 0;
                    echo '<div class="row">';

                    $page = 0;
                    if (isset($_GET['page']) && !empty($_GET['page']))
                        $page = (INT)$_GET['page'];
                    
                    $totalNumPics = $select->rowCount();
                    $i = 0;
                    $x = 0;
                    $y = 0;
                    foreach ($userRow as $value) {
                        if($x < 5){
                            if ($page === $y){
                                $disabled = '';
            
                                foreach ($likeRow as $like)
                                {
                                    if ($like['image_id'] == $value['id'] && $like['username'] == $user){
                                        $disabled = "disabled";
                                    }
                                }

                                if ($x === 0)
                                    echo'<ul>
                                            <li><a href="gallery.php?page='. ($page - $y) .'"><< '. ($page - $y) .'</a></li>
                                            <li><a href="gallery.php?page='. ($y + 1)  .'">>> '. ($y + 1) .'</a></li>
                                        </ul>';
                        
                                if ($count % 3 == 0)
                                    echo '</div><div class="row">';
                                echo '<div class="column"> Likes: ' . $value['likes'] . '<br/>
                                        <img src="' . $value['image_url'] . '"><br/>
                                    <form action="image_event.php" method="post">';
                                if (isset($_SESSION['logged-in'])){
                                    echo '<input type="hidden" name="imgid" value="' . $value['id'] .'"/>';
                                    echo '<input type="hidden" name="imgurl" value="' . $value['image_url'] .'"/>';
                                    echo '<input type="submit" ' . $disabled . ' name="like" value="Like"/>';
                                    echo '<input type="submit" name="comment" value="Comment"/>';
                                    if ($value['userID'] == $_SESSION['logged-in'])
                                    echo '<input type="submit" name="delete" value="Delete"/>';
                                }
                                echo '</form></div>';
                                $count = $count + 1;
                            }
                            $x++;
                        }else{
                            $x = 0;
                            $y++;
                        }
                        $i++;
                    }
                    if ($page > $y)
                    echo'<ul>
                            <li><a href="gallery.php"><< Back to page1</a></li>
                        </ul>';
                    echo ' </div>';
                ?>
            </div>
           
        </div>
        <footer>
        <i>&copy; mmthombe</i>
            Camagru 
        </footer>
    </body>
</html>