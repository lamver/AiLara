<?php

namespace App\Observers;

use App\Models\Modules\Blog\Posts;
use App\Models\telegramMessages;
use App\Services\Telegram\TgBroadsace;

class BlogPostsObserver
{
    /**
     * Handle the Posts "created" event.
     */
    public function created(Posts $post): bool
    {
        if (!$post->telegramBot) {
            return false;
        }

        $botId = $post->telegramBot->id;
        $broadside = new TgBroadsace();

        $imagePost = '';

        if ($post->image) {
            $imagePost = '<img src="' . $post->image . '"/>';
        }

        $broadside->toAllByBotId($botId, Posts::class, $post->id)->html($imagePost . $post->content);

        return true;

    }

    /**
     * Handle the Posts "updated" event.
     */
    public function updated(Posts $post): bool
    {

        $changes = $post->getChanges();

        if (isset($changes['status'])) {
            return $this->handleStatusChange($post, $changes);
        }

        if (!$this->hasTelegramIntegration($post)) {
            return true;
        }

        if ($this->handleContentChange($post, $changes)) {
            return true;
        }

        return true;

    }

    /**
     * Handle the Posts "deleted" event.
     */
    public function deleted(Posts $post): bool
    {
        $telegram = $this->getTelegramInfoFromPost($post);
        $deletedIds = (new TgBroadsace())->deleteMessage($telegram['botId'], $telegram['messageIds']);

        foreach ($deletedIds as $id) {
            TelegramMessages::where('message_id', $id)->delete();
        }

        return true;

    }

    /**
     * @param Posts $post
     * @param array $changes
     * @return bool
     */
    private function handleStatusChange(Posts $post, array $changes): bool
    {
        return match ($post->status) {
            Posts::STATUS[0] => $this->created($post) ?: true,
            Posts::STATUS[1] => $this->deleted($post) ?: true,
            default => false,
        };

    }

    /**
     * @param Posts $post
     * @return bool
     */
    private function hasTelegramIntegration(Posts $post): bool
    {
        return $post->telegramBot && $post->telegramMessages;
    }

    /**
     * @param Posts $post
     * @param array $changes
     * @return bool
     */
    private function handleContentChange(Posts $post, array $changes): bool
    {
        if (isset($changes['content']) && $post->status === Posts::STATUS[0]) {
            // If content is changed and the post status is the first status, update Telegram message
            $telegram = $this->getTelegramInfoFromPost($post);
            (new TgBroadsace())->edit($telegram['botId'], $telegram['messageIds'], $changes['content']);
            return true;
        }

        return false;
    }

    /**
     * @param Posts $post
     * @return array
     */
    protected function getTelegramInfoFromPost(Posts $post): array
    {
        $telegramBotId = $post->telegramBot->id;
        $messageIds = array_column($post->telegramMessages->toArray(), 'message_id');

        return [
            'botId' => $telegramBotId,
            'messageIds' => $messageIds
        ];
    }

}
