<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $cat): ?>
            <li class="nav__item">
                <a href="pages/all-lots.html"><?= esc($cat['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<section class="lot-item container">
    <h2><?= esc($lot['name_l']) ?></h2>
    <div class="lot-item__content">
        <div class="lot-item__left">
            <div class="lot-item__image">
                <img src="<?= $lot['url']; ?>" width="730" height="548" alt="Сноуборд">
            </div>
            <p class="lot-item__category">Категория: <span><?= esc($lot['name_c']); ?></span></p>
            <p class="lot-item__description"><?= esc($lot['descr']); ?></p>
        </div>
        <div class="lot-item__right">
            <?php if (isset($_SESSION['user'])): ?>
                <div class="lot-item__state">
                    <div class="lot-item__timer timer<?php if ($secs <= 3600) echo ' timer--finishing'; ?>">
                        <?= $tend; ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?php if ($lot['MAX(r.sum)'] !== null) echo f_price($lot['MAX(r.sum)']); else echo f_price($lot['price']); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка
                            <span><?php if ($lot['MAX(r.sum)'] !== null) echo f_price($lot['MAX(r.sum)'] + $lot['step']); else echo f_price($lot['price'] + $lot['step']); ?></span>
                        </div>
                    </div>
                    <form class="lot-item__form" action="" method="post" autocomplete="off">
                        <p class="lot-item__form-item form__item <?= isset($errors['rate']) ? "form__item--invalid" : "" ?>">
                            <label for="cost">Ваша ставка</label>
                            <input id="cost" type="text" name="rate"
                                   placeholder="<?php if ($lot['MAX(r.sum)'] !== null) echo $lot['MAX(r.sum)'] + $lot['step']; else echo $lot['price'] + $lot['step']; ?>"
                                   value="<?php if ($lot['MAX(r.sum)'] !== null) echo ($lot['MAX(r.sum)'] + $lot['step']); else echo ($lot['price'] + $lot['step']); ?>">
                            <span class="form__error"><?php if (isset($errors['rate'])) echo $errors['rate'] ?></span>
                        </p>
                        <button type="submit" class="button">Сделать ставку</button>
                    </form>
                </div>
            <? endif; ?>
            <div class="history">
                <h3>История ставок (<span>10</span>)</h3>
                <table class="history__list">
                    <tr class="history__item">
                        <td class="history__name">Иван</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">5 минут назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Константин</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">20 минут назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Евгений</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">Час назад</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Игорь</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 08:21</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Енакентий</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 13:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Семён</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 12:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Илья</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 10:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Енакентий</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 13:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Семён</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 12:20</td>
                    </tr>
                    <tr class="history__item">
                        <td class="history__name">Илья</td>
                        <td class="history__price">10 999 р</td>
                        <td class="history__time">19.03.17 в 10:20</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</section>