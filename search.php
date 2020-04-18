<?php
require_once "init.php";
require_once "helpers.php";


if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $str_search = trim($_GET["search"]);

    if (!empty($str_search)) {

        $string_text = mysqli_real_escape_string($con, $str_search);
        //узнаем текущую страницу
        $current_page = $_GET["page"] ?? 1;
        //количество элементов на странице
        $page_items = COUNT_ITEMS;
        $stmt_count = db_get_prepare_stmt($con, "SELECT COUNT(id) as 'count' FROM `lots` WHERE MATCH(name, description) AGAINST(?)", [$string_text]);
        mysqli_stmt_execute($stmt_count);
        $count_lots = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_count));
        $count_lots = $count_lots["count"];
        // скольо всего страниц с элементами поиска
        $page_count = ceil($count_lots / $page_items);
        //узнаем отсуп в количестве элементов
        $offset = ($current_page - 1) * $page_items;
        //создаем массив из количетсва страниц
        $pages_number = range(1, $page_count);

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
                "pages_number" => $pages_number,
                "current_page" => $current_page,
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
