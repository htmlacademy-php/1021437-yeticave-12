<?php
function format_sum($number = 0)
{
    $price = ceil($number);
    $format_price = number_format($price, 0, ",", " ");
    return $format_price .  " ₽";
}
function get_dt_range($value_date)
{
    $time_difference = strtotime($value_date) - time();
    $time_hours = floor($time_difference / 3600);
    $time_minutes = floor(($time_difference % 3600) / 60);
    return [$time_hours, $time_minutes];
}
function get_dt_difference($value_time_my)
{
    $time_difference = time() - strtotime($value_time_my);
    $time_hours = floor($time_difference / 3600);
    $time_minutes = floor(($time_difference % 3600) / 60);
    return [(int)$time_hours, $time_minutes];
}
function get_dt_end($value_date)
{
    if (time() > strtotime($value_date)) {
        return true;
    }
}
function get_max_price_bids($prices, $price_start)
{
    if (!isset($prices[0]["price"])) {
        return $price_start;
    }
    $max_value = $prices[0]["price"];
    foreach ($prices as $price) {
        if ($max_value < $price["price"]) {
            $max_value = $price["price"];
        }
    }
    return $max_value;
}
function check_field($field)
{
    if (empty($field)) {
        return "Это поле обязательно к заполнению";
    }
}

// блок проверок валидации формы добавление лота
function get_field_value($field_name)
{
    return $_POST[$field_name] ?? "";
}

// функция валидация форм
function validation_form($data, $rules)
{
    $errors = [];
    foreach ($data as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }
    $errors = array_filter($errors);
    return $errors;
}

// функция получения данных о пользователе
function get_data_user($link, $email_field)
{
    $email = mysqli_real_escape_string($link, $email_field);
    $sql_query_data = "SELECT * FROM `users` WHERE `email` = ? ";
    $stmt = db_get_prepare_stmt($link, $sql_query_data, [$email]);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}
function render_pagination_button($path_button, $text_button, $class_important = "", $disable = false, $text_search = null, $current_page = null)
{
    if ($disable) {
        $disable = "style='pointer-events: none;'";
    }
    $string_template =  '<li %s class="pagination-item %s"><a href="%s%s&page=%d">%s</a></li>';
    return sprintf($string_template, $disable, $class_important, $path_button, $text_search, $current_page, $text_button);
}
// функция пагинации
function render_pagination($all_lots, $value_items, $current_page, $pages, $str_search, $path_search)
{
    if ($all_lots > $value_items) {
        $pagination = "<ul class='pagination-list'>";

        if ($current_page === 1) {
            $pagination .= render_pagination_button("#", "Назад", "pagination-item-prev", true);
        } else {
            $pagination .= render_pagination_button($path_search, "Назад", "pagination-item-prev", false, $str_search, $current_page - 1);
        }

        for ($i = 1; $i <= $pages; $i++) {
            if ($current_page === $i) {
                $pagination .= render_pagination_button("#", $i, "pagination-item-active", true);
            } else {
                $pagination .= render_pagination_button($path_search, $i, "", false, $str_search, $i);
            }
        }

        if ($pages > $current_page) {
            $pagination .= render_pagination_button($path_search, "Вперед", "pagination-item-next", false, $str_search, $current_page + 1);
        } else {
            $pagination .= render_pagination_button("#", "Вперед", "pagination-item-next", true);
        }
        return $pagination . "</ul>";
    }
    return false;
}
// узнаем общее количество лотов, отступов, количество страниц
function get_count_items($link, $sql_query, $data)
{
    $current_page = $_GET["page"] ?? 1;
    $current_data = mysqli_real_escape_string($link, $data);
    $stmt_count = db_get_prepare_stmt($link, $sql_query, [$current_data]);
    mysqli_stmt_execute($stmt_count);
    $count_lots = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_count));
    $count_lots = $count_lots["count"];
    $page_count = ceil($count_lots / COUNT_ITEMS);
    $offset = ($current_page - 1) * COUNT_ITEMS;
    return [$current_data, $current_page, $count_lots, $page_count, $offset];
}
