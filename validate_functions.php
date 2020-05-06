<?php

function not_empty(): callable
{
    return function ($value): ? string {
        return empty($value) ? "Это поле обязательно к заполнению" : null;
    };
}

function unique(string $table, string $field, string $text, int $flag, $link): callable
{
    return function ($value) use ($table, $field, $text, $flag, $link): ? string {
        $table = get_escape_string($link, $table);
        $field = get_escape_string($link, $field);
        $text = get_escape_string($link, $text);
        $flag = (int)get_escape_string($link, $flag);
        $sql = sprintf('SELECT count(id) AS count FROM `%s` WHERE `%s` = ?', $table, $field);
        $stmt = db_get_prepare_stmt($link, $sql, [$value]);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $email = mysqli_fetch_assoc($result);
        return $email["count"] === $flag ? $text : null;
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
