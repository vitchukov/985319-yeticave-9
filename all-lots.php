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

$cat_id = $_GET['cat'] ?? '';
$cat_name = $_GET['name'] ?? '';
if ($cat_id) {

    $cur_page = $_GET['page'] ?? 1;
    $page_items = 9;
    $sql = 'SELECT COUNT(*) as cnt FROM lots where cat_id=(?)';
    $stmt = db_get_prepare_stmt($con, $sql, [$cat_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $items_count = mysqli_fetch_assoc($result)['cnt'];
    $pages_count = ceil($items_count / $page_items);
    $offset = ($cur_page - 1) * $page_items;
    $pages = range(1, $pages_count);

    $sql = 'select l.name name_l,l.id id_l, l.price, l.url, MAX(r.sum), c.name, count(r.id), dt_end from lots l '
        . 'join categories c on l.cat_id=c.id '
        . 'left join rates r on r.lot_id=l.id '
        . 'where cat_id=(?) and dt_end > now() '
        . 'GROUP BY l.id '
        . 'order by l.dt_cr desc LIMIT ' . $page_items . ' OFFSET ' . $offset;

    $stmt = db_get_prepare_stmt($con, $sql, [$cat_id]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

}

$page_content = include_template('all-lots.php', [
    'categories' => $categories,
    'lots' => $lots,
    'cat_id' => $cat_id,
    'cat_name' => $cat_name,
    'pages' => $pages,
    'pages_count' => $pages_count,
    'cur_page' => $cur_page
]);


$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'categories' => $categories,
    'title' => 'Все лоты',
    'user_name' => $user_name
]);

print($layout_content);
