<?php
require_once('helpers.php');

$is_auth = rand(0, 1);

$user_name = "Владимир"; // укажите здесь ваше имя

$categories = ["Доски и лыжи", "Крепления", "Ботинки", "Одежда", "Инструменты", "Разное"];

$lots = [
    [
        'title' => '2014 Rossignol District Snowboard',
        'cat' => 'Доски и лыжи',
        'price' => 10999,
        'url' => 'img/lot-1.jpg'
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'cat' => 'Доски и лыжи',
        'price' => 159999,
        'url' => 'img/lot-2.jpg'
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'cat' => 'Крепления',
        'price' => 8000,
        'url' => 'img/lot-3.jpg'
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'cat' => 'Ботинки',
        'price' => 10999,
        'url' => 'img/lot-4.jpg'
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'cat' => 'Одежда',
        'price' => 7500,
        'url' => 'img/lot-5.jpg'
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'cat' => 'Разное',
        'price' => 5400,
        'url' => 'img/lot-6.jpg'
    ]
];
$index = 0;
$index_f = 0;
$num_count = count($categories);

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


$page_content = include_template('index.php', [
    'index' => $index,
    'categories' => $categories,
    'lots' => $lots,
    'num_count' => $num_count
]);

$layout_content = include_template('layout.php', [
    'index_f' => $index_f,
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Главная страница',
    'is_auth' => $is_auth,
    'user_name' => $user_name,
    'num_count' => $num_count

]);

print($layout_content);



