<?php
require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = "Владимир"; // укажите здесь ваше имя

$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
$sql = 'SELECT id, name, code FROM categories';
$result = mysqli_query($con, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql = 'select l.name name_l,l.id id_l, l.price, l.url, MAX(r.sum), c.name from lots l '
. 'join categories c on l.cat_id=c.id '
. 'left join rates r on r.lot_id=l.id '
. 'GROUP BY l.id order by l.dt_cr limit 6';
$result = mysqli_query($con, $sql);
$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);


function f_price($price)
{
    $price = ceil($price);
    if ($price > 1000) {
        $price = number_format($price, 0, '', ' ');
    }
    $price .= " ₽";

    return $price;
}

function esc($str)
{
    $text = strip_tags($str);

    return $text;
}

//работа со временем

function end_time($s)
{
    $ts = time();

    $tnight = strtotime('tomorrow');

    $secs_to_midnight = $tnight - $ts;

    $hours = floor($secs_to_midnight / 3600);
    $minutes = floor(($secs_to_midnight % 3600) / 60);
    if ($minutes < 10) {
        $minutes = '0' . $minutes;
    }

    $tend = $hours . ':' . $minutes;
    if ($s) {
        return $secs_to_midnight;
    }

    return $tend;
}

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots,
    'tend' => end_time(null),
    'secs' => end_time('s')
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная страница',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);

print($layout_content);



