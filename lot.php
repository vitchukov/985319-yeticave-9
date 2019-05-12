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

if (isset($_GET['id']) && $_GET['id']) {
    $id = (int)$_GET['id'];
}

if (!$con) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error, 'categories' => $categories]);
} else {
# показать лот по его id. Получите также название категории, к которой принадлежит лот
    $sql = 'select l.name name_l, l.id, url, descr, price, dt_end, MAX(r.sum), step, c.name name_c from lots l '
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


if ($lot) {
    $page_content = include_template('lot.php', [
        'categories' => $categories,
        'lot' => $lot,
        'tend' => end_time($lot['dt_end'], null),
        'secs' => end_time($lot['dt_end'], 's')
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => ' Страница лота',
    'user' => $user
]);

print($layout_content);

