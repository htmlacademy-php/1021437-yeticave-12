<?php
date_default_timezone_set("Europe/Moscow");
require_once "mysql_connect.php";
// блок проверок валидации формы добавление лота
function get_field_value($field_name)
{
    return $_POST[$field_name] ?? "";
}
$classname_field_name = isset($errors['lot-name']) ? 'form__item--invalid' : '';
$classname_field_category = isset($errors['category']) ? 'form__item--invalid' : '';
$classname_field_message = isset($errors['message']) ? 'form__item--invalid' : '';
$classname_field_price = isset($errors['lot-rate']) ? 'form__item--invalid' : '';
$classname_field_step_price = isset($errors['lot-step']) ? 'form__item--invalid' : '';
$classname_field_date = isset($errors['lot-date']) ? 'form__item--invalid' : '';
$classname_field_image = isset($errors['lot-img']) ? 'form__item--invalid' : '';
?>
<form class="form form--add-lot container <?php if (count($errors)) : ?>form--invalid<?endif;?>" action="add.php" method="POST" enctype="multipart/form-data">
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?=$classname_field_name;?>">
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="<?= htmlspecialchars(get_field_value('lot-name'))?>">
            <span class="form__error"><?=$errors['lot-name'] ?? '';?></span>
        </div>
        <div class="form__item <?=$classname_field_category;?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category">
                <option value="">Выберите категорию</option>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['id'];?>" <?php if ($category['id'] === $_POST['category']) : ?>selected<?endif;?>><?= $category['name'];?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?=$errors['category'] ?? '';?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?=$classname_field_message;?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите описание лота"><?= htmlspecialchars(get_field_value('message'));?></textarea>
        <span class="form__error"><?=$errors['message'] ?? '';?></span>
    </div>
    <div class="form__item form__item--file <?=$classname_field_image;?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" name="lot-img" type="file" id="lot-img" value="">
            <label for="lot-img">
                Добавить
            </label>
        </div>
        <span class="form__error"><?=$errors['lot-img'] ?? '';?></span>
    </div>
    <div class="form__container-three">
        <div class="form__item form__item--small <?=$classname_field_price;?>">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="lot-rate" placeholder="0" value="<?= htmlspecialchars(get_field_value('lot-rate'));?>">
            <span class="form__error"><?=$errors['lot-rate'] ?? '';?></span>
        </div>
        <div class="form__item form__item--small <?=$classname_field_step_price;?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="lot-step" placeholder="0" value="<?= htmlspecialchars(get_field_value('lot-step'));?>">
            <span class="form__error"><?=$errors['lot-step'] ?? '';?></span>
        </div>
        <div class="form__item <?=$classname_field_date;?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="lot-date" value="<?= htmlspecialchars(get_field_value('lot-date'));?>" placeholder="Введите дату в формате ГГГГ-ММ-ДД">
            <span class="form__error"><?=$errors['lot-date'] ?? ''?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
