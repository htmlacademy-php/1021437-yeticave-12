<?php
date_default_timezone_set("Europe/Moscow");
require_once "mysql_connect.php";
require_once "helpers.php";
require_once "functions.php";
$is_auth = 0;
if ($is_auth === 1) {
    header("Location: index.php");
}

// запрос категорий
$sql_categories = "SELECT `name`, `code`, `id` FROM `categories`";
// выполнение запроса
$result_categories = mysqli_query($con, $sql_categories);
// получение двухмерного массива категорий
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $required_fields = ['email', 'password', 'name', 'message'];
    $errors = [];

    function validate_field_email($email_field)
    {
        if(!filter_var($email_field, FILTER_VALIDATE_EMAIL)) {
            return "Невалидный адрес";
        }
        return check_field($email_field);
    }

    $rules = [
        'email' => function() {
            return validate_field_email($_POST['email']);
        },
        'password' => function() {
            return check_field($_POST['password']);
        },
        'name' => function() {
            return check_field($_POST['name']);
        },
        'message' => function() {
            return check_field($_POST['message']);
        }
    ];


    foreach ($_POST as $key => $value) {
        if(isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    $email = mysqli_real_escape_string($con, $_POST['email']);
    $sql = "SELECT `id` FROM users WHERE `email` = '$email'";
    $res = mysqli_query($con, $sql);
    if (mysqli_num_rows($res) > 0) {
        $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
    }

    if(empty($errors)) {
        //шифруем пароль MD5
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query_insert_database_user = "INSERT INTO `users` (`registration_at`, `email`, `name`, `password`, `users_info`)
    VALUES
    (NOW(), ?, ?, ?, ?)";
        $stmt = mysqli_prepare($con, $query_insert_database_user);
        mysqli_stmt_bind_param($stmt, 'ssss', $_POST['email'], $_POST['name'], $password, $_POST['message']);
        $result = mysqli_stmt_execute($stmt);
        if($result) {
            header("location: login.php");
            exit();
        } else {
            echo 'Ошибка вставки ' . mysqli_error($con);
        }
    } else {
        $page_content = include_template("sign-up.php", [
            'errors' => $errors,
        ]);
    }
} else {
    $page_content = include_template("sign-up.php", []);
}


$layout_content = include_template("layout.php", [
    'main_content' => $page_content,
    'title_page' => 'Добавление лота',
    'user_name' => 'Bogdan',
    'categories' => $categories,
    'is_auth' => $is_auth,
]);

print($layout_content);

