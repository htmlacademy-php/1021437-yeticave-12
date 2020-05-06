<?php
require_once "init.php";
require_once "helpers.php";


if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    $email = post_value('email');
    $password = post_value('password');

    function not_empty(): callable
    {
        return function ($value): ? string {
            return empty($value) ? "Это поле обязательно к заполнению" : null;
        };
    }

    function unique(string $table, string $field, string $text, $link): callable
    {
        return function ($value) use ($table, $field, $text, $link): ? string {
            $table = get_escape_string($link, $table);
            $field = get_escape_string($link, $field);
            $text = get_escape_string($link, $text);
            $sql = sprintf('SELECT count(id) AS count FROM `%s` WHERE `%s` = ?', $table, $field);
            $stmt = db_get_prepare_stmt($link, $sql, [$value]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $email = mysqli_fetch_assoc($result);
            return $email["count"] !== 1 ? $text : null;
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

    $errors = validate(
        [
            'email' => $email,
            'password' => $password,
        ],
        [
            'email' => [
                not_empty(),
                unique("users", "email", "Такого пользователя нету", $con),
            ],
            'password' => [
                not_empty(),
                password_correct($con, $email),
            ],
        ]
    );

    if (empty($errors)) {
        $result = get_data_user($con, $email);
        $_SESSION["user"] = $result ? mysqli_fetch_assoc($result) : null;
        header("Location: index.php");
        exit();
    } else {
        $page_content = include_template("login.php", [
            "errors" => $errors,
            "text_errors" => "Вы ввели неверный email/пароль",
            "email" => post_value("email", ""),
            "password" => post_value("password", ""),
        ]);
    }
} else {
    $page_content = include_template("login.php", [
        "email" => post_value("email", ""),
        "password" => post_value("password", ""),
    ]);
}

$layout_content = include_template("layout.php", [
    "main_content" => $page_content,
    "title_page" => "Авторизация на сайте",
    "categories" => $categories,
]);

print($layout_content);
