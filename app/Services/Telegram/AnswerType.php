<?php

namespace App\Services\Telegram;

use DefStudio\Telegraph\Exceptions\KeyboardException;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use Illuminate\Support\Str;

class AnswerType
{
    /**
     * @param array $input
     * @return void
     * @throws KeyboardException
     */
    public static function getButtons(array $input): void
    {
        match ($input['type']) {
            'select' => static::createAndSendSelect($input),
            default => self::sendMessage(Str::limit($input['placeholder'], 32)),
        };
    }

    /**
     * @param $input
     * @return void
     */
    public static function createAndSendSelect($input): void
    {
        $buttons = [];

        foreach ($input['options'] as $option) {
            $buttons[] = ReplyButton::make($option);
        }

        self::sendButtons(Str::limit($input['placeholder'], 32), $buttons);
    }

    /**
     * @param string $title
     * @param array $buttons
     * @return void
     */
    public static function sendButtons(string $title, array $buttons): void
    {
        Telegraph::message($title)
            ->replyKeyboard(ReplyKeyboard::make()->buttons($buttons))->send();
    }

    /**
     * @param $msg
     * @return void
     * @throws KeyboardException
     */
    public static function sendMessage($msg): void
    {
        Telegraph::message($msg)->forceReply()->send();
    }

}
