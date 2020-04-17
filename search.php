<?php
require_once "init.php";
require_once "helpers.php";


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $str_search = trim($_GET["search"]);

    if (!empty($str_search)) {
        $string_text = mysqli_real_escape_string($con, $str_search);
        $query_search_lot = "SELECT lots.*, categories.name as category_name FROM `lots` as lots
        JOIN `categories` as categories
        ON categories.id = lots.category_id
        WHERE MATCH(lots.name, lots.description) AGAINST(?) ORDER BY lots.created_at DESC LIMIT " . COUNT_ITEMS;
        $stmt = db_get_prepare_stmt($con, $query_search_lot, [$string_text]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $count_lots = mysqli_num_rows($result);
        if ($count_lots !== 0) {
            $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
            $page_content = include_template("search-page.php", [
                "str_search" => $string_text,
                "lots" => $lots,
                "count_lots" => $count_lots,
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
