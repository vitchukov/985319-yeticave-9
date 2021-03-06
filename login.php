<?php
require_once('helpers.php');
require_once('init.php');

$sql = 'SELECT id, name, code FROM categories';
$result = mysqli_query($con, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_content = include_template('login.php', [
    'categories' => $categories
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $form = $_POST;

    $required = ['email', 'password'];
    $errors = [];
    foreach ($required as $field) {
        if (empty($form[$field])) {
            $errors[$field] = 'Это поле надо заполнить';
        }
    }
    if (isset($form['email'])) {
        $email = mysqli_real_escape_string($con, $form['email']);
        $sql = "SELECT * FROM users WHERE email = '$email'";
        $res = mysqli_query($con, $sql);
        $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;
    }
    if (!count($errors) && $user) {
        if (password_verify($form['password'], $user['password'])) {
            $_SESSION['user'] = $user;
        } else {
            $errors['password'] = 'Неверный пароль';
        }
    } elseif (!$user && $form['email']) {
        $errors['email'] = 'Такой пользователь не найден';
    }

    if (count($errors)) {
        $page_content = include_template('login.php', ['form' => $form, 'errors' => $errors, 'categories' => $categories]);
    } else {
        header("Location: /");
        exit();
    }
} else {
    if (isset($_SESSION['user'])) {
        header("Location: /");
        exit();
    } else {
        $page_content = include_template('login.php', ['categories' => $categories]);
    }
}


$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => ' Страница входа',
]);

print($layout_content);

