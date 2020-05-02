<?php
require_once "init.php";
require_once "vendor/autoload.php";
require_once "helpers.php";

$lots_info = mysqli_query($con, "SELECT `id`, `author_id`, `name` FROM `lots` as lots WHERE lots.ends_at <= NOW() and lots.user_winner_id = 0");

for ($i = 1; $i <= mysqli_num_rows($lots_info); $i++) {
    $lot = mysqli_fetch_assoc($lots_info);
    $current_lot = $lot["id"];
    $lot_name = $lot["name"];
    //поиск последней ставки
    $find_bets = mysqli_query($con, "SELECT `user_id` FROM `bids` WHERE lot_id = " . $current_lot . " ORDER BY created_at DESC LIMIT 1");
    if (mysqli_num_rows($find_bets) > 0) {
        $user_winner = mysqli_fetch_assoc($find_bets);
        mysqli_query($con, "START TRANSACTION");
        $user_winner = $user_winner["user_id"];
        $user_info = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `users` WHERE id = " . $user_winner));
        $update_winner = mysqli_query($con, "UPDATE `lots` SET `user_winner_id` = " . $user_winner . " WHERE id = " . $current_lot);
        $transport = new Swift_SmtpTransport(MAIL["host"], MAIL["port"], MAIL["encryption"]);
        $transport->setUsername(MAIL["username"]);
        $transport->setPassword(MAIL["password"]);
        $message = new Swift_Message("Ваша ставка победила");
        $message->setTo([$user_info["email"] => $user_info["name"]]);
        $message->setFrom("admin@htmlacademy.ru", "YetiCave");
        $msg_content = include_template("email.php", [
            "user" => $user_info,
            "lot_id" => $current_lot,
            "lot_name" => $lot_name,
        ]);
        $message->setBody($msg_content, 'text/html');
        // Отправка сообщения
        $mailer = new Swift_Mailer($transport);
        $send_mail = $mailer->send($message);
        if ($send_mail) {
            mysqli_query($con, "COMMIT");
        } else {
            mysqli_query($con, "ROLLBACK");
        }
    }
}
