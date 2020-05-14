<section class="lot-item container">
    <h2>Код ошибки: <?= htmlspecialchars($code_error); ?></h2>
    <p><?= $text_error; ?></p>
    <?php if (isset($view_categories)): ?>
        <nav class="nav">
            <ul class="nav__list container">
                <?php foreach ($categories as $category) : ?>
                    <li class="nav__item <?php if (isset($current_category) && $current_category === htmlspecialchars($category["code"])) : ?>nav__item--current <? endif; ?>">
                        <a href="all-lots.php?category=<?= htmlspecialchars($category["code"]); ?>"><?= htmlspecialchars($category["name"]); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <? endif; ?>
</section>
