<?php
require_once('helpers.php');
require_once('init.php');

$sql = 'SELECT id, name, code FROM categories';
$result = mysqli_query($con, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

if (!$is_auth) {
    http_response_code(403);
    $page_content = include_template('error.php', ['error' => 'Незарегистрированным пользователям нельзя добавлять лоты.<br> Пожалуйста зарегистрируйтесь!',
        'error_code' => '403 Недостаточно прав',
        'categories' => $categories]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lot = $_POST;
    $required = ['name', 'descr', 'price', 'dt_end', 'step', 'category'];
    $errors = [];
    if (isset($lot['category'])) {
        if (!is_numeric($lot['category']) || ($lot['category'] > 6) || ($lot['category'] < 1)) {
            $errors['category'] = 'Выберите категорию';
        }
    }
    if (isset($lot['price'])) {
        $lot['price'] = (int)$lot['price'];
        if (!is_int($lot['price']) || !($lot['price'] > 0)) {
            $errors['price'] = 'Начальная цена должна быть числом больше 0';
        }
    }
    if (isset($lot['step'])) {
        $lot['step'] = (int)$lot['step'];
        if (!is_int($lot['step']) || !($lot['step'] > 0)) {
            $errors['step'] = 'Шаг ставки должен быть числом больше 0';
        }
    }
    if (isset($lot['dt_end'])) {
        if (!is_date_valid($lot['dt_end'])) {
            $errors['dt_end'] = 'Введите дату завершения торгов ГГГГ-ММ-ДД';
        }
        if (!is_date_not_end($lot['dt_end'])) {
            $errors['dt_end'] = 'Дата должна быть более текущей минимум на сутки';
        }
    } else {
        $errors['dt_end'] = 'Дата не введена';
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
        $lot['user_id'] = $user_id;
        $stmt = db_get_prepare_stmt($con, $sql, [$lot['name'], $lot['descr'], $lot['url'], $lot['price'], $lot['dt_end'], $lot['step'], $lot['user_id'], $lot['category']]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {

            $lot_id = mysqli_insert_id($con);
            header("Location: lot.php?id=" . $lot_id);
            exit();
        }

    }
} else {
    $page_content = include_template('add.php', [
        'categories' => $categories
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => ' Добавление лота',
    'user_name' => $user_name
]);

print($layout_content);

