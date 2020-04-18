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
            <?php if(intval($current_page) === 1) : ?>
                <li style="pointer-events: none;" class="pagination-item pagination-item-prev"><a href="#" style="color:#fff;">Назад</a></li>
            <?else : ?>
                <li class="pagination-item pagination-item-prev"><a href="<?= "search.php?search=" . $_GET["search"] . "&page=" . ($_GET["page"] - 1);?>">Назад</a></li>
            <?endif;?>
            <?php foreach ($pages_number as $item) : ?>
                <li class="pagination-item <?php if (intval($current_page) === intval($item)): ?>pagination-item-active <?endif;?>"><a href="<?php if (isset($_GET["page"])) : ?><?= "search.php?search=" . $_GET["search"] . "&page=".$item;?><?else : ?><?=$_SERVER["REQUEST_URI"]."&page=".$item?><?endif;?>"><?=$item;?></a></li>
            <?endforeach;?>
            <?php if(count($pages_number) > $current_page) : ?>
                <li class="pagination-item pagination-item-next"><a href="<?php if(isset($_GET["page"])) : ?><?="search.php?search=" . $_GET["search"] . "&page=" . ($_GET["page"] + 1);?><?else : ?><?="search.php?search=" . $_GET["search"] . "&page=2";?><?endif;?>">Вперед</a></li>
            <?else : ?>
                <li style="pointer-events: none;" class="pagination-item pagination-item-next"><a style="color:#fff;" href="">Вперед</a></li>
            <?endif;?>
        </ul>
        <?endif;?>
    <?endif;?>
</div>
