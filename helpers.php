<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */


function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Преобразует отрезок времени timestamp в строку
 *
 * @param $time точка времени
 *
 * @return Отрезок времени строкой
 */
function show_date($time) { // Определяем количество и тип единицы измерения
    $time = time() - strtotime($time);
    if ($time < 60) {
        return 'меньше минуты назад';
    } elseif ($time < 3600) {
        return dimension((int)($time/60), 'i');
    } elseif ($time < 86400) {
        return dimension((int)($time/3600), 'G');
    } elseif ($time < 2592000) {
        return dimension((int)($time/86400), 'j');
    } elseif ($time < 31104000) {
        return dimension((int)($time/2592000), 'n');
    } elseif ($time >= 31104000) {
        return dimension((int)($time/31104000), 'Y');
    }
}

/**
 * Вспомогательная функция для функции show_date
 *
 * @param $time числовое выражение оставшегося времени
 * @param $type тип временного отрезка минучы, часы и т.д.
 *
 * @return Отрезок времени строкой
 */

function dimension($time, $type) { // Определяем склонение единицы измерения
    $dimension = array(
        'n' => array('месяцев', 'месяц', 'месяца', 'месяц'),
        'j' => array('дней', 'день', 'дня'),
        'G' => array('часов', 'час', 'часа'),
        'i' => array('минут', 'минуту', 'минуты'),
        'Y' => array('лет', 'год', 'года')
    );
    if ($time >= 5 && $time <= 20)
        $n = 0;
    else if ($time === 1 || $time % 10 === 1)
        $n = 1;
    else if (($time <= 4 && $time >= 1) || ($time % 10 <= 4 && $time % 10 >= 1))
        $n = 2;
    else
        $n = 0;
    return $time.' '.$dimension[$type][$n]. ' назад';

}

/**
 * Удаляет html и php теги из текста
 *
 * @param $str Текст строкой
 *
 * @return $texp Текст без тегов
 */

function esc($str)
{
    $text = strip_tags($str);

    return $text;
}

/**
 * Проверяет больше ли суток осталось до наступления точки времени
 *
 * @param $date Точка времени в будущем
 *
 * @return bool true Если осталось больше суток
 */

function is_date_not_end($date)
{
    $date_in_sec = strtotime($date);
    $date_over_day = strtotime('today') + 86400;
    if ($date_in_sec > $date_over_day) {
        return true;
    }
}

/**
 * Форматирует число в формат цены
 *
 * @param $price Цена числом
 *
 * @return $price Цена в фомате цены
 */

function f_price($price)
{
    $price = ceil($price);
    if ($price > 1000) {
        $price = number_format($price, 0, '', ' ');
    }
    $price .= " ₽";

    return $price;
}

/**
 * Считает сколько осталось времени до даты в будущем
 *
 * @param $dtend Дата в будущем
 * @param $s Вспомогательный аргумент чтобы получить результат в секундах
 *
 * @return $tend Время до наступления даты в формате Hms
 * @return $secs_to_end Время до наступления даты в секундах
 *
 */

function end_time($dtend, $s)
{
    $ts = time();

    $timeend = strtotime($dtend);

    $secs_to_end = $timeend - $ts;

    $hours = floor($secs_to_end / 3600);
    $minutes = floor(($secs_to_end % 3600) / 60);
    $seconds = floor(($secs_to_end % 60) / 60);
    if ($minutes < 10) {
        $minutes = '0' . $minutes;
    }
    if ($seconds < 10) {
        $seconds = '0' . $seconds;
    }
    $tend = $hours . ':' . $minutes . ':' . $seconds ;
    if ($s) {
        return $secs_to_end;
    }

    return $tend;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else if (is_string($value)) {
                $type = 's';
            } else if (is_double($value)) {
                $type = 'd';
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;
    $result = '';

    if (!is_readable($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}


