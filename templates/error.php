<section class="lot-item container">
    <h2>Код ошибки: <?= $code_error; ?></h2>
    <p><?= $text_error; ?></p>
    <?php if (isset($view_categories)) : ?>
        <nav class="nav">
            <ul class="nav__list container">
                <?php foreach ($categories as $category) : ?>
                    <li class="nav__item">
                        <a href="all-lots.php?category=<?= htmlspecialchars($category["code"], ENT_QUOTES); ?>"><?= htmlspecialchars($category["name"], ENT_QUOTES); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    <?php endif; ?>
</section>
