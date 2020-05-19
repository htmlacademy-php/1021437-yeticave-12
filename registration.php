<?php
require_once "init.php";
require_once "helpers.php";
require_once "validate_functions.php";

if (isset($_SESSION["user"])) {
    http_response_code(403);
    $page_content = include_template("error.php", [
        "categories" => $categories,
        "code_error" => "403",
        "text_error" => "Страница для незарегистрированных пользователей"
    ]);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    $email = post_value("email");
    $password = post_value("password");
    $name = post_value("name");
    $message = post_value("message");

    $errors = validate(
        [
            "email" => $email,
            "password" => $password,
            "name" => $name,
            "message" => $message,
        ],
        [
            "email" => [
                not_empty("Введите электронный адресс"),
                checking_correct_email(),
                db_exists("users", "email", "Пользователь с этим email уже зарегистрирован", $con),
                str_length_gt(254),
            ],
            "password" => [
                not_empty("Введите пароль"),
            ],
            "name" => [
                not_empty("Укажите Ваше ФИО"),
                str_length_gt(128),
            ],
            "message" => [
                not_empty("Напишите как с вами связаться"),
            ],
        ]
    );

    if (empty($errors)) {
        $password = password_hash($password, PASSWORD_DEFAULT);
        $query_insert_database_user = "INSERT INTO `users`(
            `registration_at`,
            `email`,
            `name`,
            `password`,
            `users_info`
        )
        VALUES(NOW(), ?, ?, ?, ?)";
        $stmt = db_get_prepare_stmt($con, $query_insert_database_user, [$email, $name, $password, $message]);
        $result = mysqli_stmt_execute($stmt);
        if ($result) {
            header("location: login.php");
            exit();
        }
        echo "Ошибка вставки " . mysqli_error($con);
    } else {
        $page_content = include_template("sign-up.php", [
            "errors" => $errors,
        ]);
    }
} else {
    $page_content = include_template("sign-up.php", []);
}

$layout_content = include_template("layout.php", [
    "main_content" => $page_content,
    "title_page" => "Страница регистрации",
    "user_name" => get_value_from_user_session("name"),
    "categories" => $categories,
]);

print($layout_content);
