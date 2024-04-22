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
     * A form from db
     * @var array
     */
    protected array $theForm = [];

    /**
     * Params form Handler::theForm
     * @var array
     */
    protected array $theParams = [];

    /**
     * @return void
     */
    public function start(): void
    {
        $form = json_decode($this->bot->aiFrom->form_config, true);
        $form = current($form['tasks']);
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
        $this->theForm = current($form['tasks']);
        $userStep = $this->chat->user_step;
        $this->theParams = $this->theForm['params'];

        $this->steps($userStep, $text);
    }

    /**
     * @param int|null $userStep
     * @param string $text
     * @return void
     * @throws KeyboardException
     */
    public function steps(?int $userStep, string $text): void
    {
        $limitText = Str::limit(trim($text), 32);
        $limitBtnName = Str::limit(trim($this->theForm['btnName']), 32);

        match (true) {
            (is_null($userStep) && $limitText === $limitBtnName) => $this->firstStep(),
            ($userStep >= 0 && isset(array_keys($this->theParams)[$userStep])) => $this->nextStep($userStep, $text),
            default => $this->defaultStep()
        };
    }

    /**
     * @return void
     */
    public function defaultStep(): void
    {
        $this->chat->html("Чтобы начать, пожалуйста, выполните комманду /start")->send();
    }

    /**
     * @return void
     * @throws KeyboardException
     */
    public function firstStep(): void
    {
        $this->saveStep(0, []);
        AnswerType::getButtons(current($this->theParams));
    }

    /**
     * @param int $userStep
     * @param string $text
     * @return void
     */
    public function nextStep(int $userStep, string $text): void
    {
        try {
            // Get the name of the field to save
            $nameOfFieldSave = array_keys($this->theParams)[$userStep];
            $userStep += 1;

            // Save user input
            $userInput = json_decode($this->chat->user_input, true);
            $userInput[$nameOfFieldSave] = $text;
            $this->saveStep($userStep, $userInput);

            // Get the name of the field to show
            $nameOfFieldShow = array_keys($this->theParams)[$userStep];
            $input = $this->theParams[$nameOfFieldShow];

            // Display buttons based on input type
            AnswerType::getButtons($input);
        } catch (\Throwable) {
            // Handle exceptions
            $promptMask = $this->createMask($this->theForm['prompt_mask']);
            $this->saveStep(null, []);
            $this->chat->html($promptMask)->send();

            // Fetch answer from API
            $this->getAnswerFromApi($promptMask);
        }
    }

    /**
     * @param int|null $userStep
     * @param array $data
     * @return void
     */
    public function saveStep(int|null $userStep, array $data): void
    {
        $this->chat->user_input = json_encode($data);
        $this->chat->user_step = $userStep;
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
    public function getAnswerFromApi(string $promptMask, array $result = [], int $steps = 0): void
    {
        $aiSearchApi = new AiSearchApi();
        $message = "";

        if ($steps === 0) {
            $result = $aiSearchApi->taskCreate(['prompt' => $promptMask]);
            $message = $result['message'] ?? "";
        }

        if ($result['result'] === true) {
            sleep($this->sleepSeconds);
            $theAnswer = $aiSearchApi->getTaskByTaskId($result['task_id']);

            if ($theAnswer['result'] === true && $theAnswer['answer']['status'] !== Tasks::STATUS_CREATED && ($this->recursionCounter++) <= $this->recursionTries) {
                $this->getAnswerFromApi($promptMask, $result, $this->recursionCounter);
                return;
            }

            $message = $theAnswer['answer']['answer'];
        }

        Telegraph::html($message)->send();
    }

}
