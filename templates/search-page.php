<?php
require_once "functions.php";
?>
<div class="container">
    <?php if (isset($empty_search)) : ?>
        <h2><?= $empty_search; ?></h2>
    <? else : ?>
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= htmlspecialchars($str_search); ?></span>»</h2>
            <ul class="lots__list">
                <?php foreach ($lots as $lot) : ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= htmlspecialchars($lot["image_link"]); ?>" width="350" height="260" alt="Сноуборд">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= htmlspecialchars($lot["category_name"]); ?></span>
                            <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= htmlspecialchars($lot["id"]); ?>"><?= htmlspecialchars($lot["name"]); ?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?= htmlspecialchars(format_sum($lot["price_start"])); ?></span>
                                </div>
                                <?php list($hours, $minutes) = get_dt_range($lot["ends_at"]); ?>
                                <div class="lot__timer timer <?php if ($hours < 1) : ?>timer--finishing<? endif; ?>">
                                    <?= htmlspecialchars($hours . ":" . $minutes); ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <? endforeach; ?>
            </ul>
        </section>
        <?php echo render_pagination($count_lots, COUNT_ITEMS, $current_page, $page_count, $str_search, "search.php?search=") ?>
    <? endif; ?>
</div>
