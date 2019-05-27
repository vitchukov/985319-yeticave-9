<?php
require_once('helpers.php');
require_once('init.php');
require_once('getwinner.php');


$sql = 'SELECT id, name, code FROM categories';
$result = mysqli_query($con, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql = 'select l.name name_l,l.id id_l, l.price, l.url, MAX(r.sum), c.name, count(r.id), dt_end from lots l '
    . 'join categories c on l.cat_id=c.id '
    . 'left join rates r on r.lot_id=l.id '
    . 'where dt_end > now() '
    . 'GROUP BY l.id order by l.dt_cr desc limit 6';
$result = mysqli_query($con, $sql);
$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная страница',
    'user_name' => $user_name
]);

print($layout_content);



