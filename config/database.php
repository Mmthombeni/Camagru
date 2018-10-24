<?php
$DB_NAME = "camagru";
$DB_DSN = "mysql:host=". $_SERVER['SERVER_NAME'];
//$DB_DSN = 'mysql:host=127.0.0.1';
$DB_USER = "root";
$DB_PASSWORD = "samsung";
session_start();

function ft_escape_str($string){
    return (filter_var($string, FILTER_SANITIZE_STRING));
}
?>