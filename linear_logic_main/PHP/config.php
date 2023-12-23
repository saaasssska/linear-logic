<?php
/* Учетные данные базы данных. (Database credentials.) 
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'users');
 
/* Попытка подключения к базе данных MySQL (Attempt to connect to MySQL database) */
// The link function in PHP creates a link for a target. 
// The link method returns true if the link is successfully created and false if not.
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Проверьте подключение (Check connection)
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>