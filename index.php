<?php
date_default_timezone_set("Europe/Moscow");
require_once "helpers.php";
//Categories for footer and header
$categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];
//Elements
$ads = [
    [
        "name" => "2014 Rossignol District Snowboard",
        "category" => "Доски и лыжи",
        "price" => "10999",
        "url_image" => "img/lot-1.jpg",
        "expiration_date" => "2020-03-31"
    ],
    [
        "name" => "DC Ply Mens 2016/2017 Snowboard",
        "category" => "Доски и лыжи",
        "price" => "159999",
        "url_image" => "img/lot-2.jpg",
        "expiration_date" => "2020-04-06"
    ],
    [
        "name" => "Крепления Union Contact Pro 2015 года размер L/XL",
        "category" => "Крепления",
        "price" => "8000",
        "url_image" => "img/lot-3.jpg",
        "expiration_date" => "2020-04-02"
    ],
    [
        "name" => "Ботинки для сноуборда DC Mutiny Charocal",
        "category" => "Ботинки",
        "price" => "10999",
        "url_image" => "img/lot-4.jpg",
        "expiration_date" => "2020-04-03"
    ],
    [
        "name" => "Куртка для сноуборда DC Mutiny Charocal	",
        "category" => "Одежда",
        "price" => "7500",
        "url_image" => "img/lot-5.jpg",
        "expiration_date" => "2020-04-07"
    ],
    [
        "name" => "Маска Oakley Canopy",
        "category" => "Разное",
        "price" => "5400",
        "url_image" => "img/lot-6.jpg",
        "expiration_date" => "2020-04-01"
    ]
];

$page_content = include_template("main.php", [
    'categories' => $categories,
    'ads' => $ads
]);

$layout_content = include_template("layout.php", [
    'main_content' => $page_content,
    'title_page' => 'Главная',
    'user_name' => 'Bogdan',
    'categories' => $categories,
]);

print($layout_content);
