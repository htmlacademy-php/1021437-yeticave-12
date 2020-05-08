<?php
require_once "init.php";
require_once "helpers.php";
require_once "validate_functions.php";
require_once "vendor/autoload.php";
use Imagine\Image\Box;

if (!isset($_SESSION["user"])) {
    http_response_code(403);
    $page_content = include_template("error.php", [
        "categories" => $categories,
        "code_error" => "403",
        "text_error" => "Страница доступна только зарегистрированным пользователям"
    ]);
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {
    $errors = [];
    $lot_name = post_value("lot-name");
    $message = post_value("message");
    $lot_rate = post_value("lot-rate");
    $category = post_value("category");
    $lot_step = post_value("lot-step");
    $lot_date = post_value("lot-date");

    $errors = validate(
        [
            "lot-name" => $lot_name,
            "message" => $message,
            "lot-rate" => $lot_rate,
            "category" => $category,
            "lot-step" => $lot_step,
            "lot-date" => $lot_date,
        ],
        [
            "lot-name" => [
                not_empty(),
                validate_field_text(255),
            ],
            "message" => [
                not_empty(),
            ],
            "lot-rate" => [
                not_empty(),
                it_is_number(),
                validate_field_price(),
            ],
            "category" => [
                not_empty(),
            ],
            "lot-step" => [
                not_empty(),
                it_is_number(),
                validate_field_price(),
            ],
            "lot-date" => [
                not_empty(),
                validate_field_date(),
            ],
        ]
    );

    if (empty($_FILES["lot-img"]["name"])) {
        $errors["lot-img"] = "Файл обязателен для загрузки";
    } else {
        $id_image = uniqid();
        $file_name = $id_image . $_FILES["lot-img"]["name"];
        $type_file = mime_content_type($_FILES["lot-img"]["tmp_name"]);
        if ($type_file !== "image/jpeg" && $type_file !== "image/png") {
            $errors["lot-img"] = "Поддерживается загрузка только png, jpg, jpeg " . $type_file;
        } else {
            move_uploaded_file($_FILES["lot-img"]["tmp_name"], PATH_UPLOADS_IMAGE . $file_name);
            if ($_FILES["lot-img"]["error"] !== UPLOAD_ERR_OK) {
                return "Ошибка при загрузке файла - код ошибки: " . $_FILES["lot-img"]["error"];
            }
            /**
             * Обрезаем картинку лота
             */
            $imagine = new Imagine\Gd\Imagine();
            $img = $imagine->open(PATH_UPLOADS_IMAGE . $file_name);
            $img->resize(new Box(IMAGE_PARAMETERS["width"], IMAGE_PARAMETERS["height"]));
            /**
             * Добавляем watermark
             */
            $watermark = $imagine->open('img/logo.png');
            $size = $img->getSize();
            $wSize = $watermark->getSize();
            $bottomRight = new Imagine\Image\Point($size->getWidth() - $wSize->getWidth(), $size->getHeight() - $wSize->getHeight());
            $img->paste($watermark, $bottomRight);
            /**
             * Сохранение изображения
             */
            $img->save(PATH_UPLOADS_IMAGE . $file_name, IMAGE_QUALITY);
        }
    }

    //если ошибок нету
    if (!count($errors)) {
        $file_url = PATH_UPLOADS_IMAGE . $id_image . $_FILES["lot-img"]["name"];
        $query_insert_database_lot = "INSERT INTO `lots` (
            `created_at`,
            `name`,
            `description`,
            `image_link`,
            `price_start`,
            `ends_at`,
            `step_rate`,
            `author_id`,
            `user_winner_id`,
            `category_id`
        )
        VALUES(
            NOW(), ?, ?, ?, ?, ?, ?, ?, '0', ?)";
        $stmt = db_get_prepare_stmt($con, $query_insert_database_lot, [
            $_POST["lot-name"],
            $_POST["message"],
            $file_url,
            $_POST["lot-rate"],
            $_POST["lot-date"],
            $_POST["lot-step"],
            $_SESSION["user"]["id"],
            $_POST["category"]
        ]);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            $last_id = mysqli_insert_id($con);
            // переадресация на страницу с новым добавленным лотом
            header("Location: lot.php?id=" . $last_id);
        } else {
            echo "Ошибка вставки " . mysqli_error($con);
        }
    } else {
        $page_content = include_template("add-lot.php", [
            "categories" => $categories,
            "errors" => $errors,
            "id_category" => post_value("category", null),
        ]);
    }
} else {
    $page_content = include_template("add-lot.php", [
        "categories" => $categories,
    ]);
}

$layout_content = include_template("layout.php", [
    "main_content" => $page_content,
    "title_page" => "Добавление нового лота",
    "user_name" => session_user_value("name", ""),
    "categories" => $categories,
    "css_calendar" => "<link href=\"css/flatpickr.min.css\" rel=\"stylesheet\">"
]);

print($layout_content);
