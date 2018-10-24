<?php
	include_once('config/database.php');

	$handler = NULL;
	$userdata = NULL;
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
		if(!($userdata=$select->fetchAll()))
			header ("Location: login.php");
	}
	else{
		header ("Location: login.php");
	}
	$select = $handler->prepare("SELECT * FROM `images` WHERE userID = :userID ORDER BY creation_date DESC");
	$select->bindParam(":userID" , $_SESSION["logged-in"]);
	$select->execute();
	$userRow = $select->fetchAll();

	if(isset($_GET['notify']) && !empty($_GET['notify'])){
		if($_GET['notify'] === "disable"){
			$notifca = FALSE;
			$insert = $handler->prepare("UPDATE verify SET notification = :notification WHERE id = :id;");
            $insert->bindParam(":notification" , $notifca);
            $insert->bindParam(":id" ,  $_SESSION["logged-in"]);
            $insert->execute();
		}
		else if($_GET['notify'] === "enable"){
			$notifca = TRUE;
			$insert = $handler->prepare("UPDATE verify SET notification = :notification WHERE id = :id;");
            $insert->bindParam(":notification" , $notifca);
            $insert->bindParam(":id" ,  $_SESSION["logged-in"]);
            $insert->execute();
		}
		header("Location: home.php");
	}
?>

<!DOCTYPE html>
<html lang='en'>
    <head>
        	<meta charest="UTF-8">
        	<title>Camagru - Home Page</title>

        	<link rel="stylesheet" href="cam.css" type="text/css" media="all">
			
    </head>
    <body>
		<nav class="nav_bar">
			<div class="left">
            	<h4>CAMAGRU</h4>
            </div>
            <div class="right">
			<ul>
        		<li><a class="active" href="#home">login: <?php if (isset($_SESSION['username'])) echo $_SESSION['username']; else echo "username"; ?></a></li>
        		<li><a href="gallery.php">Gallery</a></li>
        		<li><a href="signout.php">logout</a></li>
    		</ul>
            </div>
		</nav>

		<input type="hidden" name="username" id="username" value="<?php if (isset($_SESSION['username'])) echo $_SESSION['username']; else echo "username"; ?>">

		<div class ="container">	
			<div class="main">
				<div class="camera_container">
					<div class="inner">
						<button href="#" id="startbutton" class="capture-pic"> Capture </button>
						<canvas id="canvas_top"></canvas>
						<video id="video" autoplay></video>
						<canvas id="canvas"></canvas>						
					</div>
				</div>
				<input type="file" crossOrigin="Anonymous" class="form-control-file" id="upload" accept="image/png" />
				<div class="super">
					<table>
						<tr>
							<td><img src="./img_super/step.png" onclick="do_super(this.src)" alt=""></td>
							<td><img src="./img_super/spongb.png" onclick="do_super(this.src)" alt=""></td>
							<td><img src="./img_super/Awesomo.png" onclick="do_super(this.src)" alt=""></td>
							<td><img src="./img_super/glass1.png" onclick="do_super(this.src)" alt=""></td>
							<td><img src="./img_super/teye.png" onclick="do_super(this.src)" alt=""></td>
						</tr>	
					</table>
					<br/>
					<br/>
					<br/>
					<a href="profile.php?change=name">Change Name</a>
					<a href="profile.php?change=username">Change Username</a>
					<a href="profile.php?change=email">Change Email</a>
					<a href="profile.php?change=password">Change Password</a>
					<?php
						$userdata= $userdata[0];
						if($userdata['notification']){
							echo '<a href="home.php?notify=disable">Disable Notifications</a>';
						}else
							echo '<a href="home.php?notify=enable">Enable Notifications</a>';
					?>
				</div>
			</div>
			<div class="side_nav">
				<div class="pics">
					<?php
						
        				$count = 0;

        				echo '<div class="row">';

        				foreach ($userRow as $value) {
            				if ($count % 3 == 0)
                 		echo '</div><div class="row">';
            			echo '<div class="column">
                		<img src="' . $value['image_url'] . '"></div>';
            			$count = $count + 1;
        				}

        				echo ' </div>';
					?>
				</div>
			</div>
		</div>
		<footer>
		<i>&copy; mmthombe</i>
            Camagru 
		</footer>
	</body>
	<script src="camera.js"></script>
</html>
