<?php
$con = mysqli_connect('localhost', 'root', '9ZOYcLpeEb8y1Zmr', '1021437-yeticave-12');
if ($con === false) {
    print("Ошибка подключения: " . mysqli_connect_error());
    exit();
}
// установка кодировки
mysqli_set_charset($con, "utf8");
