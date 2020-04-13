<?php

date_default_timezone_set("Europe/Moscow");
require_once "mysql_connect.php";
require_once "helpers.php";
require_once "functions.php";
if ($is_auth === 0) {
    header("Location: index.php");
}

// запрос категорий
$sql_categories = "SELECT `name`, `code`, `id` FROM `categories`";
// выполнение запроса
$result_categories = mysqli_query($con, $sql_categories);
// получение двухмерного массива категорий
$categories = mysqli_fetch_all($result_categories, MYSQLI_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $required_fields = ['lot-name', 'category', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];

    // функция проверки полей: lot-name, message
    function validate_field_text($field_text, $max = NULL)
    {
        if($max && mb_strlen($field_text) > $max) {
            return "Значение должно быть не более $max символов";
        }
        return check_field($field_text);
    }
    // функция проверки начальной цены и шага ставки
    function validate_field_price($field_price)
    {
        if(!is_numeric($field_price)) {
            return "Можно вводить только число";
        }
        $price = number_format($field_price, 2, '.', ',');
        if ($price <= 0) {
            return "Введите число больше нуля";
        }
        return check_field($field_price);
    }
    // функция проверки выбора категории
    function validate_field_category($field_category)
    {
        return check_field($field_category);
    }
    // функция проверки даты
    function validate_field_date($field_date)
    {
        $format_to_check = 'Y-m-d';
        $dateTimeObj = date_create_from_format($format_to_check, $field_date);
        if ($dateTimeObj) {
            $diff_in_hours = floor((strtotime($field_date) - strtotime("now")) / 3600);
            if ($diff_in_hours <= 24) {
                return "Дата заверешния торгов, не может быть меньше 24 часов или отрицательной";
            }
        }
        return check_field($field_date);
    }
    //правила проверок
    $rules = [
        'lot-name' => function() {
            return validate_field_text($_POST['lot-name'],  255);
        },
        'message' => function() {
            return validate_field_text($_POST['message']);
        },
        'lot-rate' => function() {
            return validate_field_price($_POST['lot-rate']);
        },
        'category' => function() {
            return validate_field_category($_POST['category']);
        },
        'lot-step' => function() {
            return validate_field_price($_POST['lot-step']);
        },
        'lot-date' => function() {
            return validate_field_date($_POST['lot-date']);
        }
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

//    function handler_rules($data, $rules)
//    {
//
//    }

    $errors = array_filter($errors);

    if (empty($_FILES['lot-img']['name'])) {
        $errors['lot-img'] = 'Файл обязателен для загрузки';
    } else {
        $id_image = uniqid();
        $file_name = $id_image . $_FILES['lot-img']['name'];
        $type_file = mime_content_type($_FILES['lot-img']['tmp_name']);
        if($type_file !== "image/jpeg" && $type_file !== "image/png") {
            $errors['lot-img'] = 'Поддерживается загрузка только png, jpg, jpeg ' . $type_file;
        } else {
            move_uploaded_file($_FILES['lot-img']['tmp_name'], PATH_UPLOADS_IMAGE .$file_name);
            if($_FILES['lot-img']['error'] !== UPLOAD_ERR_OK) {
                return "Ошибка при загрузке файла - код ошибки: " . $_FILES['lot-img']['error'];
            }
        }

    }


    //если ошибок нету
    if(!count($errors)) {
        $file_url = PATH_UPLOADS_IMAGE . $id_image . $_FILES['lot-img']['name'];
        $query_insert_database_lot = "INSERT INTO `lots`
    (`created_at`, `name`, `description`, `image_link`, `price_start`, `ends_at`, `step_rate`, `author_id`, `user_winner_id`, `category_id`)
VALUES
(NOW(), ?, ?, ?, ?, ?, ?, '3', '0', ?)";
        $stmt = mysqli_prepare($con, $query_insert_database_lot);
        mysqli_stmt_bind_param($stmt, 'ssssssi', $_POST['lot-name'],$_POST['message'], $file_url, $_POST['lot-rate'], $_POST['lot-date'], $_POST['lot-step'], $_POST['category']);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $last_id = mysqli_insert_id($con);
            // переадресация на страницу с новым добавленным лотом
            header("Location: lot.php?id=".$last_id);
        } else {
            echo 'Ошибка вставки ' . mysqli_error($con);
        }
    } else {
        $page_content = include_template("add-lot.php", [
            'categories' => $categories,
            'errors' => $errors,
        ]);
    }
} else {
    $page_content = include_template("add-lot.php", [
        'categories' => $categories
    ]);
}


$layout_content = include_template("layout.php", [
    'main_content' => $page_content,
    'title_page' => 'Добавление лота',
    'user_name' => 'Bogdan',
    'categories' => $categories,
    'css_calendar' => "<link href=\"css/flatpickr.min.css\" rel=\"stylesheet\">",
    'is_auth' => $is_auth,
]);

print($layout_content);
