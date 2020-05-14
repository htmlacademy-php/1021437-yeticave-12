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
 * @param integer $max Количество символов
 *
 * @return callable
 */
function str_length_gt($max) : callable
{
    return function ($value) use ($max): ? string {
        return $max && mb_strlen($value) > $max ? "Значение должно быть не более $max символов" : null;
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
        return !is_numeric($value) ? "Можно вводить только число" : null;
    };
}

/**
 * Проверка что число больше нуля
 *
 * @return callable
 */
function check_price_greater_than_zero() : callable
{
    return function ($value) : ? string {
        $price = number_format($value, 2, ".", ",");
        return $price <= 0 ?  "Введите число больше нуля" : null;
    };
}

/**
 * Проверка что поле
 * является корректным e-mail
 *
 * @return callable
 */
function checking_correct_email() : callable
{
    return function ($value) : ? string {
        return !filter_var($value, FILTER_VALIDATE_EMAIL) ? "Поле с адресом не является корректным" : null;
    };
}

/**
 * Проверка даты на формат и время
 * окончания торгов
 *
 * @return callable
 */
function checking_date_on_format_and_date_lot_end() : callable
{
    return function ($value) : ? string {
        $format_to_check = "Y-m-d";
        $dateTimeObj = date_create_from_format($format_to_check, $value);
        if ($dateTimeObj) {
            $diff_in_hours = floor((strtotime($value) - strtotime("now")) / 3600);
            return $diff_in_hours <= 24 ?
                "Дата заверешния торгов, не может быть меньше 24 часов или отрицательной" :
                null;
        }
        return "Не верный формат времени";
    };
}

/**
 * Возращаем ошибку если файл не добавили
 *
 * @return callable
 */
function checking_add_image() : callable
{
    return function ($image): ? string {
        return empty($image["name"]) ? "Файл обязателен для загрузки" : null;
    };
}

/**
 * Функция возращает ошибку, если не получилось
 * загрузить файл на сервер
 * @return Closure
 */
function is_file_uploaded()
{
    return function ($value) : ? string {
        $error_info = [
            "1" => "размер файла слишком большой",
            "2" => "размер файла слишком большой",
            "3" => "файл был получен только частично",
            "4" => "файл не был загружен",
            "6" => "отсутствует временная папка",
            "7" => "не удалось записать файл",
            "8" => "загрузка файла была остановлена",
        ];
        return $value["error"] !== UPLOAD_ERR_OK  ? "Ошибка при загрузке файла: " . $error_info[$value["error"]] : null;
    };
}

/**
 * Проверка корректности тпиа загруженного изображения
 *
 * @return callable
 */
function checking_type_image() : callable
{
    return function ($image) : ? string {
        $type_file = mime_content_type($image["tmp_name"]);
        return ($type_file !== "image/jpeg" && $type_file !== "image/png") ?
            "Поддерживается загрузка только png, jpg, jpeg" :
            null;
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
