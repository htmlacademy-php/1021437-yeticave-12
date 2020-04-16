<?php
$con = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
if ($con === false) {
    print("Ошибка подключения: " . mysqli_connect_error());
    exit();
}

// установка кодировки
mysqli_set_charset($con, "utf8");
