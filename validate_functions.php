<?php

function not_empty(): callable
{
    return function ($value): ? string {
        return empty($value) ? "Это поле обязательно к заполнению" : null;
    };
}

function check_unique_value(string $table, string $field, string $text, $link): callable
{
    return function ($value) use ($table, $field, $text, $link): ? string {
        list($email, $text) = value_search($table, $field, $text, $value, $link);
        return $email["count"] !== 1 ? $text : null;
    };
}

function value_search(string $table, string $field, $text, $value,  $link)
{
    $table = get_escape_string($link, $table);
    $field = get_escape_string($link, $field);
    $text = get_escape_string($link, $text);
    $sql = sprintf('SELECT count(id) AS count FROM `%s` WHERE `%s` = ?', $table, $field);
    $stmt = db_get_prepare_stmt($link, $sql, [$value]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    return [mysqli_fetch_assoc($result), $text];
}

function is_set_value_on_db(string $table, string $field, string $text, $link): callable
{
    return function ($value) use ($table, $field, $text, $link): ? string {
        list($email, $text) = value_search($table, $field, $text, $value, $link);
        return $email["count"] === 1 ? $text : null;
    };
}

function password_correct($link, $email) : callable
{
    return function ($value) use ($link, $email): ? string {
        $result = get_data_user($link, $email);
        $user_data = $result ? mysqli_fetch_assoc($result) : null;
        return (!password_verify($value, $user_data["password"])) ? "Указан неверный пароль" : null;
    };
}

function validate(array $data, array $schema): array
{
    $errors = [];
    foreach ($schema as $field => $rules) {
        foreach ($rules as $rule) {
            if(!isset($errors[$field])) {
                $errors[$field] = $rule($data[$field] ?? null);
            }
        }
    }
    return array_filter($errors);
}
