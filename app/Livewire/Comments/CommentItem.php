<?php

namespace App\Livewire\Comments;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\Comment;

class CommentItem extends Component
{
    /** @var Comment */
    public  $comment;

    /** @var int */
    public int $editCommentId;

    /** @var string */
    public string $editCommentValue = "";

    /** @var int */
    public ?int $replyCommentId;

    /** @var string */
    public string $replyCommentBody;

    /**
     * @param $comment
     * @return void
     */
    public function mount($comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.comments.comment-item', [
            'comments' => $this->comment,
        ]);

    }

    /**
     * @param $id
     * @param $body
     * @return void
     */
    public function startEditingComment($id, $body): void
    {
        $this->editCommentValue = $body;
        $this->editCommentId = (int)$id;

    }

    /**
     * @return void
     */
    public function cancelEditedComment(): void
    {
        $this->reset('editCommentId');
        $this->reset('editCommentValue');
        $this->dispatch('listRender');
    }

    /**
     * @param $id
     * @return void
     */
    public function updateComment($id): void
    {
        $validatedData = $this->validate([
            'editCommentValue' => 'required|string|max:255',
        ]);

        $this->dispatch('commentUpdate', $id, $validatedData['editCommentValue']);
        $this->reset('editCommentId');
        $this->reset('editCommentValue');
    }

    /**
     * @param $id
     * @return void
     */
    public function startReply($id): void
    {
        $this->replyCommentId = (int)$id;
    }

    /**
     * @return void
     */
    public function cancelReply(): void
    {
        $this->replyCommentId = null;
    }

    /**
     * @return void
     */
    public function replyComment(): void
    {

        $validatedData = $this->validate([
            'replyCommentBody' => 'required|string|max:255',
        ]);

        $replyComment = [
            'body' =>  $validatedData['replyCommentBody'],
            'parent_id' => $this->replyCommentId,
            'active' => Comment::STATUS_ACTIVE,
        ];

        $this->dispatch('replyComment', $replyComment);

    }

    /**
     * @param $commentId
     * @return void
     */
    public function deleteComment($commentId): void
    {
        $this->dispatch('deleteComment', $commentId);
    }

}
