<?php
require_once "init.php";
require_once "helpers.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $errors = [];

    function validate_email_field($email_field, $link)
    {
        $is_empty_email = check_field($email_field);
        if (empty($is_empty_email)) {
            $email = mysqli_real_escape_string($link, $email_field);
            $sql_query_user = "SELECT `email` FROM `users` WHERE `email` = ? ";
            $stmt = db_get_prepare_stmt($link, $sql_query_user, [$email]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) === 0) {
                return "Нет пользователя с таким адресом";
            }
        }
        return $is_empty_email;
    }

    function validate_password_field($password_field, $email_field, $link)
    {
        $is_empty_password = check_field($password_field);
        if(empty($is_empty_password)) {
            $email = mysqli_real_escape_string($link, $email_field);
            $sql_query_password = "SELECT * FROM `users` WHERE `email` = ? ";
            $stmt = db_get_prepare_stmt($link, $sql_query_password, [$email]);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user_data = $result ? mysqli_fetch_assoc($result) : null;
            if (password_verify($password_field, $user_data["password"])) {
                //запись в сессию
                $_SESSION["user"] = $user_data;
            } else {
                return "Указан неверный пароль";
            }
        }
        return $is_empty_password;
    }

    $rules = [
        "email" => function () use ($con) {
            return validate_email_field($_POST["email"], $con);
        },
        "password" => function ()  use ($con) {
            return validate_password_field($_POST["password"], $_POST["email"], $con);
        }
    ];

    $errors = validation_form($_POST, $rules);

    if (empty($errors)) {
        header("Location: index.php");
        exit();
    } else {
        $page_content = include_template("login.php", [
            "errors" => $errors,
            "text_errors" => "Вы ввели неверный email/пароль",
        ]);
    }

} else {
    $page_content = include_template("login.php", []);
    if (isset($_SESSION['user'])) {
        header("Location: index.php");
        exit();
    }
}


$layout_content = include_template("layout.php", [
    "main_content" => $page_content,
    "title_page" => "Авторизация на сайте",
    "categories" => $categories,
]);

print($layout_content);

