<?php
require_once "functions.php";
?>
<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">
        На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.
    </p>
    <ul class="promo__list">
        <?php foreach ($categories as $category) : ?>
            <li class="promo__item promo__item--<?= htmlspecialchars($category["code"]); ?>">
                <a class="promo__link"
                   href="all-lots.php?category=<?= htmlspecialchars($category["code"]); ?>"><?= htmlspecialchars($category["name"]); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</section>
<section class="lots">
    <?php if (!empty($lots)): ?>
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?php foreach ($lots as $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= htmlspecialchars($lot["image_link"]); ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= htmlspecialchars($lot["category_name"]) ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= htmlspecialchars($lot["id"]); ?>"><?= htmlspecialchars($lot["name"]) ?></a></h3>
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
    <? else: ?>
        <div class="lots__header">
            <h2>Нет открытых лотов</h2>
        </div>
    <? endif; ?>
</section>
