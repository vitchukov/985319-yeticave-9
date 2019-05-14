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

$sql = 'select l.name name_l,l.id id_l, l.price, l.url, MAX(r.sum), c.name, dt_end from lots l '
. 'join categories c on l.cat_id=c.id '
. 'left join rates r on r.lot_id=l.id '
. 'where dt_end > now() '
. 'GROUP BY l.id order by l.dt_cr limit 6';
$result = mysqli_query($con, $sql);
$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots
//    'tend' => end_time('', null),
//    'secs' => end_time('', 's')
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная страница',
    'user' => $user
]);

print($layout_content);



