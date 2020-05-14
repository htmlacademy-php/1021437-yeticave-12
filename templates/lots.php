<?php
require_once "functions.php";
require_once "helpers.php";
?>
<nav class="nav">
    <ul class="nav__list container" style="padding-left: 0;">
        <?php foreach ($categories as $category) : ?>
            <li class="nav__item <?php if (isset($current_category_code) && $current_category_code === htmlspecialchars($category["code"])) : ?>nav__item--current <? endif; ?>">
                <a href="all-lots.php?category=<?= htmlspecialchars($category["code"]); ?>"><?= htmlspecialchars($category["name"]); ?></a>
            </li>
        <? endforeach; ?>
    </ul>
</nav>
<section class="lots">
    <?php if (count($lots) > 0) : ?>
        <h2>Все лоты в категории <span>«<?= htmlspecialchars($current_category_name); ?>»</span></h2>
        <ul class="lots__list">
            <?php foreach ($lots as $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= htmlspecialchars($lot["image_link"]); ?>" width="350" height="260" alt="Сноуборд">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($lot["category"]); ?></span>
                        <h3 class="lot__title">
                            <a class="text-link" href="lot.php?id=<?= htmlspecialchars($lot["id"]); ?>"><?= htmlspecialchars($lot["name"]); ?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <?php if ((int)$lot["count_bets"] > 0) : ?>
                                    <span
                                        class="lot__amount"><?= htmlspecialchars($lot["count_bets"] . " " . get_noun_plural_form($lot["count_bets"], "ставка", "ставки", "ставок")); ?></span>
                                <? else: ?>
                                    <span class="lot__amount">Стартовая цена</span>
                                <? endif; ?>
                                <span class="lot__cost"><?= htmlspecialchars(format_sum($lot["price"])); ?></span>
                            </div>
                            <?php list($hours, $minutes) = get_dt_range($lot["ends_at"]); ?>
                            <div class="lot__timer timer <?php if ($hours < 1) : ?>timer--finishing<? endif; ?>">
                                <?= htmlspecialchars($hours . " : " . $minutes); ?>
                            </div>
                        </div>
                    </div>
                </li>
            <? endforeach; ?>
        </ul>
    <? else: ?>
        <h2>Нет лотов в категории <span>«<?= htmlspecialchars($current_category_name); ?>»</span></h2>
    <? endif; ?>
</section>
<?php echo render_pagination($count_lots, COUNT_ITEMS, $current_page, $page_count, $current_category_code, 'all-lots.php?category='); ?>
