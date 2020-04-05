<?php
date_default_timezone_set("Europe/Moscow");
require_once "helpers.php";
// подключение к БД
$con = mysqli_connect('localhost','root', '9ZOYcLpeEb8y1Zmr', '1021437-yeticave-12');
if ($con === false) {
    print("Ошибка подключения: " . mysqli_connect_error());
    exit();
}
// установка кодировки
mysqli_set_charset($con, "utf8");
// запрос категорий
$sql_categories = "SELECT `name`, `code` FROM `categories`";
// выполнение запроса
$result_categories = mysqli_query($con, $sql_categories);
// получение двухмерного массива категорий
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

// запрос лотов
$sql_lots = "SELECT lot.name, lot.price_start, lot.image_link, lot.created_at, lot.ends_at, category.name as category_name FROM `lots` as lot
INNER JOIN `categories` as category
ON lot.category_id = category.id
WHERE lot.ends_at > NOW()
ORDER BY `created_at` DESC LIMIT 6";
// выполнение запроса
$result_lots = mysqli_query($con, $sql_lots);
// получение двухмерного массива лотов
$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

$page_content = include_template("main.php", [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template("layout.php", [
    'main_content' => $page_content,
    'title_page' => 'Главная',
    'user_name' => 'Bogdan',
    'categories' => $categories,
]);

print($layout_content);
