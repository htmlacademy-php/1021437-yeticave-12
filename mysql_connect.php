<?php
$con = mysqli_connect(HOST_FOR_CONNECT, USER_NAME_CONNECT, USER_PASSWORD_CONNECT, CURRENT_DATABASE);
if ($con === false) {
    print("Ошибка подключения: " . mysqli_connect_error());
    exit();
}

// установка кодировки
mysqli_set_charset($con, "utf8");
