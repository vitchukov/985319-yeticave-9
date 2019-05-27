<?php

$con = mysqli_connect('localhost', 'root', '', 'YetiCave');
mysqli_set_charset($con, 'utf8');
session_start();
$user_name = null;
$user_id = null;
$is_auth = false;

if (!$con) {
    $error = mysqli_connect_error();
    $page_content = include_template('error.php', ['error' => $error, 'categories' => $categories]);
}

if (isset($_SESSION['user'])) {
    $is_auth = true;
    $user_id = $_SESSION['user']['id'];
    $user_name = $_SESSION['user']['name'];
}