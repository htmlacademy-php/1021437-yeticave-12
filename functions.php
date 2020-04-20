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
// функция пагинации
function get_pagination($all_lots, $value_items, $current_page, $pages, $str_search)
{
    if ($all_lots > $value_items) {
        $disable_style = "style='pointer-events: none;'";
        $pagination = "<ul class=\"pagination-list\">";

        if ($current_page === 1) {
            $pagination .= "<li " . $disable_style . " class='pagination-item pagination-item-prev'><a href='#' style='color:#fff;'>Назад</a></li>";
        } else {
            $pagination .= "<li class='pagination-item pagination-item-prev'><a href=" . "search.php?search=" . $str_search . "&page=" . ($current_page - 1) . ">Назад</a></li>";
        }

        for ($i = 1; $i <= $pages; $i++) {
            if ($current_page === $i) {
                $pagination .= "<li " . $disable_style . " class='pagination-item pagination-item-active'><a>$i</a></li>";
            } else {
                $pagination .= "<li class='pagination-item'><a href='search.php?search=" . $str_search . "&page=" . $i . "'>" . $i . "</a></li>";
            }
        }

        if ($pages > $current_page) {
            $pagination .= "<li class='pagination-item pagination-item-next'><a href='search.php?search=" . $str_search . "&page=" . ($current_page + 1) . "'>Вперед</a></li>";
        } else {
            $pagination .= "<li " . $disable_style . " class='pagination-item pagination-item-next'><a style='color:#fff;' href='#'>Вперед</a></li>";
        }
        return $pagination .= "</ul>";
    }

    return false;
}

