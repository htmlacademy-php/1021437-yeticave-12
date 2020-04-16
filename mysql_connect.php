<?php
$con = mysqli_connect("localhost", USER_NAME_CONNECT, USER_PASSWORD_CONNECT, "1021437-yeticave-12");
if ($con === false) {
    print("Ошибка подключения: " . mysqli_connect_error());
    exit();
}

