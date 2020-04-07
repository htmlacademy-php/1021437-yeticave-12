<?php
date_default_timezone_set("Europe/Moscow");
require_once "helpers.php";

$con = mysqli_connect('localhost', 'root', '9ZOYcLpeEb8y1Zmr', '1021437-yeticave-12');
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


// получение идентификатора с помощью фильтра целое число
$id_lot = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
// выборка информации о лоте
$current_lot = "SELECT lot.id, lot.name, lot.step_rate, ends_at, lot.price_start, lot.description, lot.image_link, lot.created_at, lot.ends_at, category.name as category_name
FROM `lots` as lot
INNER JOIN `categories` as category
ON lot.category_id = category.id
WHERE lot.id = '$id_lot'";
// выборка истории ставок
$sql_bids = "SELECT users.name, bids.price, bids.created_at FROM `bids` as bids
INNER JOIN `users` as users
ON bids.user_id = users.id
WHERE `lot_id` = '$id_lot'
ORDER BY bids.created_at DESC";
$result_bids = mysqli_query($con, $sql_bids);
$bids = mysqli_fetch_all($result_bids, MYSQLI_ASSOC);

if ($result_lot = mysqli_query($con, $current_lot)) {
    if (mysqli_num_rows($result_lot)) {
        $lot = mysqli_fetch_assoc($result_lot);
        // подключаем шаблон с карточкой лота
        $page_content = include_template("current_lot.php", [
            'categories' => $categories,
            'lot' => $lot,
            'bids' => $bids
        ]);
    } else {
        // подключаем шаблон с ошибкой
        $page_content = include_template("error.php", [
            'text_error' => '404 Страница не найдена'
        ]);
    }
} else {
    $page_content = include_template("error.php", [
        'text_error' => "Ошибка подключения: " . mysqli_errno($con)
    ]);
}


// собираем итоговую страницу лота
$layout_content = include_template("layout.php", [
    'main_content' => $page_content,
    'title_page' => 'Карточка лота',
    'user_name' => 'Bogdan',
    'categories' => $categories
]);

print($layout_content);
