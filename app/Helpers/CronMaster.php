<?php

namespace App\Helpers;

class CronMaster
{
    const ALL_FREQUENCIES = [
        'cron' => 'Запустить задачу по расписанию с параметрами cron',
        'everySecond' => 'Запускать задачу ежесекундно',
        'everyTwoSeconds' => 'каждые 2 секунды',
        'everyFiveSeconds' => 'каждые 5 секунд',
        'everyTenSeconds' => 'каждые 10 секунд',
        'everyFifteenSeconds' => 'каждые 15 секунд',
        'everyTwentySeconds' => 'каждые 20 секунд',
        'everyThirtySeconds' => 'каждые 30 секунд',
        'everyMinute' => 'Запускать задачу ежеминутно',
        'everyTwoMinutes' => 'каждые 2 минуты',
        'everyThreeMinutes' => 'каждые 3 минуты',
        'everyFourMinutes' => 'каждые 4 минуты',
        'everyFiveMinutes' => 'каждые 5 минут',
        'everyTenMinutes' => 'каждые 10 минут',
        'everyFifteenMinutes' => 'каждые 15 минут',
        'everyThirtyMinutes' => 'каждые 30 минут',
        'hourly' => 'каждый час',
        'hourlyAt' => 'в 17 минут каждого часа',
        'everyOddHour' => 'каждый нечетный час',
        'everyTwoHours' => 'каждые 2 часа',
        'everyThreeHours' => 'каждые 3 часа',
        'everyFourHours' => 'каждые 4 часа',
        'everySixHours' => 'каждые 6 часов',
        'daily' => 'каждый день в полночь',
        'dailyAt' => 'ежедневно в 13:00',
        'twiceDaily' => 'ежедневно дважды в день: дважды в день: в 1:00 и 13:00',
        'twiceDailyAt' => 'ежедневно в 1:15 и 13:15',
        'weekly' => 'еженедельно в воскресенье в 00:00',
        'weeklyOn' => 'еженедельно в понедельник в 8:00',
        'monthly()' => 'ежемесячно первого числа в 00:00',
        'monthlyOn' => 'ежемесячно 4 числа в 15:00',
        'twiceMonthly' => 'ежемесячно дважды в месяц: 1 и 16 числа в 13:00',
        'lastDayOfMonth' => 'ежемесячно в последний день месяца в 15:00',
        'quarterly' => 'ежеквартально в первый день в 00:00',
        'quarterlyOn' => 'ежеквартально в 4-й день в 14:00.',
        'yearly' => 'ежегодно в первый день в 00:00',
        'yearlyOn' => 'ежегодно в июне первого числа в 17:00',
    ];

    static public function getAllFrequencies()
    {
        return self::ALL_FREQUENCIES;
    }

    static public function isValidFrequency($freq)
    {
        return array_key_exists($freq, self::ALL_FREQUENCIES);
    }

}
