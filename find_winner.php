<?php
require_once "init.php";
$result = mysqli_query($con,
    "SELECT `id`, `author_id` FROM `lots` as lots WHERE lots.ends_at <= NOW() and lots.user_winner_id = 0");

for ($i = 0; $i < mysqli_num_rows($result); $i++) {
    $lot = mysqli_fetch_assoc($result);
    $current_lot = $lot["id"];
    //поиск последней ставки
    $find_bets = mysqli_query($con,
        "SELECT `user_id` FROM `bids` WHERE lot_id = " . $current_lot . " ORDER BY created_at DESC LIMIT 1");
    if (mysqli_num_rows($find_bets) > 0) {
        mysqli_query($con, "START TRANSACTION");
        $user_winner = mysqli_fetch_assoc($find_bets);
        $user_winner = $user_winner["user_id"];
        $update_winner = mysqli_query($con,
            "UPDATE `lots` SET `user_winner_id` = " . $user_winner . " WHERE id = " . $current_lot);
        if ($update_winner) {
            mysqli_query($con, "COMMIT");
            //Сценарий отправки письма тут
        } else {
            mysqli_query($con, "ROLLBACK");
        }

    } else {
        echo "NULL";
    }
}
