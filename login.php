<?php
require_once "init.php";
require_once "helpers.php";
require_once "validate_functions.php";

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    $email = post_value("email");
    $password = post_value("password");

    $errors = validate(
        [
            "email" => $email,
            "password" => $password,
        ],
        [
            "email" => [
                not_empty(),
                check_unique_value("users", "email", "Такого пользователя нету", $con),
            ],
            "password" => [
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
