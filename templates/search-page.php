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
    <?php
        function render_pagination($name_class_default, $name_important=NULL, $path, $text_button, $text_search=NULL, $current_page=NULL)
        {
            $disable_style = "style='pointer-events: none;'";
            $block_pagination = "<li class='" . $name_class_default . " ";
            if(isset($name_important) && $path === '#') {
                return $block_pagination . $name_important . "'" . "><a href='#'" . $disable_style . ">". $text_button ."</a></li>";
            }
            switch ($text_button) {
                case 'Назад':
                    return $block_pagination . $name_important . "'><a href='" . $path . $text_search .  "&page="  . ($current_page - 1) . "'>Назад</a></li>";
                case 'Вперед':
                    return $block_pagination . $name_important . "'><a href='" . $path . $text_search .  "&page="  . ($current_page + 1) . "'>Вперед</a></li>";
                default:
                    return $block_pagination . "'><a href='" . $path . $text_search .  "&page="  . $current_page . "'>" . $current_page . "</a></li>";
            }
            return $block_pagination;
        }

// функция пагинации
        function get_pagination($all_lots, $value_items, $current_page, $pages, $str_search)
        {
            if ($all_lots > $value_items) {
                $pagination = "<ul class='pagination-list'>";

                if ($current_page === 1) {
                    $pagination .= render_pagination("pagination-item", "pagination-item-prev", "#", "Назад");
                } else {
                    $pagination .= render_pagination ("pagination-item", "pagination-item-prev", "search.php?search=", "Назад", $str_search, $current_page);
                }

                for ($i = 1; $i <= $pages; $i++) {
                    if ($current_page === $i) {
                        $pagination .= render_pagination("pagination-item", "pagination-item-active", "#", $i);
                    } else {
                        $pagination .= render_pagination("pagination-item", NULL, "search.php?search=", $i, $str_search, $i);
                    }
                }

                if ($pages > $current_page) {
                    $pagination .= render_pagination ("pagination-item", "pagination-item-next", "search.php?search=", "Вперед", $str_search, $current_page);
                } else {
                    $pagination .= render_pagination("pagination-item", "pagination-item-next", "#", "Вперед");

                }
                return $pagination .= "</ul>";
            }

            return false;
        }
        ?>
        <?php echo get_pagination($count_lots, COUNT_ITEMS, $current_page, $page_count, $str_search)?>
    <?endif;?>
</div>
