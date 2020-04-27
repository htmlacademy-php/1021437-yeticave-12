<?php
require_once "init.php";
require_once "helpers.php";

// получение идентификатора с помощью фильтра целое число
$id_lot = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);
// выборка информации о лоте
$current_lot = "SELECT lot.id, lot.name, lot.step_rate, ends_at, lot.price_start, lot.description, lot.image_link, lot.created_at, lot.author_id, lot.author_id, lot.ends_at, category.name as category_name
FROM `lots` as lot
INNER JOIN `categories` as category
ON lot.category_id = category.id
WHERE lot.id = '$id_lot'";
// выборка истории ставок
$sql_bids = "SELECT users.name, bids.price, bids.created_at, users.id FROM `bids` as bids
INNER JOIN `users` as users
ON bids.user_id = users.id
WHERE `lot_id` = '$id_lot'
ORDER BY bids.created_at DESC";
$result_bids = mysqli_query($con, $sql_bids);
$bids = mysqli_fetch_all($result_bids, MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_SESSION["user"])) {
    // подготовка суммы ставки
    $bird_sum = mysqli_real_escape_string($con, $_POST["cost"]);
    // получаем информацию по лоту
    $lot = mysqli_fetch_assoc(mysqli_query($con, $current_lot));

    // узнаем есть ли ставки по этому лоту
    $max_value_bird = mysqli_query($con, "SELECT MAX(price) as `max_price` FROM `bids` WHERE `lot_id` =" . $id_lot .  " LIMIT 1");
    $max_value = mysqli_fetch_assoc($max_value_bird);

    if ($max_value["max_price"] !== null) {
        $new_sum = $max_value["max_price"] + $lot["step_rate"];
    } else {
        $new_sum = $lot["price_start"] + $lot["step_rate"];
    }

    if ($new_sum <= $bird_sum) {
        $sql_insert_bids = "INSERT INTO `bids` (`created_at`, `price`, `user_id`, `lot_id`) VALUES (NOW(), ?, ?, ?)";
        $stmt = db_get_prepare_stmt($con, $sql_insert_bids, [$bird_sum, $_SESSION["user"]["id"], $id_lot]);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            header("Location: lot.php?id=".$id_lot);
        } else {
            $page_content = include_template("error.php", [
                "text_error" => "Ошибка вставки: " . mysqli_errno($con)
            ]);
        }
    } else {
        $page_content = include_template("current_lot.php", [
            "categories" => $categories,
            "lot" => $lot,
            "bids" => $bids,
            "bird_sum" => $bird_sum,
            "text_error" => "Сумма ставки меньше минимальной или введено некорректное значение",
        ]);
    }
} else if ($result_lot = mysqli_query($con, $current_lot)) {
    if (mysqli_num_rows($result_lot)) {
        $lot = mysqli_fetch_assoc($result_lot);
        // подключаем шаблон с карточкой лота
        $page_content = include_template("current_lot.php", [
            "categories" => $categories,
            "lot" => $lot,
            "bids" => $bids,
        ]);
    } else {
        // подключаем шаблон с ошибкой
        $page_content = include_template("error.php", [
            "code_error" => "404",
            "text_error" => "Не удалось найти страницу с лотом №-" . "<b>" . $id_lot . "</b>",
        ]);
    }
} else {
    $page_content = include_template("error.php", [
        "text_error" => "Ошибка подключения: " . mysqli_errno($con)
    ]);
}

// собираем итоговую страницу лота
$layout_content = include_template("layout.php", [
    "main_content" => $page_content,
    "title_page" => "Страница лота",
    "user_name" => $_SESSION["user"]["name"] ?? "",
    "categories" => $categories,
]);

print($layout_content);
