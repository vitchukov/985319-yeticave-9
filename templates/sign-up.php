<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $cat): ?>
            <li class="nav__item">
                <a href="all-lots.php?cat=<?= $cat['id']; ?>&name=<?= $cat['name']; ?>"><?= esc($cat['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<form class="form container <?= !empty($errors) ? 'form--invalid' : "" ?>" action="sign-up.php" method="post" autocomplete="off"> <!-- form--invalid -->
    <h2>Регистрация нового аккаунта</h2>
    <div class="form__item <?= isset($errors['email']) ? "form__item--invalid" : "" ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?php if (isset($form['email'])) echo $form['email'] ?>">
        <span class="form__error"><?php if (isset($errors['email'])) echo $errors['email']; ?></span>
    </div>
    <div class="form__item <?= isset($errors['password']) ? "form__item--invalid" : "" ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?php if (isset($errors['password'])) echo 'Введите пароль' ?></span>
    </div>
    <div class="form__item <?= isset($errors['name']) ? "form__item--invalid" : "" ?>">
        <label for="name">Имя <sup>*</sup></label>
        <input id="name" type="text" name="name" placeholder="Введите имя" value="<?php if (isset($form['name'])) echo $form['name'] ?>">
        <span class="form__error"><?php if (isset($errors['name'])) echo 'Введите имя' ?></span>
    </div>
    <div class="form__item <?= isset($errors['contacts']) ? "form__item--invalid" : "" ?>">
        <label for="message">Контактные данные <sup>*</sup></label>
        <textarea id="message" name="contacts" placeholder="Напишите как с вами связаться"></textarea>
        <span class="form__error"><?php if (isset($errors['contacts'])) echo 'Напишите как с вами связаться' ?></span>
    </div>
    <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
    <button type="submit" class="button">Зарегистрироваться</button>
    <a class="text-link" href="../login.php">Уже есть аккаунт</a>
</form>