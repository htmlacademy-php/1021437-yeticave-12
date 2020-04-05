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
ORDER BY `created_at` DESC";
// выполнение запроса
$result_lots = mysqli_query($con, $sql_lots);
// получение двухмерного массива лотов
$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);

//Elements
//$ads = [
//    [
//        "name" => "2014 Rossignol District Snowboard",
//        "category" => "Доски и лыжи",
//        "price" => "10999",
//        "url_image" => "img/lot-1.jpg",
//        "expiration_date" => "2020-03-31"
//    ],
//    [
//        "name" => "DC Ply Mens 2016/2017 Snowboard",
//        "category" => "Доски и лыжи",
//        "price" => "159999",
//        "url_image" => "img/lot-2.jpg",
//        "expiration_date" => "2020-04-06"
//    ],
//    [
//        "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
//        "category" => "Крепления",
//        "price" => "8000",
//        "url_image" => "img/lot-3.jpg",
//        "expiration_date" => "2020-04-02"
//    ],
//    [
//        "name" => "Ботинки для сноуборда DC Mutiny Charocal",
//        "category" => "Ботинки",
//        "price" => "10999",
//        "url_image" => "img/lot-4.jpg",
//        "expiration_date" => "2020-04-03"
//    ],
//    [
//        "name" => "Куртка для сноуборда DC Mutiny Charocal	",
//        "category" => "Одежда",
//        "price" => "7500",
//        "url_image" => "img/lot-5.jpg",
//        "expiration_date" => "2020-04-07"
//    ],
//    [
//        "name" => "Маска Oakley Canopy",
//        "category" => "Разное",
//        "price" => "5400",
//        "url_image" => "img/lot-6.jpg",
//        "expiration_date" => "2020-04-01"
//    ]
//];

$page_content = include_template("main.php", [
    'categories' => $categories,
    'ads' => $lots
]);

$layout_content = include_template("layout.php", [
    'main_content' => $page_content,
    'title_page' => 'Главная',
    'user_name' => 'Bogdan',
    'categories' => $categories,
]);

print($layout_content);
