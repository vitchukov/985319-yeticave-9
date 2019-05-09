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
    $required = ['name', 'descr', 'price', 'dt_end', 'step', 'category'];
   // $errors = [];
    if ($lot['category'] == 'Выберите категорию') {
        $errors['category'] = 'Выберите категорию';
    }
    if (!is_int($lot['price']) && !($lot['price'] > 0)) {
        $errors['price'] = 'Введите начальную цену';
    }
    if (!is_int($lot['step']) && !($lot['step'] > 0)) {
        $errors['step'] = 'Введите шаг ставки';
    }
    if (!is_date_valid($lot['dt_end'])) {
        $errors['dt_end'] = 'Введите дату завершения торгов ГГГГ-ММ-ДД';
    }
    if (!is_date_not_end($lot['dt_end'])) {
        $errors['dt_end'] = 'Дата должна быть более текущей минимум на сутки';
    }
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Заполните это поле';
        }
    }
    if (!empty($_FILES['url']['name'])) {
        $filename = uniqid();
        $f_type = mime_content_type($_FILES['url']['tmp_name']);
        if ($f_type !== "image/png" && $f_type !== "image/jpeg" && $f_type !== "image/jpg") {
            $errors['url'] = 'Загрузите изображение в формате PNG или JPG';
        } else {
            move_uploaded_file($_FILES['url']['tmp_name'], 'uploads/' . $filename);
            $lot['url'] = 'uploads/' . $filename;
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

