<?php
require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = "Владимир"; // укажите здесь ваше имя


$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
$sql = 'SELECT id, name, code FROM categories';
$result = mysqli_query($con, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);


function esc($str)
{
    $text = strip_tags($str);

    return $text;
}

$page_content = include_template('add.php', [
    'categories' => $categories,
  ]);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lot = $_POST;
    $errors = [];
    if ($lot['category'] == 'Выберите категорию') {
        $errors['category'] = 'Выберите категорию';
    }
    if (!is_int($lot['price'])) {
        $errors['price'] = 'Введите начальную цену';
    }
    if (!is_int($lot['step'])) {
        $errors['step'] = 'Введите шаг ставки';
    }
    if (!is_date_valid($lot['dt_end'])) {
        $errors['dt_end'] = 'Введите дату завершения торгов ГГГГ-ММ-ДД';
    }
    foreach ($lot as $field => $value) {
        if (empty($lot[$field])) {
            $errors[$field] = 'Заполните это поле';
        }
    }
    if (!empty($_FILES['url']['name'])) {
        $tmp_name = $_FILES['url']['tmp_name'];
        $path = $_FILES['url']['name'];
        $f_type = mime_content_type($tmp_name);
        if ($f_type !== "image/png" && $f_type !== "image/jpeg") {
            $errors['url'] = 'Загрузите изображение в формате PNG или JPG';
        } else {
            move_uploaded_file($tmp_name, 'uploads/' . $path);
            $lot['url'] = 'uploads/' . $path;
        }
    } else {
        $errors['url'] = 'Вы не загрузили файл изображения';
    }
    if (!empty($errors)) {
        $page_content = include_template('add.php', [
            'categories' => $categories,
            'errors' => $errors,
            'lot' => $lot,
        ]);
    } else {
        $sql = 'INSERT INTO lots (dt_cr, name, descr, url, price, dt_end, step, user_id, cat_id) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';
        $lot['user_id'] = 1;
        $stmt = db_get_prepare_stmt($con, $sql, [$lot['name'], $lot['descr'], $lot['url'], $lot['price'], $lot['dt_end'], $lot['step'], $lot['user_id'], $lot['category']]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {

            $lot_id = mysqli_insert_id($con);
            header("Location: lot.php?id=" . $lot_id);
        }

    }
} else {
    $page_content = include_template('add.php', [
        'categories' => $categories,
    ]);
}


//    $sql = 'INSERT INTO lots (dt_cr, name, descr, url, price, dt_end, step, user_id, cat_id) VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)';
//
//    $stmt = db_get_prepare_stmt($con, $sql, [$lot['name'], $lot['descr'], $lot['url'], $lot['price'], $lot['dt_end'], $lot['step'], $lot['user_id'], $lot['cat_id']]);
//    $res = mysqli_stmt_execute($stmt);
//
//    if ($res) {
//        $lot_id = mysqli_insert_id($con);
//
//        header("Location: lot.php?id=" . $lot_id);
//    }
//    else {
//        $page_content = include_template('error.php', ['error' => mysqli_error($con), 'categories' => $categories]);
//    }
//}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => ' Добавление лота',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);

print($layout_content);

