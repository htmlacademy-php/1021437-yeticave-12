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
        function render_pagination_button($path_button, $text_button, $class_important="", $disable=false, $text_search=NULL, $current_page=NULL)
        {
            if($disable) {
                $disable = "style='pointer-events: none;'";
            }
            $string_template =  '<li %1$s class="pagination-item %2$s"><a href="%3$s%4$s&page=%5$d">%6$s</a></li>';
            return sprintf($string_template, $disable, $class_important, $path_button, $text_search, $current_page, $text_button);
        }
        // функция пагинации
        function render_pagination($all_lots, $value_items, $current_page, $pages, $str_search)
        {
            if ($all_lots > $value_items) {
                $pagination = "<ul class='pagination-list'>";

                if ($current_page === 1) {
                    $pagination .= render_pagination_button("#", "Назад", "pagination-item-prev", true);
                } else {
                    $pagination .= render_pagination_button("search.php?search=", "Назад", "pagination-item-prev", false, $str_search, $current_page - 1);
                }

                for ($i = 1; $i <= $pages; $i++) {
                    if ($current_page === $i) {
                        $pagination .= render_pagination_button("#", $i, "pagination-item-active", true);
                    } else {
                        $pagination .= render_pagination_button("search.php?search=", $i,  "", false, $str_search, $i);
                    }
                }

                if ($pages > $current_page) {
                    $pagination .= render_pagination_button("search.php?search=", "Вперед",  "pagination-item-next", false, $str_search, $current_page + 1);
                } else {
                    $pagination .= render_pagination_button("#", "Вперед", "pagination-item-next", true);
                }
                return $pagination .= "</ul>";
            }
            return false;
        }
        ?>
        <?php echo render_pagination($count_lots, COUNT_ITEMS, $current_page, $page_count, $str_search)?>
    <?endif;?>
</div>
