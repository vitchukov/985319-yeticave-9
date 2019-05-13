<?php
require_once('helpers.php');

session_start();

$user = null;

if ($_SESSION){
    $user = $_SESSION['user'];
   }


$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
$sql = 'SELECT id, name, code FROM categories';
$result = mysqli_query($con, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);


if (!$con) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error, 'categories' => $categories]);
} elseif (isset($_GET['id']) && $_GET['id']) {
    $id = (int)$_GET['id'];

# показать лот по его id. Получите также название категории, к которой принадлежит лот
    $sql = 'select l.name name_l, l.id id_l, url, descr, price, dt_end, MAX(r.sum), step, c.name name_c from lots l '
        . 'join categories c on l.cat_id=c.id '
        . 'left join rates r on r.lot_id=l.id '
        . 'where l.id=' . $id . ' GROUP BY l.id limit 1';
    if ($result = mysqli_query($con, $sql)) {
        if (!mysqli_num_rows($result)) {
            http_response_code(404);
            $page_content = include_template('error.php', ['error' => 'Лота с таким идентификатором не существует',
                'error_code' => '404 Страницы не существует',
                'categories' => $categories]);
        } else {
            $lot = mysqli_fetch_assoc($result);
        }
    }
}
//form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form = $_POST;
    $required = ['rate'];
    $errors = [];


    if (!is_int($form['rate']) && !($form['rate'] > 0)) {
        $errors['rate'] = 'Введите вашу ставку';
    }
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = 'Заполните это поле';
        }
    }
    if (!empty($errors)) {
        $page_content = include_template('lot.php', [
            'categories' => $categories,
            'errors' => $errors,
            'lot' => $lot,
            'form' => $form,
            'tend' => end_time($lot['dt_end'], null),
            'secs' => end_time($lot['dt_end'], 's')
        ]);
    } else {
        $sql = 'INSERT INTO rates (dt_rate, sum, user_id, lot_id) VALUES (NOW(), ?, ?, ?)';
        $stmt = db_get_prepare_stmt($con, $sql, [$form['rate'], $user['id'], $id]);
        $res = mysqli_stmt_execute($stmt);
        if ($res) {
            header("Refresh: 0");
            exit();
        }
    }
} elseif ($lot) {
    $page_content = include_template('lot.php', [
        'categories' => $categories,
        'lot' => $lot,
        'tend' => end_time($lot['dt_end'], null),
        'secs' => end_time($lot['dt_end'], 's')
    ]);
}
//form

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => ' Страница лота',
    'user' => $user
]);

print($layout_content);

