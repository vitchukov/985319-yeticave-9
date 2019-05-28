<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $cat): ?>
            <li class="nav__item">
                <a href="all-lots.php?cat=<?= $cat['id']; ?>&name=<?= $cat['name']; ?>"><?= esc($cat['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<form class="form form--add-lot container <?= !empty($errors) ? 'form--invalid' : "" ?>" action="add.php" method="post"
      enctype="multipart/form-data"> <!-- form--invalid -->
    <h2>Добавление лота</h2>
    <div class="form__container-two">
        <div class="form__item <?= isset($errors['name']) ? "form__item--invalid" : "" ?>"> <!-- form__item--invalid -->
            <label for="lot-name">Наименование <sup>*</sup></label>
            <input id="lot-name" type="text" name="name" placeholder="Введите наименование лота"
                   value="<?php if (isset($lot['name'])) echo $lot['name'] ?>">
            <span class="form__error"><?php if (isset($errors['name'])) echo $errors['name'] ?></span>
        </div>
        <div class="form__item <?= isset($errors['category']) ? "form__item--invalid" : "" ?>">
            <label for="category">Категория <sup>*</sup></label>
            <select id="category" name="category" value="<?php if (isset($lot['category'])) echo $lot['category'] ?>">
                <option>Выберите категорию</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?php if (isset($lot['category']) && $lot['category'] === $cat['id']) echo 'selected' ?>><?= $cat['name']; ?></option>
                <?php endforeach; ?>
            </select>
            <span class="form__error"><?php if (isset($errors['category'])) echo $errors['category'] ?></span>
        </div>
    </div>
    <div class="form__item form__item--wide <?= isset($errors['descr']) ? "form__item--invalid" : "" ?>">
        <label for="message">Описание <sup>*</sup></label>
        <textarea id="message" name="descr" placeholder="Напишите описание лота"
                  ><?php if (isset($lot['descr'])) echo $lot['descr'] ?></textarea>
        <span class="form__error"><?php if (isset($errors['descr'])) echo $errors['descr'] ?></span>
    </div>
    <div class="form__item form__item--file <?= isset($errors['url']) ? "form__item--invalid" : "" ?>">
        <label>Изображение <sup>*</sup></label>
        <div class="form__input-file">
            <input class="visually-hidden" type="file" id="lot-img" name="url" value="">
            <label for="lot-img">
                Добавить
            </label>
        </div>
    </div>
    <div class="form__container-three <?= isset($errors['price']) ? "form__item--invalid" : "" ?>">
        <div class="form__item form__item--small">
            <label for="lot-rate">Начальная цена <sup>*</sup></label>
            <input id="lot-rate" type="text" name="price" placeholder="0"
                   value="<?php if (isset($lot['price'])) echo $lot['price'] ?>">
            <span class="form__error"><?php if (isset($errors['price'])) echo $errors['price'] ?></span>
        </div>
        <div class="form__item form__item--small <?= isset($errors['step']) ? "form__item--invalid" : "" ?>">
            <label for="lot-step">Шаг ставки <sup>*</sup></label>
            <input id="lot-step" type="text" name="step" placeholder="0"
                   value="<?php if (isset($lot['step'])) echo $lot['step'] ?>">
            <span class="form__error"><?php if (isset($errors['step'])) echo $errors['step'] ?></span>
        </div>
        <div class="form__item <?= isset($errors['dt_end']) ? "form__item--invalid" : "" ?>">
            <label for="lot-date">Дата окончания торгов <sup>*</sup></label>
            <input class="form__input-date" id="lot-date" type="text" name="dt_end"
                   placeholder="Введите дату в формате ГГГГ-ММ-ДД"
                   value="<?php if (isset($lot['dt_end'])) echo $lot['dt_end'] ?>">
            <span class="form__error"><?php if (isset($errors['dt_end'])) echo $errors['dt_end'] ?></span>
        </div>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Добавить лот</button>
</form>
