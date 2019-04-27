<?php
require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = "Владимир"; // укажите здесь ваше имя

//$categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];

$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
$sql = 'SELECT id, name, code FROM categories';
$result = mysqli_query($con, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql = 'select l.name_l, price, url, c.name from lots l
inner join categories c on l.cat_id=c.id';
$result = mysqli_query($con, $sql);
$lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

//$lots = [
//    [
//        'title' => '2014 Rossignol District Snowboard',
//        'cat' => 'Доски и лыжи',
//        'price' => 10999,
//        'url' => 'img/lot-1.jpg'
//    ],
//    [
//        'title' => 'DC Ply Mens 2016/2017 Snowboard',
//        'cat' => 'Доски и лыжи',
//        'price' => 159999,
//        'url' => 'img/lot-2.jpg'
//    ],
//    [
//        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
//        'cat' => 'Крепления',
//        'price' => 8000,
//        'url' => 'img/lot-3.jpg'
//    ],
//    [
//        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
//        'cat' => 'Ботинки',
//        'price' => 10999,
//        'url' => 'img/lot-4.jpg'
//    ],
//    [
//        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
//        'cat' => 'Одежда',
//        'price' => 7500,
//        'url' => 'img/lot-5.jpg'
//    ],
//    [
//        'title' => 'Маска Oakley Canopy',
//        'cat' => 'Разное',
//        'price' => 5400,
//        'url' => 'img/lot-6.jpg'
//    ]
//];


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



