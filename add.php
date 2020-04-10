<?php
date_default_timezone_set("Europe/Moscow");
require_once "helpers.php";
require_once "functions.php";
require_once "mysql_connect.php";

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
    function validate_field_text($field_text, $type, $max = NULL)
    {
        if(empty($field_text)) {
            return "Это поле обязательно к заполнению";
        }
        //поле lot-name имеет ограничение из-за типа Varchar(255)
        if($type === 'lot-name' && strlen($field_text) > $max) {
            return "Значение должно быть от $min до $max символов";
        }
    }
    // функция проверки начальной цены
    function validate_field_price($field_price)
    {
        if(empty($field_price)) {
            return "Поле сумма обязательно к заполнению";
        }
        $price = number_format(intval($field_price), 0, '.', ',');
        if ($price <= 0) {
            return "Введите число больше нуля";
        }
    }
    // функция проверки выбора категории
    function validate_field_category($field_category)
    {
        if($field_category === 'Выберите категорию') {
            return "Выберите категорию из списка";
        }
    }
    // функция проверки даты
    function validate_field_date($field_date)
    {
        if(empty($field_date)) {
            return "Поле дата обязательно к заполнению";
        }
        $format_to_check = 'Y-m-d';
        $dateTimeObj = date_create_from_format($format_to_check, $field_date);
        if ($dateTimeObj) {
            $diff_in_hours = floor((strtotime($field_date) - strtotime("now")) / 3600);
            if ($diff_in_hours <= 24) {
                return "Дата заверешния торгов, не может быть меньше 24 часов или отрицательной" . $diff_in_hours;
            }
        }
    }
    //правила проверок
    $rules = [
        'lot-name' => function() {
            return validate_field_text($_POST['lot-name'],  'lot-name', 255);
        },
        'message' => function() {
            return validate_field_text($_POST['message'],  'message');
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

    $errors = array_filter($errors);

    if (empty($_FILES['lot-img']['name'])) {
        $errors['lot-img'] = 'Файл обязателен для загрузки';
    } else {
        $file_name = $_FILES['lot-img']['name'];
        $type_file = mime_content_type($_FILES['lot-img']['tmp_name']);
        if($type_file !== "image/jpeg" && $type_file !== "image/png") {
            $errors['lot-img'] = 'Поддерживается загрузка только png, jpg, jpeg ' . $type_file;
        }
        move_uploaded_file($_FILES['lot-img']['tmp_name'], 'img/'.$file_name);
    }


    //если ошибок нету
    if(!count($errors)) {
        $file_url = 'img/' . $_FILES['lot-img']['name'];
        $id_category = get_id_category($categories);
        $query_insert_database_lot = "INSERT INTO `lots`
    (`created_at`, `name`, `description`, `image_link`, `price_start`, `ends_at`, `step_rate`, `author_id`, `user_winner_id`, `category_id`)
VALUES
(NOW(), ?, ?, ?, ?, ?, ?, '3', '0', ?)";
        $stmt = mysqli_prepare($con, $query_insert_database_lot);
        mysqli_stmt_bind_param($stmt, 'sssisii', $_POST['lot-name'],$_POST['message'], $file_url, $_POST['lot-rate'], $_POST['lot-date'], $_POST['lot-step'], $id_category);
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
    'css_calendar' => "<link href=\"css/flatpickr.min.css\" rel=\"stylesheet\">"
]);

print($layout_content);
