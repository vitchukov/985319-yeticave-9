<?php
require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = "Владимир"; // укажите здесь ваше имя


$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
$sql = 'SELECT id, name, code FROM categories';
$result = mysqli_query($con, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);


$page_content = include_template('login.php', [
    'categories' => $categories
]);


$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => ' Страница входа',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);

print($layout_content);

