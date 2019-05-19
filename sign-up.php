<?php
require_once('helpers.php');

session_start();

$user = null;

if ($_SESSION) {
    $user = $_SESSION['user'];
}

$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
if (!$con) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error, 'categories' => $categories]);
} else {
    $sql = 'SELECT id, name, code FROM categories';
    $result = mysqli_query($con, $sql);
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $page_content = include_template('sign-up.php', [
        'categories' => $categories,
    ]);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $form = $_POST;
        $required = ['email', 'password', 'name', 'contacts'];
        $errors = [];
        if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введите правильный e-mail';
        }
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $errors[$field] = 'Заполните это поле';
                $page_content = include_template('sign-up.php', [
                    'categories' => $categories,
                    'errors' => $errors,
                    'form' => $form
                ]);
            }
        }
        if (empty($errors)) {
            $email = mysqli_real_escape_string($con, $form['email']);
            $sql = "SELECT id FROM users WHERE email = '$email'";
            $res = mysqli_query($con, $sql);

            if (mysqli_num_rows($res) > 0) {
                $errors[] = 'Пользователь с этим email уже зарегистрирован';
                $page_content = include_template('sign-up.php', [
                    'categories' => $categories,
                    'errors' => $errors,
                    'form' => $form
                ]);
            } else {
                $password = password_hash($form['password'], PASSWORD_DEFAULT);

                $sql = 'INSERT INTO users (dt_reg, email, name, contacts, password) VALUES (NOW(), ?, ?, ?, ?)';
                $stmt = db_get_prepare_stmt($con, $sql, [$form['email'], $form['name'], $form['contacts'], $password]);
                $res = mysqli_stmt_execute($stmt);
            }

            if ($res && empty($errors)) {
                header("Location: /login.php");
                exit();
            }
        } else {
            $page_content = include_template('sign-up.php', [
                'categories' => $categories,
                'errors' => $errors,
                'form' => $form
            ]);
        }
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => ' Регистрация',
    'user' => $user,
]);

print($layout_content);

