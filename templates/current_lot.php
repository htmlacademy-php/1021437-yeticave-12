<?php
require_once "functions.php";
require_once "helpers.php";

$last_bet = (!empty($bids)) ? (int)$bids[0]["id"] : 0;
?>
<section class="lot-item container">
    <h2><?= htmlspecialchars($lot["name"], ENT_QUOTES); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= htmlspecialchars($lot["image_link"], ENT_QUOTES); ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot["category_name"], ENT_QUOTES); ?></span></p>
            <p class="lot-item__description">
                <?= htmlspecialchars($lot["description"], ENT_QUOTES); ?>
            </p>
        </div>
        <div class="lot-item__right">
            <?php #Не показываем при условиях: пользователь не авторизован; срок размещения лота истёк; лот создан текущим пользователем; последняя ставка сделана текущим пользователем ?>
            <?php if (!empty($user_id) && (!get_dt_end($lot["ends_at"])) && ($user_id) !== (int)$lot["author_id"] && $last_bet !== $user_id) : ?>
                <div class="lot-item__state">
                    <?php
                    list($hours, $minutes) = get_dt_range($lot["ends_at"]);
                    ?>
                    <div class="lot-item__timer timer <?= ($hours < 1) ? 'timer--finishing' : ''; ?>">
                        <?= "$hours : $minutes"; ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= htmlspecialchars(format_sum($current_price), ENT_QUOTES); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= htmlspecialchars(format_sum($lot["step_rate"] + $current_price), ENT_QUOTES); ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="lot.php?id=<?= $lot["id"]; ?>" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item <?= (isset($text_error)) ? 'form__item--invalid' : ''; ?> ">
                            <label for="cost">Ваша ставка</label>
                            <?php if (isset($bet_sum)) : ?>
                                <input id="cost" type="text" name="cost" placeholder="" value="<?= htmlspecialchars($bet_sum, ENT_QUOTES); ?>">
                            <?php else :?>
                                <input id="cost" type="text" name="cost" placeholder="<?= htmlspecialchars(format_sum($lot["step_rate"] + $current_price), ENT_QUOTES); ?>" value="">
                            <?php endif;?>
                            <span class="form__error"><?= $text_error ?? "" ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <?php endif; ?>
            <div class="history">
                <h3>История ставок (<span><?= htmlspecialchars(count($bids), ENT_QUOTES); ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bids as $bid) : ?>
                        <tr class="history__item">
                            <td class="history__name"><?= htmlspecialchars($bid["name"], ENT_QUOTES); ?></td>
                            <td class="history__price"><?= htmlspecialchars(format_sum($bid["price"]), ENT_QUOTES); ?></td>
                            <?php list($hours, $minutes) = get_dt_difference($bid["created_at"]); ?>
                            <?php if ($hours === 0) : ?>
                                <td class="history__time"><?= $minutes . " " . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . " назад"; ?></td>
                            <?php else : ?>
                                <td class="history__time"><?= $hours . " " . get_noun_plural_form($hours, 'часа', 'часа', 'часов') . " " . $minutes . " " . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . " назад"; ?></td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
