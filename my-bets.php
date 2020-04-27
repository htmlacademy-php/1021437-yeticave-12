<?php
require_once "init.php";
require_once "helpers.php";

if (isset($_SESSION["user"])) {
    list($current_id, $current_page, $count_lots, $page_count, $offset) = get_count_items($con, "SELECT COUNT(id) as 'count' FROM `bids` WHERE `user_id` = ?", $_SESSION["user"]["id"]);
    $result_bids = mysqli_query($con, "SELECT lots.name, lots.image_link, categories.name as category, lots.ends_at, bids.created_at, bids.price, lots.description, lots.id, lots.user_winner_id, users.users_info  
        FROM `bids` as bids 
        JOIN `lots` as lots ON lots.id = bids.lot_id 
        JOIN `categories` as categories ON categories.id = lots.category_id
        JOIN `users` as users
        ON users.id = lots.author_id
        WHERE `user_id` = " . $current_id . " ORDER BY bids.created_at DESC LIMIT " . COUNT_ITEMS . " OFFSET " . $offset);
    $bets = mysqli_fetch_all($result_bids, MYSQLI_ASSOC);

    $page_content = include_template("bets.php", [
        "bets" => $bets,
        "user_id" => $_SESSION["user"]["id"],
        "count_lots" => $count_lots,
        "page_count" => $page_count,
        "current_page" => (int)$current_page,
    ]);
} else {
    header("Location: /");
}

// собираем итоговую страницу лота
$layout_content = include_template("layout.php", [
    "main_content" => $page_content,
    "title_page" => "Страница лота",
    "user_name" => $_SESSION["user"]["name"] ?? "",
    "categories" => $categories,
]);

print($layout_content);
