<?php
require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = "Владимир"; // укажите здесь ваше имя


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
            $page_content = include_template('error.php', ['error' => 'Лота с таким идентификатором не существует', 'categories' => $categories]);
        } else {
            $lot = mysqli_fetch_assoc($result);
        }
    }
}

function f_price($price)
{
    $price = ceil($price);
    if ($price > 1000) {
        $price = number_format($price, 0, '', ' ');
    }
    $price .= " ₽";

    return $price;
}

//работа со временем

function end_time($dtend, $s)
{
    $ts = time();

    $timeend = strtotime($dtend);

    $secs_to_end = $timeend - $ts;

    $hours = floor($secs_to_end / 3600);
    $minutes = floor(($secs_to_end % 3600) / 60);
    if ($minutes < 10) {
        $minutes = '0' . $minutes;
    }

    $tend = $hours . ':' . $minutes;
    if ($s) {
        return $secs_to_end;
    }

    return $tend;
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
    'is_auth' => $is_auth,
    'user_name' => $user_name,
]);

print($layout_content);

