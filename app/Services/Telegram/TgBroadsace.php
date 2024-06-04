<?php

namespace App\Services\Telegram;

use DefStudio\Telegraph\Models\TelegraphChat;
use App\Models\telegramMessages;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class TgBroadsace
{
    /** @var mixed */
    protected $chats;

    /**  @var string */
    protected string $message = '';

    /** @var int */
    protected int $botId;

    /** @var mixed */
    protected ?int $modelId;

    /** @var string */
    protected string $modelType;

    /** @var string|null */
    protected ?string $attachments = null;

    /**
     * Declaration of the allowed HTML tags for sanitization.
     *
     * @var string $allowedTags
     */
    protected string $allowedTags = '<b><i><a><s><u><code><pre><strong><ins><s><strike><del><span><tg-spoiler>';

    public function __construct(int $botId, string $modelType = '', ?int $modelId = null)
    {
        $this->botId = $botId;
        $this->modelId = $modelId;
        $this->modelType = $modelType;

        $this->chats = $this->getChats($this->botId);
    }

    /**
     * @param array $messageIds
     * @param string $message
     * @return void
     */
    public function edit(array $messageIds, string $message): void
    {
        if (function_exists('register_shutdown_function')) {
            register_shutdown_function([$this, 'processEditChats'], $messageIds, $message);
            return;
        }

        $this->processEditChats($messageIds, $message);

    }

    /**
     * Process edit chats
     *
     * @param array $messageIds
     * @param string $message
     *
     * @return void
     */
    private function processEditChats(array $messageIds, string $message): void
    {
        foreach ($this->getChats($this->botId) as $chat) {
            foreach ($messageIds as $messageId) {

                $msg = strip_tags($message, $this->allowedTags);

                if ($this->attachments) {

                    $chat->editMedia($messageId)->photo($this->attachments)->send()->getBody()->getContents();
                    $result = json_decode($chat->editCaption($messageId)->html($msg)->send()->getBody()->getContents(), true);

                    if (!$result['ok']) {
                        json_decode($chat->edit($messageId)->html($msg)->send()->getBody()->getContents(), true);
                    }

                    continue;
                }

                $chat->edit($messageId)->html($msg)->send()->getBody()->getContents();
            }
        }
    }

    /**
     * @param array $messageIds
     * @return array
     */
    public function deleteMessage(array $messageIds): array
    {
        $deletedIds = [];
        foreach ($this->getChats($this->botId) as $chat) {
            foreach ($messageIds as $messageId) {
                $result = json_decode($chat->deleteMessage($messageId)->send()->getBody()->getContents(), true);
                if ($result['ok']) {
                    $deletedIds[] = $messageId;
                }
            }
        }

        return $deletedIds;
    }

    public function setAttachments(string $attachments): void
    {
        $this->attachments = $attachments;
    }

    /**
     * @param $botId
     * @return Collection
     */
    public function getChats($botId): Collection
    {
        return TelegraphChat::where('telegraph_bot_id', $botId)
            ->where('name', 'like', '[channel]%')
            ->get();
    }

    /**
     * @param string $message
     * @return void
     */
    public function send(string $message): void
    {
        if (!$this->chats) {
            return;
        }

        $msg = strip_tags($message, $this->allowedTags);

        if (function_exists('register_shutdown_function')) {
            register_shutdown_function([$this, 'processSendChats'], $message);
            return;
        }

        $this->processSendChats($msg);

    }

    /**
     * Process and send messages to chats.
     *
     * @param $msg
     * @return void
     */
    public function processSendChats($msg)
    {
        foreach ($this->chats as $chat) {

            if ($this->attachments) {
                $result = json_decode($chat->photo($this->attachments)->html($msg)->send()->getBody()->getContents(), true);
            } else {
                $result = json_decode($chat->html($msg)->send()->getBody()->getContents(), true);
            }

            if ($result['ok']) {
                $this->saveToTable($result['result']['message_id']);
                continue;
            }

            Log::error(json_encode($result));
        }
    }

    /**
     * Save message details to the telegraph_messages table
     *
     * @param int $messageId
     * @return void
     */
    public function saveToTable($messageId): void
    {
        $telegraphMessages = new telegramMessages();

        $telegraphMessages->model_id = $this->modelId;
        $telegraphMessages->model_type = $this->modelType;
        $telegraphMessages->telegraph_bot_id = $this->botId;
        $telegraphMessages->message_id = $messageId;

        $telegraphMessages->save();

    }

}
