<?php
include_once('config/database.php');

try{
    $verified = TRUE;
    $handler = new PDO($DB_DSN . ';dbname=' . $DB_NAME, $DB_USER, $DB_PASSWORD);
    $handler->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $insert = $handler->prepare("UPDATE `verify` SET verified = :verified WHERE code LIKE :code");
    $insert->bindParam(':verified', $verified);
    $insert->bindParam(':code', $_GET['id']);
    $insert->execute();
    echo "Success";
}catch(PDOException $e){
    echo "Connection Failed: " . $e->message();
}

header("Location: login.php");
exit();
?>