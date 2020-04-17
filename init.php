<?php
session_start();

define("DB_USER", "root");
define("DB_PASSWORD", "9ZOYcLpeEb8y1Zmr");
define("PATH_UPLOADS_IMAGE", "uploads/");
define("DB_HOST", "localhost");
define("DB_NAME", "1021437-yeticave-12");
define("COUNT_ITEMS", "6");

date_default_timezone_set("Europe/Moscow");

require_once "mysql_connect.php";
require_once "functions.php";

// запрос категорий
$sql_categories = "SELECT `name`, `code`, `id` FROM `categories`";
// выполнение запроса
$result_categories = mysqli_query($con, $sql_categories);
// получение двухмерного массива категорий
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
