<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $cat): ?>
            <li class="nav__item">
                <a href="all-lots.php?cat=<?= $cat['id']; ?>&name=<?= esc($cat['name']); ?>"><?= esc($cat['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?= $error_code; ?></h2>
    <p><?= $error; ?></p>
</section>