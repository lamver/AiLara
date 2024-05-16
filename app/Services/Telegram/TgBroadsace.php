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
    protected int $modelId;
    protected string $modelType;
    protected string $allowedTags = '<b><i><a><s><u><code><pre><strong><ins><s><strike><del><span><tg-spoiler>';

    /**
     * @param int $botId
     * @param string $modelType
     * @param int $modelId
     * @return $this
     */
    public function toAllByBotId(int $botId, string $modelType, int $modelId)
    {
        $this->botId = $botId;
        $this->modelId = $modelId;
        $this->modelType = $modelType;

        $this->chats = $this->getChats($this->botId);

        return $this;
    }

    /**
     * @param int $botId
     * @param array $messageIds
     * @param string $message
     * @return void
     */
    public function edit(int $botId, array $messageIds, string $message): void
    {
        foreach ($this->getChats($botId) as $chat) {
            foreach ($messageIds as $messageId) {
                $chat->edit($messageId)->message(strip_tags($message, $this->allowedTags))->send();
            }
        }
    }

    /**
     * @param int $botId
     * @param array $messageIds
     * @return array
     */
    public function deleteMessage(int $botId, array $messageIds): array
    {
        $deletedIds = [];
        foreach ($this->getChats($botId) as $chat) {
            foreach ($messageIds as $messageId) {
                $result = json_decode($chat->deleteMessage($messageId)->send()->getBody()->getContents(), true);
                if ($result['ok']) {
                    $deletedIds[] = $messageId;
                }
            }
        }

        return $deletedIds;
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

            $res = $chat->photo('https://aisearch.ru/cdn-cgi/image/fit=contain,width=1024,height=1024,compression=fast/files/175/547517/foto_dlya_stati_s_zagolovkom_cenovaya_voina_na_rynke_elektrokarov_v_kitae_usilivaetsya_i_melkie_igro_547517.png')
                ->send()
                ->getBody()
                ->getContents();

            Log::error(json_decode($res));

            $result = json_decode($chat->{$type}($message)->send()->getBody()->getContents(), true);

            if ($result['ok']) {

                $telegraphMessages = new telegramMessages();

                $telegraphMessages->model_id = $this->modelId;
                $telegraphMessages->model_type = $this->modelType;
                $telegraphMessages->telegraph_bot_id = $this->botId;
                $telegraphMessages->message_id = $result['result']['message_id'];

                $telegraphMessages->save();

                continue;
            }

            Log::error(json_decode($result));
        }

    }

}
