<?php
// Инициализировать сеанс (Initialize the session)
session_start();
 
// Удалить все переменные сеанса (Unset all of the session variables)
$_SESSION = array();
 
// Уничтожить сессию. (Destroy the session.)
session_destroy();
 
// Перенаправление на страницу входа (Redirect to login page)
header("location: login.php");
exit;
?>