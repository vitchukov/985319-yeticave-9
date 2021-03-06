<?php
require_once('helpers.php');
require_once('init.php');

$pages = 1;
$pages_count = 1;
$cur_page = 1;


$sql = 'SELECT id, name, code FROM categories';
$result = mysqli_query($con, $sql);

if ($result) {
    $categories = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

$lots = [];

mysqli_query($con, 'CREATE FULLTEXT INDEX lot_search ON lots(name, descr)');

$search = esc(trim($_GET['search'] ?? ''));

if (!empty($search)) {

    $cur_page = $_GET['page'] ?? 1;
    $page_items = 9;
    $sql = 'SELECT COUNT(*) as cnt FROM lots where MATCH(name, descr) AGAINST(?)';
    $stmt = db_get_prepare_stmt($con, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items_count = mysqli_fetch_assoc($result)['cnt'];
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $sql = 'select l.name name_l,l.id id_l, l.price, l.url, MAX(r.sum), c.name, count(r.id), dt_end from lots l '
        . 'join categories c on l.cat_id=c.id '
        . 'left join rates r on r.lot_id=l.id '
        . 'where MATCH(l.name, descr) AGAINST(?) and dt_end > now() '
        . 'GROUP BY l.id '
        . 'order by l.dt_cr desc LIMIT ' . $page_items . ' OFFSET ' . $offset;

    $stmt = db_get_prepare_stmt($con, $sql, [$search]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);
    $page_content = include_template('search.php', [
        'categories' => $categories,
        'lots' => $lots,
        'search' => $search,
        'pages' => $pages,
        'pages_count' => $pages_count,
        'cur_page' => $cur_page
    ]);
} else {
    $page_content = include_template('error.php', ['error' => 'Вы не ввели поисковый запрос!',
        'error_code' => 'Пустой поисковый запрос.',
        'categories' => $categories]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Поиск',
    'user_name' => $user_name
]);

print($layout_content);
