<?php
require_once "init.php";
require_once "helpers.php";

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $str_search = trim($_GET["search"]);

    if (!empty($str_search)) {
        list($string_text, $current_page, $count_lots, $page_count, $offset) = get_count_items($con, "SELECT COUNT(id) as 'count' FROM `lots` WHERE MATCH(name, description) AGAINST(?)", $str_search);
        // информация по лотам на странице в количестве 9 штук и со смещением
        $query_search_lot = "SELECT lots.*, categories.name as category_name FROM `lots` as lots
        JOIN `categories` as categories
        ON categories.id = lots.category_id
        WHERE MATCH(lots.name, lots.description) AGAINST(?) ORDER BY lots.created_at DESC LIMIT " . COUNT_ITEMS . " OFFSET " . $offset;
        $stmt = db_get_prepare_stmt($con, $query_search_lot, [$string_text]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($count_lots !== 0) {
            $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $page_content = include_template("search-page.php", [
                "str_search" => $string_text,
                "lots" => $lots,
                "count_lots" => $count_lots,
                "page_count" => $page_count,
                "current_page" => (int)$current_page,
            ]);
        } else {
            $page_content = include_template("search-page.php", [
                "empty_search" => "Ничего не найдено по вашему запросу",
            ]);
        }
    } else {
        $page_content = include_template("search-page.php", [
            "empty_search" => "Ничего не найдено по вашему запросу",
        ]);
    }
}

$layout_content = include_template("layout.php", [
    "main_content" => $page_content,
    "title_page" => "Страница регистрации",
    "user_name" => $_SESSION["user"]["name"] ?? "",
    "categories" => $categories,
]);

print($layout_content);
