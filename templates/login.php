<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $cat): ?>
            <li class="nav__item">
                <a href="all-lots.php?cat=<?= $cat['id']; ?>&name=<?= esc($cat['name']); ?>"><?= esc($cat['name']); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
<form class="form container <?= !empty($errors) ? 'form--invalid' : "" ?>" action="login.php" method="post"> <!-- form--invalid -->
    <h2>Вход</h2>
    <div class="form__item <?= isset($errors['email']) ? "form__item--invalid" : "" ?>"> <!-- form__item--invalid -->
        <label for="email">E-mail <sup>*</sup></label>
        <input id="email" type="text" name="email" placeholder="Введите e-mail" value="<?php if (isset($form['email'])) { echo $form['email'];} ?>">
        <span class="form__error"><?php if (isset($errors['email'])) {echo $errors['email'];} ?></span>
    </div>
    <div class="form__item form__item--last <?= isset($errors['password']) ? "form__item--invalid" : "" ?>">
        <label for="password">Пароль <sup>*</sup></label>
        <input id="password" type="password" name="password" placeholder="Введите пароль">
        <span class="form__error"><?php if (isset($errors['password'])) {echo $errors['password'];} ?></span>
    </div>
    <button type="submit" class="button">Войти</button>
</form>