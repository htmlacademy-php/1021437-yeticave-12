<?php
require_once "init.php";
require_once "vendor/autoload.php";
require_once "helpers.php";
//$lots_info = mysqli_query($con, "SELECT
//    `id`,
//    `author_id`,
//    `name`
//    FROM `lots` AS lots
//    WHERE lots.ends_at <= NOW()
//    AND lots.user_winner_id = 0");

$lots_info = mysqli_query($con, "SELECT
        lots.id AS current_lot,
        lots.author_id,
        lots.name label_lot,
        users.email AS email_winner,
        users.name AS fio_winner,
        users.id AS user_id
    FROM `lots` AS lots
	JOIN `users` AS users
    ON users.id = (SELECT `user_id` FROM `bids` WHERE lot_id = lots.id ORDER BY created_at DESC LIMIT 1)
    WHERE lots.ends_at <= NOW()
    AND lots.user_winner_id = 0");

for ($i = 1; $i <= mysqli_num_rows($lots_info); $i++) {
    $lot = mysqli_fetch_assoc($lots_info);
    $current_lot = $lot["current_lot"];
    $lot_name = $lot["label_lot"];
    //поиск последней ставки
//    $find_bets = mysqli_query($con, "SELECT * FROM `users` WHERE id = (SELECT `user_id` FROM `bids` WHERE lot_id = " . $current_lot . " ORDER BY created_at DESC LIMIT 1)");
//    if (mysqli_num_rows($find_bets) > 0) {
//    $user_info = mysqli_fetch_assoc($find_bets);
    mysqli_query($con, "START TRANSACTION");
    $user_id_winner = $lot["user_id"];
    $update_winner = mysqli_query($con, "UPDATE `lots` SET `user_winner_id` = " . $user_id_winner . " WHERE id = " . $current_lot);
    $update_winner ? mysqli_query($con, "COMMIT") : mysqli_query($con, "ROLLBACK");

    $transport = new Swift_SmtpTransport(MAIL["host"], MAIL["port"], MAIL["encryption"]);
    $transport->setUsername(MAIL["username"]);
    $transport->setPassword(MAIL["password"]);
    $message = new Swift_Message("Ваша ставка победила");
    $message->setTo([$lot["email_winner"] => $lot["fio_winner"]]);
    $message->setFrom("admin@htmlacademy.ru", "YetiCave");
    $msg_content = include_template("email.php", [
        "user" => $lot["fio_winner"],
        "lot_id" => $current_lot,
        "lot_name" => $lot_name,
        "host_project" => $_SERVER["HTTP_HOST"],
    ]);
    $message->setBody($msg_content, 'text/html');
    // Отправка сообщения
    $mailer = new Swift_Mailer($transport);
    $send_mail = $mailer->send($message);
//    }
}
