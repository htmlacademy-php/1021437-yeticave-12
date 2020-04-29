<p style="color:red;margin-left:17px;"><?= $text_errors ?? '' ?></p>
<form class="form container <?php if (!empty($errors)) : ?>form--invalid<? endif; ?>" action="login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?php if (!empty($errors["email"])) : ?>form__item--invalid <? endif; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="email" name="email" placeholder="Введите e-mail" value="<?= $email; ?>">
        <span class="form__error"><?= $errors["email"] ?? '' ?></span>
    </div>
    <div
        class="form__item form__item--last  <?php if (!empty($errors["password"])) : ?>form__item--invalid <? endif; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= $password; ?>">
        <span class="form__error"><?= $errors["password"] ?? '' ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
