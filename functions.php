<?php
/**
 * Форматирует число и добавляет в конце знак рубля
 * @param int $number Число для форматирования
 *
 * @return string Отформатированное число со знаком рубля
 */
function format_sum($number = 0)
{
    $price = ceil($number);
    $format_price = number_format($price, 0, ",", " ");
    return $format_price . " ₽";
}

/**
 * Вычисляет оставшееся время и
 * возвращает данные в виде часов и минут
 * @param DateTime $value_date Дата
 *
 * @return array Часы и минуты
 */
function get_dt_range($value_date)
{
    $time_difference = strtotime($value_date) - time();
    $time_hours = floor($time_difference / 3600);
    $time_minutes = floor(($time_difference % 3600) / 60);
    return [$time_hours, $time_minutes];
}

/**
 * Возвращает разница в часах и минутах
 * между текущем временем и входящим значением
 * @param DateTime $value_time_my
 *
 * @return array Часы и минуты
 */
function get_dt_difference($value_time_my)
{
    $time_difference = time() - strtotime($value_time_my);
    $time_hours = floor($time_difference / 3600);
    $time_minutes = floor(($time_difference % 3600) / 60);
    return [(int)$time_hours, $time_minutes];
}

/**
 * Возвращает булевое true в случае
 * если входящая дата меньше текущей
 * @param DateTime $value_date дата
 *
 * @return bool
 */
function get_dt_end($value_date)
{
    if (time() > strtotime($value_date)) {
        return true;
    }
}

/**
 * Поиск максимальной цены или возвращние
 * текущей цены
 * @param array $prices Ставки с ценой
 * @param int $price_start Стартовая цена
 *
 * @return mixed
 */
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

/**
 * Возвращает ошибку, если поле пустое
 * @param string $field Поле с текстом
 *
 * @return string Комментарий
 */
function check_field($field)
{
    if (empty($field)) {
        return "Это поле обязательно к заполнению";
    }
}

/**
 * Возвращает значение из глобального массива $_POST
 * если оно не пустое
 * @param string $field_name Поле с текстом
 *
 * @return string Текст
 */
function get_field_value($field_name)
{
    return $_POST[$field_name] ?? "";
}

/**
 * Обработка правил валидации для
 * различных входных данных
 * @param array $data Данные для валидации
 * @param array $rules Перечень правил для валидации
 *
 * @return array Массив с ошибками
 */
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

/**
 * Ищет в БД информацию о пользователе и
 * возвращает её
 * @param mysqli $link Ресурс соединения
 * @param string $email_field Email адресс
 *
 * @return false|mysqli_result Данные о пользователе
 */
function get_data_user($link, $email_field)
{
    $sql_query_data = "SELECT * FROM `users` WHERE `email` = ? ";
    $stmt = db_get_prepare_stmt($link, $sql_query_data, [$email_field]);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
}

/**
 * Функция создания html элемента <li> для
 * пагинации, применяется в функции render_pagination
 * @param string $path_button Путь url
 * @param string $text_button Текст на кнопке
 * @param string $class_important Дополнительный класс
 * @param bool $disable Состояние кнопки
 * @param null $text_search Текст для поиска (работает на поиске)
 * @param null $current_page Текущая страница
 *
 * @return string
 */
function render_pagination_button(
    $path_button,
    $text_button,
    $class_important = "",
    $disable = false,
    $text_search = null,
    $current_page = null
) {
    if ($disable) {
        $disable = "style='pointer-events: none;'";
    }
    $string_template = '<li %s class="pagination-item %s"><a href="%s%s&page=%d">%s</a></li>';
    return sprintf($string_template, $disable, $class_important, $path_button, $text_search, $current_page,
        $text_button);
}

/**
 * Создание html элемента пагинации, если
 * количество элементов больше заданного количества
 * @param int $all_lots Количество элементов всего
 * @param $value_items Количество элементов на странице
 * @param int $current_page Текущая страница
 * @param int $pages Количество страниц
 * @param $str_search Строка с поиском
 * @param $path_search Путь url
 *
 * @return bool|string HTML element
 */
function render_pagination($all_lots, $value_items, $current_page, $pages, $str_search, $path_search)
{
    if ($all_lots > $value_items) {
        $pagination = "<ul class='pagination-list'>";

        if ($current_page === 1) {
            $pagination .= render_pagination_button("#", "Назад", "pagination-item-prev", true);
        } else {
            $pagination .= render_pagination_button($path_search, "Назад", "pagination-item-prev", false, $str_search,
                $current_page - 1);
        }

        for ($i = 1; $i <= $pages; $i++) {
            if ($current_page === $i) {
                $pagination .= render_pagination_button("#", $i, "pagination-item-active", true);
            } else {
                $pagination .= render_pagination_button($path_search, $i, "", false, $str_search, $i);
            }
        }

        if ($pages > $current_page) {
            $pagination .= render_pagination_button($path_search, "Вперед", "pagination-item-next", false, $str_search,
                $current_page + 1);
        } else {
            $pagination .= render_pagination_button("#", "Вперед", "pagination-item-next", true);
        }
        return $pagination . "</ul>";
    }
    return false;
}

/**
 * Возвращает экранированную строку
 * @param mysqli $connect Ресурс соединения
 * @param string $text Текст для экранирования
 *
 * @return string Экранированная строка
 */
function get_escape_string($connect, $text)
{
    $str = mysqli_real_escape_string($connect, $text);
    return $str;
}

/**
 * Обрабатывает запрос с количеством лотов и
 * возращает данные для пагинации
 * @param mysqli $link Ресурс соединения
 * @param string $sql_query SQL запрос с количеством лотов
 * @param string/int $data Данные для запроса
 * @param int $page Номер страницы
 *
 * @return array Текущая страница, количество лотов, количество страниц, отступ
 */
function compute_pagination_offset_and_limit($link, $sql_query, $data, $page)
{
    $current_page = $page ?? 1;
    $stmt_count = db_get_prepare_stmt($link, $sql_query, [$data]);
    mysqli_stmt_execute($stmt_count);
    $count_lots = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt_count));
    $count_lots = $count_lots["count"];
    $page_count = ceil($count_lots / COUNT_ITEMS);
    $offset = ($current_page - 1) * COUNT_ITEMS;
    return [$current_page, $count_lots, $page_count, $offset];
}
