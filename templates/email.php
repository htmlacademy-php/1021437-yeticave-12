<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= htmlspecialchars($user, ENT_QUOTES);?></p>
<p>Ваша ставка для лота <a href="http://<?= $host_project; ?>/lot.php?id=<?= $lot_id; ?>"><?= htmlspecialchars($lot_name, ENT_QUOTES); ?></a> победила.</p>
<p>Перейдите по ссылке <a href="http://<?= $host_project; ?>/my-bets.php">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>
