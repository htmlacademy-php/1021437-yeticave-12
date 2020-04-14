<?php
date_default_timezone_set("Europe/Moscow");
require_once "mysql_connect.php";
require_once "helpers.php";
require_once "functions.php";


$page_content = include_template("login.php", [
]);

$layout_content = include_template("layout.php", [
    'main_content' => $page_content,
    'title_page' => 'Добавление лота',
    'user_name' => 'Bogdan',
    'categories' => $categories,
    'is_auth' => $is_auth,
]);

print($layout_content);

