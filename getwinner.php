<?php
require_once('helpers.php');
require_once ('vendor/autoload.php');

$transport = new Swift_SmtpTransport("phpdemo.ru", 25);
$transport->setUsername("keks@phpdemo.ru");
$transport->setPassword("htmlacademy");

$mailer = new Swift_Mailer($transport);

$logger = new Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

$con = mysqli_connect("localhost", "root", "", "yeticave");
mysqli_set_charset($con, "utf8");
$sql = 'SELECT r.user_id, u.name name_u, u.email, l.name name_l, l.id FROM lots l '
. 'left join rates r on l.id=r.lot_id '
. 'join users u on r.user_id=u.id '
. 'where l.dt_end <= now() and l.user_win_id is null '
. 'order by r.dt_rate desc limit 1';
$result = mysqli_query($con, $sql);
$winner = mysqli_fetch_assoc($result);

$sql = 'update lots set user_win_id = (?) where id = ' . $winner['id'];
$stmt = db_get_prepare_stmt($con, $sql, [$winner['user_id']]);
$res = mysqli_stmt_execute($stmt);
if ($res) {
    $lot_id = mysqli_insert_id($con);
}

$message = new Swift_Message();
$message->setSubject("Ваша ставка победила");
$message->setFrom(['keks@phpdemo.ru' => 'YetiCave']);
$message->setTo($winner['email']);

$msg_content = include_template('email.php', ['winner' => $winner]);
$message->setBody($msg_content, 'text/html');
$result = $mailer->send($message);

if ($result) {
    print("Сообщение успешно отправлено");
}
else {
    print("Не удалось отправить сообщение: " . $logger->dump());
}

