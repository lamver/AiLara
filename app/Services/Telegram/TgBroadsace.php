<?php

namespace App\Services\Telegram;

use DefStudio\Telegraph\Models\TelegraphChat;
use App\Models\telegramMessages;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

class TgBroadsace
{
    protected $chats;
    protected string $message = '';
    protected int $botId;
    protected ?int $modelId;
    protected string $modelType;

    protected ?string $attachments = null;
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
        foreach ($this->getChats($this->botId) as $chat) {
            foreach ($messageIds as $messageId) {

                $msg = strip_tags($message, $this->allowedTags);

                if ($this->attachments) {

                    $chat->editMedia($messageId)->photo($this->attachments)->send()->getBody()->getContents();
                    $result = json_decode($chat->editCaption($messageId)->message($msg)->send()->getBody()->getContents(), true);

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
    public function html(string $message): void
    {
        $this->send('html', strip_tags($message, $this->allowedTags));
    }

    /**
     * @param string $message
     * @return void
     */
    public function text(string $message): void
    {
        $this->send('message', strip_tags($message));
    }

    /**
     * @param string $message
     * @return void
     */
    public function markdown(string $message): void
    {
        $this->send('markdown', $message);
    }

    /**
     * @param string $type
     * @param string $message
     * @return void
     */
    public function send(string $type, string $message): void
    {
        if (!$this->chats) {
            return;
        }

        foreach ($this->chats as $chat) {

            if ($this->attachments) {
                $result = json_decode($chat->photo($this->attachments)->html($message)->send()->getBody()->getContents(), true);
            } else {
                $result = json_decode($chat->html($message)->send()->getBody()->getContents(), true);
            }

            if ($result['ok']) {
                $this->saveToTable($result['result']['message_id']);
                continue;
            }

            Log::error(json_decode($result));

        }

    }

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
