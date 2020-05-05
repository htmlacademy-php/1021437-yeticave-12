<?php
require_once "init.php";
require_once "helpers.php";


if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    $email = $_POST["email"];
    $password = $_POST["password"];

    function email_availability($link)
    {
        return function ($value) use ($link): ? string {
            $result = get_data_user($link, $value);
            return mysqli_num_rows($result) === 0 ?  "Нет пользователя с таким адресом" : null;
        };
    }

    function not_empty(): callable
    {
        return function ($value): ? string {
            return empty($value) ? "Это поле обязательно к заполнению" : null;
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
            'email' => post_value('email'),
            'password' => post_value('password'),
        ],
        [
            'email' => [
                not_empty(),
                email_availability($con),
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
