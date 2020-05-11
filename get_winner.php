<?php
require_once "init.php";
require_once "vendor/autoload.php";
require_once "helpers.php";

$lots_info = mysqli_query($con, "SELECT
        lots.id AS lot_id,
        lots.author_id,
        lots.name AS lot_name,
        users.email AS winner_email,
        users.name AS winner_name,
        users.id AS winner_id
    FROM `lots` AS lots
	JOIN `users` AS users
    ON users.id = (SELECT `user_id` FROM `bids` WHERE lot_id = lots.id ORDER BY created_at DESC LIMIT 1)
    WHERE lots.ends_at <= NOW()
    AND lots.user_winner_id = 0");

for ($i = 1; $i <= mysqli_num_rows($lots_info); $i++) {
    $lot = mysqli_fetch_assoc($lots_info);
    $current_lot = $lot["lot_id"];
    $lot_name = $lot["lot_name"];
    mysqli_query($con, "START TRANSACTION");
    $user_id_winner = $lot["winner_id"];
    $update_winner = mysqli_query($con, "UPDATE `lots` SET `user_winner_id` = " . $user_id_winner . " WHERE id = " . $current_lot);
    if ($update_winner) {
        mysqli_query($con, "COMMIT");
        $transport = new Swift_SmtpTransport(MAIL["host"], MAIL["port"], MAIL["encryption"]);
        $transport->setUsername(MAIL["username"]);
        $transport->setPassword(MAIL["password"]);
        $message = new Swift_Message("Ваша ставка победила");
        $message->setTo([$lot["winner_email"] => $lot["winner_name"]]);
        $message->setFrom("admin@htmlacademy.ru", "YetiCave");
        $msg_content = include_template("email.php", [
            "user" => $lot["winner_name"],
            "lot_id" => $current_lot,
            "lot_name" => $lot_name,
            "host_project" => $_SERVER["HTTP_HOST"],
        ]);
        $message->setBody($msg_content, 'text/html');
        $mailer = new Swift_Mailer($transport);
        $send_mail = $mailer->send($message);
    } else {
        mysqli_query($con, "ROLLBACK");
    }
}
