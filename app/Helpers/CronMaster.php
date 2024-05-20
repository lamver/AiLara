<?php

namespace App\Helpers;

class CronMaster
{

    const ALL_FREQUENCIES = [
        'everySecond' => 'Запускать задачу ежесекундно',
        'everyTwoSeconds' => 'каждые 2 секунды',
        'hourly' => 'каждый час',
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
