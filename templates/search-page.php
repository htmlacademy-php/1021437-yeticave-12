<?php
require_once "functions.php";
?>
<div class="container">
    <?php if(isset($empty_search)) : ?>
        <h2><?= $empty_search;?></h2>
    <? else : ?>
        <section class="lots">
            <h2>Результаты поиска по запросу «<span><?= $str_search;?></span>»</h2>
            <ul class="lots__list">
                <?php foreach ($lots as $lot) : ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $lot["image_link"]; ?>" width="350" height="260" alt="Сноуборд">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= $lot["category_name"]; ?></span>
                        <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?=$lot["id"];?>"><?= $lot["name"]; ?></a></h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount">Стартовая цена</span>
                                <span class="lot__cost"><?= format_sum($lot["price_start"]); ?></span>
                            </div>
                            <?php list($hours, $minutes) = get_dt_range($lot["ends_at"]);?>
                            <div class="lot__timer timer <?php if ($hours < 1) : ?>timer--finishing<?php endif;?>">
                                <?= $hours . ":" . $minutes;?>
                            </div>
                        </div>
                    </div>
                </li>
                <?endforeach;?>
            </ul>
        </section>
        <?php if($count_lots > COUNT_ITEMS) : ?>
        <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a>Назад</a></li>
            <li class="pagination-item pagination-item-active"><a>1</a></li>
            <li class="pagination-item"><a href="#">2</a></li>
            <li class="pagination-item"><a href="#">3</a></li>
            <li class="pagination-item"><a href="#">4</a></li>
            <li class="pagination-item pagination-item-next"><a href="#">Вперед</a></li>
        </ul>
        <?endif;?>
    <?endif;?>
</div>
