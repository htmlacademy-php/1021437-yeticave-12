<?php
require_once "init.php";
require_once "helpers.php";
require_once "validate_functions.php";

// получение идентификатора с помощью фильтра целое число
$id_lot = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
// выборка информации о лоте
$current_lot = "SELECT 
    lot.id, 
    lot.name, 
    lot.step_rate, 
    ends_at, 
    lot.price_start, 
    lot.description, 
    lot.image_link, 
    lot.created_at, 
    lot.author_id, 
    lot.author_id, 
    lot.ends_at,
     category.name AS category_name
    FROM `lots` AS lot
    INNER JOIN `categories` AS category
    ON lot.category_id = category.id
    WHERE lot.id = '$id_lot'";
// выборка истории ставок
$sql_bids = "SELECT 
    users.name, 
    bids.price, 
    bids.created_at, 
    users.id 
    FROM `bids` AS bids
    INNER JOIN `users` AS users
    ON bids.user_id = users.id
    WHERE `lot_id` = '$id_lot'
    ORDER BY bids.created_at DESC";
$result_bids = mysqli_query($con, $sql_bids);
$bids = mysqli_fetch_all($result_bids, MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["user"])) {
    // подготовка суммы ставки
    $bet_sum = get_escape_string($con, post_value("cost"));
    $lot = mysqli_fetch_assoc(mysqli_query($con, $current_lot));
    // узнаем есть ли ставки по этому лоту
    $max_value_bird = mysqli_query(
        $con,
        "SELECT MAX(price) AS `max_price` FROM `bids` WHERE `lot_id` =" . $id_lot . " LIMIT 1"
    );
    $max_value = mysqli_fetch_assoc($max_value_bird);
    $new_sum = get_max_price_lot($max_value["max_price"], $lot["step_rate"], $lot["price_start"]);

    $errors = validate(
        [
            "cost" => post_value("cost"),
        ],
        [
            "cost" => [
                not_empty("Укажите сумму больше или равной минимальной"),
                str_length_gt(12),
            ],
        ]
    );

    if ($new_sum <= $bet_sum && empty($errors)) {
        $page_content = create_bet($bet_sum, $con, $id_lot) ?
            header("Location: lot.php?id=" . $id_lot) :
            $page_content = include_template("error.php", [
            "code_error" => "404",
            "text_error" => "Ошибка вставки: " . mysqli_errno($con),
        ]);
    } elseif ($errors) {
        $page_content = include_template("current_lot.php", [
            "categories" => $categories,
            "lot" => $lot,
            "bids" => $bids,
            "bet_sum" => $bet_sum,
            "text_error" => $errors["cost"],
        ]);
    } else {
        $page_content = include_template("current_lot.php", [
            "categories" => $categories,
            "lot" => $lot,
            "bids" => $bids,
            "bet_sum" => $bet_sum,
            "text_error" => "Сумма ставки меньше минимальной или введено некорректное значение",
        ]);
    }
} else {
    $result_lot = mysqli_query($con, $current_lot);
    if (mysqli_num_rows($result_lot)) {
        $lot = mysqli_fetch_assoc($result_lot);
        $page_content = include_template("current_lot.php", [
            "categories" => $categories,
            "lot" => $lot,
            "bids" => $bids,
        ]);
    } else {
        $page_content = include_template("error.php", [
            "code_error" => "404",
            "text_error" => "Не удалось найти страницу с лотом №-" . "<b>" . $id_lot . "</b>",
        ]);
    }
}

// собираем итоговую страницу лота
$layout_content = include_template("layout.php", [
    "main_content" => $page_content,
    "title_page" => "Страница лота",
    "user_name" => session_user_value("name", ""),
    "categories" => $categories,
]);

print($layout_content);
