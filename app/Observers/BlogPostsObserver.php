<?php

namespace App\Observers;

use App\Models\Modules\Blog\Posts;
use App\Models\telegramMessages;
use App\Services\Telegram\TgBroadsace;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class BlogPostsObserver
{
    private const NEW_LINE = "\n";

    private const TELEGRAM_LENGTH_TEXT =  1020;

    /**
     * Handle the Posts "created" event.
     */
    public function created(Posts $post): bool
    {
        if (!$post->telegramBot) {
            return false;
        }

        $broadside = new TgBroadsace($post->telegramBot->id, Posts::class, $post->id);

        if ($post->image) {
            $broadside->setAttachments($post->image);
        }

        $postContent = $this->appendAdditionalText($post, $post->content);

        $broadside->send($postContent);

        return true;

    }

    /**
     * Handle the Posts "updated" event.
     */
    public function updated(Posts $post): bool
    {

        $changes = $post->getChanges();

        if (!isset($changes['content'])) {
            $changes['content'] = $post->content;
        }

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

        if ($telegram['botId'] && count($telegram['messageIds'])) {
            $deletedIds = (new TgBroadsace($telegram['botId']))->deleteMessage($telegram['messageIds']);

            foreach ($deletedIds as $id) {
                TelegramMessages::where('message_id', $id)->delete();
            }
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
        if ($post->status === Posts::STATUS[0]) {
            // If content is changed and the post status is the first status, update Telegram message
            $telegram = $this->getTelegramInfoFromPost($post);

            $broadside = new TgBroadsace($telegram['botId'], Posts::class, $post->id);

            if ($post->image) {
                $broadside->setAttachments($post->image);
            }

            $postContent = $this->appendAdditionalText($post, $changes['content']);

            $broadside->edit($telegram['messageIds'], $postContent);

            return true;
        }

        return true;
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

    /**
     * Append additional text and link to the given text based on the post settings.
     *
     * @param Posts $post
     * @param string $text
     * @return string
     */
    private function appendAdditionalText(Posts $post, string $text): string
    {

        $lengthText = (int)$post->telegram_length_text > 0 ? $post->telegram_length_text : self::TELEGRAM_LENGTH_TEXT;

        $addText = "";
        $link = "";

        if ($post->telegram_add_text) {
            $addText = self::NEW_LINE . $post->telegram_add_text;
            $lengthText = $lengthText - mb_strlen($addText);
        }

        if (!!$post->telegram_post_url) {
            $url = trim(Config::get('app.url'), '/') . '/' . trim(str_replace("//", "/", $post->currentPostUrl()), '/');
            $link = self::NEW_LINE . "<a href='" . $url . "'> $post->title </a>";
            $lengthText = $lengthText - mb_strlen($link);
        }

        $text = Str::limit(strip_tags($text), $lengthText);

        if ($addText !== "") {
            $text .= $addText;
        }

        if ($link !== "") {
            $text .= $link;
        }

        return $text;
    }

}
