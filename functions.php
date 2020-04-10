<?php
function format_sum($number = 0)
{
    $price = ceil($number);
    $format_price = number_format($price, 0, ',', ' ');
    return $format_price .  " ₽";
}
function get_dt_range($value_date)
{
    $time_difference = strtotime($value_date) - time();
    $time_hours = floor($time_difference / 3600);
    $time_minutes = floor(($time_difference % 3600) / 60);
    return [$time_hours, $time_minutes];
}
function get_max_price_bids($prices, $price_start)
{
    if (!isset($prices[0]['price'])) {
        return $price_start;
    }
    $max_value = $prices[0]['price'];
    foreach ($prices as $price) {
        if ($max_value < $price['price']) {
            $max_value = $price['price'];
        }
    }
    return $max_value;
}
function get_id_category($categories)
{
    $id_category = NULL;
    foreach ($categories as $item) {
        if ($item['name'] === $_POST['category']) {
            $id_category = $item['id'];
        }
    }
    // если категорию не получилось опрелелить пусть лот попадает в категорию разное
    return $id_category ?? 6;
}
