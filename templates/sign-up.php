<?php
require_once "functions.php";
?>
<form class="form container <?php if (!empty($errors)) : ?>form--invalid<? endif; ?>" action="registration.php" method="POST" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?php if (!empty($errors["email"])) : ?> form__item--invalid <? endif; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="email" name="email" placeholder="Введите e-mail" value="<?= htmlspecialchars(post_value("email")) ?>">
        <span class="form__error"><?= $errors["email"] ?? "" ?></span>
    </div>
    <div class="form__item <?php if (!empty($errors["password"])) : ?> form__item--invalid <? endif; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= htmlspecialchars(post_value("password")) ?>">
        <span class="form__error">Введите пароль</span>
    </div>
    <div class="form__item <?php if (!empty($errors["name"])) : ?> form__item--invalid <? endif; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= htmlspecialchars(post_value("name")) ?>">
        <span class="form__error"><?= $errors["name"] ?? "" ?></span>
    </div>
    <div class="form__item <?php if (!empty($errors["message"])) : ?> form__item--invalid <? endif; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= htmlspecialchars(post_value("message")) ?></textarea>
        <span class="form__error">Напишите как с вами связаться</span>
    </div>
    <span class="form__error <?php if (!empty($errors)) : ?>form__error--bottom<? endif; ?>">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
