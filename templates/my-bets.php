<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $cat): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= esc($cat['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="rates container">
    <h2>Мои ставки</h2>
    <table class="rates__list">
        <?php foreach ($rates as $key => $rate): ?>
            <tr class="rates__item <?php if ($user['id'] == $rate['user_win_id']) echo ' rates__item--win';
            elseif (!($user['id'] == $rate['user_win_id']) && ($rate['user_win_id'])) echo ' rates__item--end';?>">
                <td class="rates__info">
                    <div class="rates__img">
                        <img src="<?= $rate['url']; ?>" width="54" height="40" alt="<?= $rate['name_l']; ?>">
                    </div>
                    <div>
                        <h3 class="rates__title"><a href="lot.html"><?= $rate['name_l']; ?></a></h3>
                        <p><?php if ($user['id'] == $rate['user_win_id']) echo $rate['contacts']; ?></p>
                    </div>
                </td>
                <td class="rates__category">
                    <?= $rate['name_c']; ?>
                </td>
                <td class="rates__timer">
                    <div class="timer <?php if (end_time($rate['dt_end'], 's') <= 86400 && !($rate['user_win_id'])) echo ' timer--finishing';
                    elseif ($user['id'] == $rate['user_win_id']) echo 'timer--win';
                    elseif (!($user['id'] == $rate['user_win_id']) && ($rate['user_win_id'])) echo 'timer--end'; ?>">
                        <?php if ($user['id'] == $rate['user_win_id']) echo 'Ставка выиграла';
                        elseif (!($user['id'] == $rate['user_win_id']) && ($rate['user_win_id'])) echo 'Торги окончены';
                        else echo(end_time($rate['dt_end'], null)); ?>
                    </div>
                </td>
                <td class="rates__price">
                    <?= f_price($rate['sum']); ?>
                </td>
                <td class="rates__time">
                    <?= $rate['dt_r']; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</section>