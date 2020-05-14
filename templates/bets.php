<?php
require_once "helpers.php";
require_once "functions.php";
?>

<?php if (isset($error_bets)) : ?>
    <section class="rates container">
        <h2><?= $error_bets ?></h2>
    </section>
<? else : ?>
    <section class="rates container">
        <?php if (!empty($bets)) : ?>
            <h2>Мои ставки</h2>
            <table class="rates__list">
                <?php foreach ($bets as $bet) : ?>
                    <?php
                        $user_winner = (int)$bet["user_winner_id"] === $user_id ? true : false;
                        $lot_end = get_dt_end($bet["ends_at"]) ? true : false;
                    ?>
                    <tr class="rates__item <?php if ($user_winner) : ?> rates__item--win <? elseif ($lot_end) : ?>rates__item--end<? endif; ?>">
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src="<?= htmlspecialchars($bet["image_link"]);?>" width="54" height="40" alt="<?= htmlspecialchars($bet["category"]);?>">
                            </div>
                            <?php if ($user_winner) : ?>
                                <div>
                                    <h3 class="rates__title"><a href="lot.php?id=<?= htmlspecialchars($bet["id"]); ?>"><?= htmlspecialchars($bet["name"]); ?></a></h3>
                                    <p><?= htmlspecialchars($bet["users_info"]); ?></p>
                                </div>
                            <? else : ?>
                                <h3 class="rates__title"><a href="lot.php?id=<?= htmlspecialchars($bet["id"]); ?>"><?= htmlspecialchars($bet["name"]); ?></a></h3>
                            <? endif; ?>
                        </td>
                        <td class="rates__category">
                            <?= htmlspecialchars($bet["category"]);?>
                        </td>
                        <td class="rates__timer">
                            <?php if (!$user_winner && $lot_end) : ?>
                                <div class="timer timer--end">Торги окончены</div>
                            <? elseif (!$user_winner) : ?>
                                <?php list($hours_to_end, $minutes) = get_dt_range($bet["ends_at"]); ?>
                                <div class="timer <?php if ($hours_to_end < 1) : ?>timer--finishing<? endif; ?>">
                                    <?= htmlspecialchars($hours_to_end) . " : " . htmlspecialchars($minutes); ?>
                                </div>
                            <? else: ?>
                                <div class="timer timer--win">Ставка выиграла</div>
                            <? endif; ?>
                        </td>
                        <td class="rates__price">
                            <?= htmlspecialchars(format_sum($bet["price"])); ?>
                        </td>
                        <?php list($hours, $minutes) = get_dt_difference($bet["created_at"]); ?>

                        <td class="rates__time">
                            <?php if ($hours < 1) : ?>
                                <?= htmlspecialchars($minutes . " " . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . " назад"); ?></td>
                            <? else : ?>
                                <?= htmlspecialchars($hours . " " . get_noun_plural_form($hours, 'часа', 'часа', 'часов') . " " . $minutes . " " . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . " назад"); ?></td>
                            <? endif; ?>
                        </td>
                    </tr>
                <? endforeach; ?>
            </table>
            <?php echo render_pagination($count_lots, COUNT_ITEMS, $current_page, $page_count, '', 'my-bets.php?'); ?>
        <? else: ?>
            <h2>Вы не делали ещё ставок</h2>
        <? endif; ?>
    </section>

<? endif; ?>
