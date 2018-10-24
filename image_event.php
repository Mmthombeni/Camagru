<?php
include_once('config/database.php');

try
{
    $handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
    $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $username = $_SESSION['logged-in'];
    
    if (!isset($_POST['imgid'])){
        header("Location: gallery.php");
    }
    
    if (isset($_POST["like"]))
    {
        $getLikes = $handler->prepare("SELECT likes FROM images WHERE id=:id");
        $cols = array(':id' => $_POST["imgid"]);
        $getLikes->execute($cols);
        $imageRow = $getLikes->fetch();

        $likeAdd = $handler->prepare("UPDATE images SET likes=:likes WHERE id=:id");
        $cols = array(':likes' => $imageRow['likes'] + 1, ':id' => $_POST["imgid"]);
        $likeAdd->execute($cols);
        
        $likeIn = $handler->prepare("insert into likes"
                . "(image_id, username) VALUES (:imid, :user)");
        $colsIn = array(':imid' => $_POST['imgid'],
                        ':user' => $username);
        $likeIn->execute($colsIn);
        $notify = $handler->prepare("SELECT email, notification FROM `verify`, `images` WHERE images.id=:id AND verify.id=images.userID");
        $notify->bindparam(':id', $_POST["imgid"]);
        $notify->execute();
        
        $userdata = $notify->fetchAll()[0];
        if($userdata['notification']){
            $email = $userdata['email'];
            $to=$email;
            $subject="Notification (like)";
            $headers = "From: Camagru <admin@camagru.com>\r\n". 
                        "MIME-Version: 1.0" . "\r\n" . 
                        "Content-type: text/html; charset=UTF-8" . "\r\n";
            $body='Your Your image just got a new like. Login to see more information';
            if (mail($to,$subject,$body,$headers)){
                 "Picture liked";
            }
        }
        
        header("Location: gallery.php");
    }
    else if (isset($_POST["comment"]))
    {
        require_once 'comment.php';
        echo 'User commented';
    }
    else if (isset($_POST["delete"]))
    {
        $select = $handler->prepare("SELECT image_url FROM `images` WHERE id=:id");
        $select->bindparam(':id', $_POST["imgid"]);
        $select->execute();
        $img = $select->fetchAll()[0];
        $img['image_url'];
        unlink($img['image_url']);
        
        $imageDelete = $handler->prepare("DELETE FROM `images` WHERE id=:id;
                                         DELETE FROM `likes` WHERE image_id=:id2;
                                         DELETE FROM `comments` WHERE image_id=:id1");
        $imageDelete->bindparam(':id', $_POST["imgid"]);
        $imageDelete->bindparam(':id2', $_POST["imgid"]);
        $imageDelete->bindparam(':id1', $_POST["imgid"]);
        $imageDelete->execute();

        
        header("Location: gallery.php");
        echo 'User deleted';
    }

    echo $_POST["imgid"];
}
catch(PDOException $e){
    echo "Connection Failed: " . $e->getMessage();
}

