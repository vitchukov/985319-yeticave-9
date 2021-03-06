<?php
require_once('helpers.php');
require_once('init.php');

$page_content = null;
$rates = [];

$sql = 'SELECT id, name, code FROM categories';
$result = mysqli_query($con, $sql);
$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

# показать все ставки пользователя по id. Получите также название категории, к которой принадлежит

$sql = 'select u.contacts, l.id id_l, l.url, l.name name_l, l.dt_end, c.name name_c, r.sum, r.dt_rate dt_r, l.user_win_id from rates r '
    . ' left join lots l on r.lot_id = l.id '
    . ' join categories c on l.cat_id = c.id '
    . ' left join users u on l.user_win_id = u.id '
    . ' where r.user_id=' . $user_id . ' group by r.id order by r.dt_rate desc ';

if ($result = mysqli_query($con, $sql)) {
    if (!mysqli_num_rows($result)) {
        http_response_code(404);
        $page_content = include_template('error.php', ['error' => 'Ставок нет',
            'error_code' => '404 Страницы не существует',
            'categories' => $categories]);
    } else {
        $rates = mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
}

if ($rates) {
    $page_content = include_template('my-bets.php', [
        'categories' => $categories,
        'rates' => $rates,
        'user_name' => $user_name,
        'user_id' => $user_id
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => ' Мои ставки',
    'user_name' => $user_name
]);

print($layout_content);
