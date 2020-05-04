<?php
session_start();

define("DB_USER", "root");
define("DB_PASSWORD", "147456");
define("PATH_UPLOADS_IMAGE", "uploads/");
define("DB_HOST", "localhost");
define("DB_NAME", "1021437-yeticave-12");
define("COUNT_ITEMS", "9");
define("MAIL", [
    "host" => "smtp.mailtrap.io",
    "port" => 2525,
    "encryption" => "tls",
    "username" => "913ac37e38ecd8",
    "password" => "64542b20aea49b",

]);
define("IMAGE_PARAMETERS", [
   "width" => 800,
    "height" => 600,
]);
define("IMAGE_QUALITY", [
    "jpeg_quality" => 60,
    "png_compression_level" => 7,
]);

date_default_timezone_set("Europe/Moscow");

require_once "mysql_connect.php";
require_once "functions.php";

// запрос категорий
$sql_categories = "SELECT `name`, `code`, `id` FROM `categories`";
// выполнение запроса
$result_categories = mysqli_query($con, $sql_categories);
// получение двухмерного массива категорий
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);
