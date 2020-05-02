<?php
require_once "init.php";
require_once "helpers.php";


if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    function validate_email_field($email_field, $link)
    {
        if (empty($email_field)) {
            return "Это поле обязательно к заполнению";
        }
        $result = get_data_user($link, $email_field);
        if (mysqli_num_rows($result) === 0) {
            return "Нет пользователя с таким адресом";
        }
    }

    function validate_password_field($password_field, $email_field, $link)
    {
        if (empty($password_field)) {
            return "Это поле обязательно к заполнению";
        }
        $result = get_data_user($link, $email_field);
        $user_data = $result ? mysqli_fetch_assoc($result) : null;
        if (!password_verify($password_field, $user_data["password"])) {
            return "Указан неверный пароль";
        }
    }

    $rules = [
        "email" => function () use ($con) {
            return validate_email_field($_POST["email"], $con);
        },
        "password" => function () use ($con) {
            return validate_password_field($_POST["password"], $_POST["email"], $con);
        }
    ];

    $errors = validation_form($_POST, $rules);

    if (empty($errors)) {
        $result = get_data_user($con, $_POST["email"]);
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
