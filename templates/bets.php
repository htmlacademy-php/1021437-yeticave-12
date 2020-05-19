<?php
require_once "helpers.php";
require_once "functions.php";
?>

<?php if (isset($error_bets)) : ?>
    <section class="rates container">
        <h2><?= $error_bets; ?></h2>
    </section>
<?php else : ?>
    <section class="rates container">
        <?php if (!empty($bets)) : ?>
            <h2>Мои ставки</h2>
            <table class="rates__list">
                <?php foreach ($bets as $bet) : ?>
                    <?php
                        $user_winner = (int)$bet["user_winner_id"] === $user_id ? true : false;
                        $lot_end = get_dt_end($bet["ends_at"]) ? true : false;
                    ?>
                    <tr class="rates__item <?php if ($user_winner) : ?> rates__item--win <?php elseif ($lot_end) : ?>rates__item--end<?php endif; ?>">
                        <td class="rates__info">
                            <div class="rates__img">
                                <img src="<?= htmlspecialchars($bet["image_link"], ENT_QUOTES);?>" width="54" height="40" alt="<?= htmlspecialchars($bet["category"], ENT_QUOTES);?>">
                            </div>
                            <?php if ($user_winner) : ?>
                                <div>
                                    <h3 class="rates__title"><a href="lot.php?id=<?= $bet["id"]; ?>"><?= htmlspecialchars($bet["name"], ENT_QUOTES); ?></a></h3>
                                    <p><?= htmlspecialchars($bet["users_info"], ENT_QUOTES); ?></p>
                                </div>
                            <?php else : ?>
                                <h3 class="rates__title"><a href="lot.php?id=<?= $bet["id"]; ?>"><?= htmlspecialchars($bet["name"], ENT_QUOTES); ?></a></h3>
                            <?php endif; ?>
                        </td>
                        <td class="rates__category">
                            <?= htmlspecialchars($bet["category"], ENT_QUOTES);?>
                        </td>
                        <td class="rates__timer">
                            <?php if (!$user_winner && $lot_end) : ?>
                                <div class="timer timer--end">Торги окончены</div>
                            <?php elseif (!$user_winner) : ?>
                                <?php list($hours_to_end, $minutes) = get_dt_range($bet["ends_at"]); ?>
                                <div class="timer <?= ($hours_to_end < 1) ? 'timer--finishing' : ''; ?>">
                                    <?="$hours_to_end : $minutes"; ?>
                                </div>
                            <?php else : ?>
                                <div class="timer timer--win">Ставка выиграла</div>
                            <?php endif; ?>
                        </td>
                        <td class="rates__price">
                            <?= htmlspecialchars(format_sum($bet["price"]), ENT_QUOTES); ?>
                        </td>
                        <?php list($hours, $minutes) = get_dt_difference($bet["created_at"]); ?>

                        <td class="rates__time">
                            <?php if ($hours < 1) : ?>
                                <?= $minutes . " " . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . " назад"; ?>
                            <?php else : ?>
                                <?= $hours . " " . get_noun_plural_form($hours, 'часа', 'часа', 'часов') . " " . $minutes . " " . get_noun_plural_form($minutes, 'минута', 'минуты', 'минут') . " назад"; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
            <?php echo render_pagination($count_lots, COUNT_ITEMS, $current_page, $page_count, '', 'my-bets.php?'); ?>
        <?php else : ?>
            <h2>Вы не делали ещё ставок</h2>
        <?php endif; ?>
    </section>

<?php endif; ?>
