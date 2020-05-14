<?php
require_once "functions.php";
require_once "helpers.php";
$current_price = get_max_price_bids($bids, $lot["price_start"]);
$last_bet = (!empty($bids)) ? (int)$bids[0]["id"] : 0;
?>
<section class="lot-item container">
    <h2><?= htmlspecialchars($lot["name"]); ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= htmlspecialchars($lot["image_link"]); ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot["category_name"]); ?></span></p>
            <p class="lot-item__description">
                <?= htmlspecialchars($lot["description"]); ?>
            </p>
        </div>
        <div class="lot-item__right">
            <?php #Не показываем при условиях: пользователь не авторизован; срок размещения лота истёк; лот создан текущим пользователем; последняя ставка сделана текущим пользователем ?>
            <?php if (isset($_SESSION["user"]) && (!get_dt_end($lot["ends_at"])) && ($_SESSION["user"]["id"]) !== (int)$lot["author_id"] && $last_bet !== $_SESSION["user"]["id"]) : ?>
                <div class="lot-item__state">
                    <?php
                    list($hours, $minutes) = get_dt_range($lot["ends_at"]);
                    ?>
                    <div class="lot-item__timer timer <?php if ($hours < 1) : ?>timer--finishing<? endif; ?>">
                        <?= htmlspecialchars($hours . ":" . $minutes); ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= htmlspecialchars(format_sum($current_price)); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= htmlspecialchars(format_sum($lot["step_rate"] + $current_price)); ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="lot.php?id=<?= htmlspecialchars($lot["id"]); ?>" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item <?php if (isset($text_error)) : ?>form__item--invalid<? endif; ?> ">
                            <label for="cost">Ваша ставка</label>
                            <?php if (isset($bet_sum)) : ?>
                                <input id="cost" type="text" name="cost" placeholder="" value="<?= htmlspecialchars($bet_sum); ?>">
                            <?else:?>
                                <input id="cost" type="text" name="cost" placeholder="<?= htmlspecialchars(format_sum($lot["step_rate"] + $current_price)); ?>" value="">
                            <?endif;?>
                            <span class="form__error"><?= $text_error ?? "" ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <? endif; ?>
            <div class="history">
                <h3>История ставок (<span><?= htmlspecialchars(count($bids)); ?></span>)</h3>
                <table class="history__list">
                    <?php foreach ($bids as $bid) : ?>
                        <tr class="history__item">
                            <td class="history__name"><?= htmlspecialchars($bid["name"]); ?></td>
                            <td class="history__price"><?= htmlspecialchars(format_sum($bid["price"])); ?></td>
                            <?php list($hours, $minutes) = get_dt_difference($bid["created_at"]); ?>
                            <?php if ($hours === 0) : ?>
                                <td class="history__time"><?= htmlspecialchars($minutes . " " . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . " назад"); ?></td>
                            <? else : ?>
                                <td class="history__time"><?= htmlspecialchars($hours . " " . get_noun_plural_form($hours, 'часа', 'часа', 'часов') . " " . $minutes . " " . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . " назад"); ?></td>
                            <? endif; ?>
                        </tr>
                    <? endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
