<p style="color:red;margin-left:17px;"><?= $text_errors ?? "" ?></p>
<form class="form container <?= (!empty($errors)) ? 'form--invalid' : ''; ?>" action="login.php" method="post">
    <h2>Вход</h2>
    <div class="form__item <?= (!empty($errors["email"])) ? 'form__item--invalid' : ''; ?>">
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="email" name="email" placeholder="Введите e-mail" value="<?= htmlspecialchars($email, ENT_QUOTES); ?>">
        <span class="form__error"><?= $errors["email"] ?? "" ?></span>
    </div>
    <div
        class="form__item form__item--last  <?= (!empty($errors["password"])) ? 'form__item--invalid' : ''; ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль" value="<?= htmlspecialchars($password, ENT_QUOTES); ?>">
        <span class="form__error"><?= $errors["password"] ?? "" ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>
