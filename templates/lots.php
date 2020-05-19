<?php
require_once "functions.php";
require_once "helpers.php";
?>
<nav class="nav">
    <ul class="nav__list container" style="padding-left: 0;">
        <?php foreach ($categories as $category) : ?>
            <li class="nav__item <?= (isset($current_category_code) && $current_category_code === $category["code"]) ? 'nav__item--current' : "" ?>">
                <a href="all-lots.php?category=<?= htmlspecialchars($category["code"], ENT_QUOTES); ?>"><?= htmlspecialchars($category["name"], ENT_QUOTES); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lots">
    <?php if (count($lots) > 0) : ?>
        <h2>Все лоты в категории <span>«<?= htmlspecialchars($current_category_name, ENT_QUOTES); ?>»</span></h2>
        <ul class="lots__list">
            <?php foreach ($lots as $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= htmlspecialchars($lot["image_link"], ENT_QUOTES); ?>" width="350" height="260" alt="Сноуборд">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($lot["category"], ENT_QUOTES); ?></span>
                        <h3 class="lot__title">
                            <a class="text-link" href="lot.php?id=<?= $lot["id"]; ?>"><?= htmlspecialchars($lot["name"], ENT_QUOTES); ?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <?php if ((int)$lot["count_bets"] > 0) : ?>
                                    <span
                                        class="lot__amount"><?= $lot["count_bets"] . " " . get_noun_plural_form($lot["count_bets"], "ставка", "ставки", "ставок"); ?></span>
                                <?php else : ?>
                                    <span class="lot__amount">Стартовая цена</span>
                                <?php endif; ?>
                                <span class="lot__cost"><?= htmlspecialchars(format_sum($lot["price"]), ENT_QUOTES); ?></span>
                            </div>
                            <?php list($hours, $minutes) = get_dt_range($lot["ends_at"]); ?>
                            <div class="lot__timer timer <?= ($hours < 1) ? 'timer--finishing' : ''; ?>">
                                <?= "$hours : $minutes"; ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else : ?>
        <h2>Нет лотов в категории <span>«<?= htmlspecialchars($current_category_name, ENT_QUOTES); ?>»</span></h2>
    <?php endif; ?>
</section>
<?php echo render_pagination($count_lots, COUNT_ITEMS, $current_page, $page_count, $current_category_code, 'all-lots.php?category='); ?>
