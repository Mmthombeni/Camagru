<?PHP
    include_once("config/setup.php");
    include_once("config/database.php");
    
    
    $name = ft_escape_str($_POST['username']);
    $user_id = $_SESSION['logged-in'];
    
    $image_name = $name . mktime();
    $dir = "./img/uploads/$image_name.png";
    $image = $_POST['image'];
    $image2 = $_POST['image2'];

    $image = str_replace('data:image/png;base64, ', '', $image);
    $image = str_replace(' ', '+', $image);
    $data = base64_decode($image);

    $image2 = str_replace('data:image/png;base64, ', '', $image2);
    $image2 = str_replace(' ', '+', $image2);
    $data = base64_decode($image2);

    merge_img($dir, $image, $image2);

    function merge_img($path, $img, $img2){
        $dest = imagecreatefrompng($img);
        $src = imagecreatefrompng($img2);

        imagecolortransparent($src, imagecolorat($src, 0, 0));
        imagecopymerge($dest, $src, 0, 0, 0, 0, $_POST['image_width'], $_POST['image_height'], 100);
        imagepng($dest, $path, 9);

        imagedestroy($dest);
        imagedestroy($src);
        echo "Done";
    }

    try{
        $handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
        $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $insert = $handler->prepare("INSERT INTO `images` (image_url, userID)
        VALUES(:image_url, :userID)");
        $insert->bindParam(':image_url', $dir);
        $insert->bindParam(':userID', $user_id);
        $insert->execute();
    } catch (PDOException $e) {
        echo "error: " .$sql . "<br>" . $e->getMessage();
    }
?>