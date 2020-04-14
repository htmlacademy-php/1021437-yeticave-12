<?php
date_default_timezone_set("Europe/Moscow");
require_once "functions.php";
?>

<form class="form container <?if(!empty($errors)) : ?>form--invalid<?endif;?>" action="registration.php" method="POST" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?if(!empty($errors['email'])) : ?> form__item--invalid <?endif;?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="email" name="email" placeholder="Введите e-mail" value="<?= htmlspecialchars(get_field_value('email'))?>">
        <span class="form__error"><?= ($errors['email']) ?? ''?></span>
    </div>
    <div class="form__item <?if(!empty($errors['password'])) : ?> form__item--invalid <?endif;?>"">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= htmlspecialchars(get_field_value('password'))?>">
        <span class="form__error">Введите пароль</span>
    </div>
    <div class="form__item <?if(!empty($errors['name'])) : ?> form__item--invalid <?endif;?>"">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= htmlspecialchars(get_field_value('name'))?>">
        <span class="form__error">Введите имя</span>
    </div>
    <div class="form__item <?if(!empty($errors['message'])) : ?> form__item--invalid <?endif;?>"">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= htmlspecialchars(get_field_value('message'))?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
    </div>
    <span class="form__error <?if(!empty($errors)) : ?>form__error--bottom<?endif;?>">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="#">Уже есть аккаунт</a>
</form>
