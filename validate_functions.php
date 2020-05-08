<?php

/**
 * Проверяет на установке значения или
 * возращает ошибку
 *
 * @return callable
 */
function not_empty(): callable
{
    return function ($value): ? string {
        return empty($value) ? "Это поле обязательно к заполнению" : null;
    };
}

/**
 * Проверяет существует ли в указанной таблице
 * заданное значение и возращает ошибку если
 * такого значения нету
 * @param string $table Таблица где ищем
 * @param string $field Поле по которому ищем
 * @param string $text Значение для поиска
 * @param mysqli $link Ресурс соединения
 *
 * @return callable
 */
function check_unique_value(string $table, string $field, string $text, $link): callable
{
    return function ($value) use ($table, $field, $text, $link): ? string {
        $email = value_search($table, $field, $value, $link);
        $text = get_escape_string($link, $text);
        return $email["count"] !== 1 ? $text : null;
    };
}

/**
 * Ищет в таблице соответствие по данным
 * указанными в аргументах
 * @param string $table Таблица
 * @param string $field Поле по которому ищем
 * @param string $value Значение для поиска
 * @param mysqli $link Ресурс соединения
 *
 * @return array|null Массив с данными
 */
function value_search(string $table, string $field, $value, $link)
{
    $table = get_escape_string($link, $table);
    $field = get_escape_string($link, $field);
    $sql = sprintf("SELECT count(id) AS count FROM `%s` WHERE `%s` = ?", $table, $field);
    $stmt = db_get_prepare_stmt($link, $sql, [$value]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_assoc($result);
}

/**
 * Проверяет существует ли в указанной таблице
 * заданное значение и возращает ошибку если
 * значения совпадают
 * @param string $table
 * @param string $field
 * @param string $text
 * @param mysqli $link Ресурс соединения
 *
 * @return callable
 */
function db_exists(string $table, string $field, string $text, $link): callable
{
    return function ($value) use ($table, $field, $text, $link): ? string {
        $email = value_search($table, $field, $value, $link);
        $text = get_escape_string($link, $text);
        return $email["count"] === 1 ? $text : null;
    };
}

/**
 * Проверяет на соответствие введеного пароля с
 * паролем из БД используя email адресс
 * @param mysqli $link Ресурс соединения
 * @param string $email Email адрес
 *
 * @return callable
 */
function password_correct($link, $email) : callable
{
    return function ($value) use ($link, $email): ? string {
        $result = get_data_user($link, $email);
        $user_data = $result ? mysqli_fetch_assoc($result) : null;
        return (!password_verify($value, $user_data["password"])) ? "Указан неверный пароль" : null;
    };
}

/**
 * Проверка что строка не больше $max - количество символов
 * @param null $max Количество символов
 *
 * @return callable
 */
function validate_field_text($max = null) : callable
{
    return function ($value) use ($max): ? string {
        return $max && mb_strlen($value) > $max ? "Значение должно быть не более $max символов" : "";
    };
}

/**
 * Проверка что введеное значение это число
 *
 * @return callable
 */
function it_is_number() : callable
{
    return function ($value) : ? string {
        return !is_numeric($value) ? "Можно вводить только число" : "";
    };
}

/**
 * Проверка что число больше нуля
 *
 * @return callable
 */
function validate_field_price() : callable
{
    return function ($value) : ? string {
        $price = number_format($value, 2, ".", ",");
        return $price <= 0 ?  "Введите число больше нуля" : "";
    };
}

/**
 * Проверка даты на формат и время
 * окончания торгов
 *
 * @return callable
 */
function validate_field_date() : callable
{
    return function ($value) : ? string {
        $format_to_check = "Y-m-d";
        $dateTimeObj = date_create_from_format($format_to_check, $value);
        if ($dateTimeObj) {
            $diff_in_hours = floor((strtotime($value) - strtotime("now")) / 3600);
            return $diff_in_hours <= 24 ? "Дата заверешния торгов, не может быть меньше 24 часов или отрицательной" : "";
        }
        return "Не верный формат времени";
    };
}

/**
 * Обработка правил валидации для
 * различных входных данных
 * @param array $data Данные для валидации
 * @param array $schema Схема правил для валидации
 *
 * @return array $errors Массив с ошибками
 */
function validate(array $data, array $schema): array
{
    $errors = [];
    foreach ($schema as $field => $rules) {
        foreach ($rules as $rule) {
            if (!isset($errors[$field])) {
                $errors[$field] = $rule($data[$field] ?? null);
            }
        }
    }
    return array_filter($errors);
}
