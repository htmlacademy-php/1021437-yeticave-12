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
function check_field($field)
{
    if (empty($field)) {
        return "Это поле обязательно к заполнению";
    }
}
// состояние пользователя
$is_auth = rand(0, 1);
// блок проверок валидации формы добавление лота
function get_field_value($field_name)
{
    return $_POST[$field_name] ?? "";
}
