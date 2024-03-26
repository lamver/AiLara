<?php

namespace App\Services\Telegram;

use App\Models\Tasks;
use App\Services\AiSearchApi;
use DefStudio\Telegraph\Exceptions\KeyboardException;
use DefStudio\Telegraph\Facades\Telegraph;
use DefStudio\Telegraph\Handlers\WebhookHandler;
use DefStudio\Telegraph\Keyboard\ReplyButton;
use DefStudio\Telegraph\Keyboard\ReplyKeyboard;
use Illuminate\Support\Str;
use Log;


class  Handler extends WebhookHandler
{
    /**
     * How many times make request to AiSearchApi::getTaskByTaskId
     * @var int
     */
    protected int $recursionCounter = 0;

    /**
     * The counter of recursion iterators
     * @var int
     */
    protected int $recursionTries = 5;

    /**
     * How many second need to sleep before each request to AiSearchApi::getTaskByTaskId
     * @var int
     */
    protected int $sleepSeconds = 5;

    /**
     * @return void
     */
    public function start(): void
    {
        $form = json_decode($this->bot->aiFrom->form_config, true);
        // Структура формы в БД такая ['tasks'][12]
        $form = $form['tasks'][12];
        $this->saveStep(null, []);
        Telegraph::message($form['name'])
            ->replyKeyboard(
                ReplyKeyboard::make()->row([
                    ReplyButton::make($form['btnName'])
                ])
            )
            ->send();
    }

    /**
     * @param $text
     * @return void
     * @throws KeyboardException
     */
    protected function handleChatMessage($text): void
    {
        $form = json_decode($this->bot->aiFrom->form_config, true);
        // Структура формы в БД такая ['tasks'][12]
        $form = $form['tasks'][12];
        $userStep = $this->chat->user_step;
        $params = $form['params'];

        $this->steps($userStep, $text, $form, $params);
    }

    /**
     * @param $userStep
     * @param $text
     * @param $form
     * @param $params
     * @return void
     * @throws KeyboardException
     */
    public function steps($userStep, $text, $form, $params): void
    {
        if (is_null($userStep) && Str::limit(trim($text), 32) === Str::limit(trim($form['btnName']), 32)) {
            $this->saveStep(0, []);
            AnswerType::getButtons(current($params));
        } elseif ($userStep >= 0 && isset(array_keys($params)[$userStep])) {
            try {
                $nameOfFieldSave = array_keys($params)[$userStep];
                $userStep += 1;

                $this->saveStep(
                    $userStep,
                    array_merge(json_decode($this->chat->user_input, true), [$nameOfFieldSave => $text])
                );

                $nameOfFieldShow = array_keys($params)[$userStep];
                $input = $params[$nameOfFieldShow];

                AnswerType::getButtons($input);
            } catch (\Throwable) {
                $promptMask = $this->createMask($form['prompt_mask']);
                $this->saveStep(null, []);
                $this->chat->html($promptMask)->send();

                $this->getAnswerFromApi($promptMask);
            }
        } else {
            $this->chat->html("Чтобы начать, пожалуйста, выполните комманду /start")->send();
        }
    }

    /**
     * @param int|null $step
     * @param array $data
     * @return void
     */
    public function saveStep(int|null $step, array $data): void
    {
        $this->chat->user_input = json_encode($data);
        $this->chat->user_step = $step;
        $this->chat->save();
    }

    /**
     * @param string $template
     * @return string
     */
    public function createMask(string $template): string
    {
        $mask = [];
        $userData = json_decode($this->chat->user_input, true);
        foreach ($userData as $key => $val) {
            $mask['{{' . $key . '}}'] = $val;
        }

        return strtr($template, $mask);
    }

    /**
     * @param string $promptMask
     * @param array $result
     * @param int $steps
     */
    public function getAnswerFromApi(string $promptMask, array $result = [], int $steps = 0)
    {
        $AiSearchApi = new AiSearchApi();
        $message = "";

        if ($steps === 0) {
            $result = $AiSearchApi->taskCreate($promptMask);
            $message = $result['message'] ?? "";
        }

        if ($result['result'] === true) {
            sleep($this->sleepSeconds);
            $theAnswer = $AiSearchApi->getTaskByTaskId($result['task_id']);

            if ($theAnswer['result'] === true) {
                if ($theAnswer['answer']['status'] !== Tasks::STATUS_CREATED && ($this->recursionCounter++) <= $this->recursionTries) {
                    return $this->getAnswerFromApi($promptMask, $result, $this->recursionCounter);
                }

                $message = $theAnswer['answer']['answer'];
            }
        }

        Telegraph::html($message)->send();
    }

}
