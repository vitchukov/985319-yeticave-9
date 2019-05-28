<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $cat): ?>
            <li class="nav__item">
                <a href="all-lots.php?cat=<?= $cat['id']; ?>&name=<?= esc($cat['name']); ?>"><?= esc($cat['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<div class="container">
    <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $search; ?></span>»</h2>
        <ul class="lots__list">
            <?php foreach ($lots as $key => $item): ?>
                <li class="lots__item lot">
                    <div class="lot__image">
                        <img src="<?= $item['url']; ?>" width="350" height="260" alt="">
                    </div>
                    <div class="lot__info">
                        <span class="lot__category"><?= esc($item['name']); ?></span>
                        <h3 class="lot__title"><a class="text-link"
                                                  href="/lot.php?id=<?= $item['id_l']; ?>"><?= esc($item['name_l']); ?></a>
                        </h3>
                        <div class="lot__state">
                            <div class="lot__rate">
                                <span class="lot__amount"><?php if ($item['MAX(r.sum)'] !== null) { echo 'Ставок ' . $item['count(r.id)'];} else { echo 'Стартовая цена'; } ?></span>
                                <span class="lot__cost"><?php if ($item['MAX(r.sum)'] !== null) { echo f_price($item['MAX(r.sum)']);} else { echo f_price($item['price']); } ?></span>
                            </div>
                            <div class="lot__timer timer<?php if ((end_time($item['dt_end'], 's') <= 3200) && ((end_time($item['dt_end'], 's') > 0))) { echo ' timer--finishing';}
                            elseif (end_time($item['dt_end'], 's') <= 0) { echo  ' timer--end'; } ?>">
                                <?php if (end_time($item['dt_end'], 's') > 0) { echo(end_time($item['dt_end'], null));}
                                elseif (end_time($item['dt_end'], 's') <= 0) { echo 'Торги завершены';} ?>
                            </div>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
    </section>
    <?php if ($pages_count > 1): ?>
    <ul class="pagination-list">
        <li class="pagination-item pagination-item-prev"><a
                    href="/search.php?search=<?= $search; ?>&page=<?php if ($cur_page > 1) { echo($cur_page - 1);} else { echo $cur_page;} ?>">Назад</a>
        </li>
        <?php foreach ($pages as $page): ?>
            <li class="pagination-item <?php if ($page === $cur_page): ?>pagination-item-active<?php endif; ?>">
                <a href="/search.php?search=<?= $search; ?>&page=<?= $page; ?>"><?= $page; ?></a>
            </li>
        <?php endforeach; ?>
        <li class="pagination-item pagination-item-next"><a
                    href="/search.php?search=<?= $search; ?>&page=<?php if ($cur_page < $pages_count) { echo($cur_page + 1);} else { echo $cur_page;} ?>">Вперед</a>
        </li>
    </ul>
    <? endif; ?>
</div>