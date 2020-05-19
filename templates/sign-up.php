<?php
require_once "functions.php";
?>
<form class="form container <?= (!empty($errors)) ? "form--invalid" : ""; ?>" action="registration.php" method="POST" autocomplete="off">
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= (!empty($errors["email"])) ? "form__item--invalid" : ""; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="email" name="email" placeholder="Введите e-mail" value="<?= htmlspecialchars(post_value("email"), ENT_QUOTES) ?>">
        <span class="form__error"><?= $errors["email"] ?? "" ?></span>
    </div>
    <div class="form__item <?= (!empty($errors["password"])) ? "form__item--invalid" : ""; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= htmlspecialchars(post_value("password"), ENT_QUOTES) ?>">
        <span class="form__error">Введите пароль</span>
    </div>
    <div class="form__item <?= (!empty($errors["name"])) ?  "form__item--invalid" : ""; ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?= htmlspecialchars(post_value("name"), ENT_QUOTES) ?>">
        <span class="form__error"><?= $errors["name"] ?? "" ?></span>
    </div>
    <div class="form__item <?= (!empty($errors["message"])) ? "form__item--invalid" : ""; ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= htmlspecialchars(post_value("message"), ENT_QUOTES) ?></textarea>
        <span class="form__error"><?= $errors["message"] ?? "" ?></span>
    </div>
    <span class="form__error <?= (!empty($errors)) ? "form__error--bottom" : ""; ?>">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="login.php">Уже есть аккаунт</a>
</form>
