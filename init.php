<?php
session_start();

define("USER_NAME_CONNECT", "root");
define("USER_PASSWORD_CONNECT", "9ZOYcLpeEb8y1Zmr");
define("PATH_UPLOADS_IMAGE", "uploads/");
define("HOST_FOR_CONNECT", "localhost");
define("CURRENT_DATABASE", "1021437-yeticave-12");

date_default_timezone_set("Europe/Moscow");

require_once "mysql_connect.php";
require_once "functions.php";

// запрос категорий
$sql_categories = "SELECT `name`, `code`, `id` FROM `categories`";
// выполнение запроса
$result_categories = mysqli_query($con, $sql_categories);
// получение двухмерного массива категорий
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
