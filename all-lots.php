<?php
require_once "init.php";
require_once "helpers.php";
$current_category = get_escape_string($con, get_value("category", ""));
if (isset($current_category) && $current_category !== "") {
    list($count_lots, $page_count) = compute_pagination_offset_and_limit(
        $con,
        "SELECT 
            COUNT(`lots`.`id`) AS 'count'
        FROM `lots`
        JOIN `categories` ON `categories`.`id` = `lots`.`category_id`
        WHERE `ends_at` > NOW() 
        AND `categories`.`code` = ?",
        $current_category
    );
    $current_page = get_page_value();
    $offset = get_offset_items($current_page, COUNT_ITEMS);
    $current_category_name = mysqli_fetch_assoc(mysqli_query($con, "SELECT `name` FROM `categories` WHERE `code` = '".$current_category."'"));
    $sql_query_lots_category = "SELECT
        lots.id, 
        lots.image_link, 
        lots.name, 
        categories.name AS category, 
        categories.code, 
        lots.ends_at, 
        (SELECT IF (MAX(bids.price) = NULL, MAX(bids.price), lots.price_start)  FROM `bids` AS bids WHERE bids.lot_id = lots.id) AS price,
        (SELECT COUNT(id) FROM `bids` AS bids WHERE bids.lot_id = lots.id) AS count_bets
    FROM `lots` AS lots
    JOIN `categories` AS categories
    ON lots.category_id = categories.id
    WHERE lots.ends_at > NOW() 
    AND categories.code = '" . $current_category . "' ORDER BY lots.created_at DESC LIMIT " . COUNT_ITEMS . " OFFSET " . $offset;
    $lots_result = mysqli_query($con, $sql_query_lots_category);
    $lots = mysqli_fetch_all($lots_result, MYSQLI_ASSOC);
    $page_content = include_template("lots.php", [
        "categories" => $categories,
        "lots" => $lots,
        "current_category_code" => $current_category ?? "",
        "count_lots" => $count_lots,
        "current_category_name" => $current_category_name["name"] ?? $current_category,
        "page_count" => $page_count,
        "current_page" => $current_page,
    ]);
} else {
    http_response_code(403);
    $page_content = include_template("error.php", [
        "categories" => $categories,
        "code_error" => "403",
        "text_error" => "Для просмотра лотов необходимо выбрать категорию и нажать по ней",
        "view_categories" => true,
    ]);
}

$layout_content = include_template("layout.php", [
    "main_content" => $page_content,
    "title_page" => "Страница лота",
    "user_name" => session_user_value("name", ""),
    "categories" => $categories,
]);

print($layout_content);
